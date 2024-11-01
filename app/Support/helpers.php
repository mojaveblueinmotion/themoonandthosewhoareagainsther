<?php

use App\Models\Master\Org\OrgStruct;
use Illuminate\Contracts\Routing\UrlGenerator;

if (!function_exists('base')) {
    function base()
    {
        return \Base::getInstance();
    }
}

if (!function_exists('lpad')) {
    function lpad($string, $length = 3, $padder = '0')
    {
        return str_pad($string, $length, $padder, STR_PAD_LEFT);
    }
}

if (!function_exists('read_more_raw')) {
    function read_more_raw($text, $maxLength = 150)
    {
        $result = $text;
        if (strlen($text) > $maxLength) {
            $result   = substr($text, 0, $maxLength);
            $readmore = substr($text, $maxLength);

            $result .= '<a href="javascript: void(0)" class="read-more text-primary" style="cursor:pointer;" onclick="$(this).parent().find(\'.read-more-cage\').show(); $(this).hide()"> Selanjutnya...</a>';

            $readless = '<a href="javascript: void(0)" class="read-less text-primary" style="cursor:pointer;" onclick="$(this).parent().parent().find(\'.read-more\').show(); $(this).parent().hide()"> Kecilkan...</a>';

            $result = "<span>{$result}<span style='display: none' class='read-more-cage'>{$readmore} {$readless}</span></span>";
        }

        return $result;
    }
}

if (!function_exists('read_more')) {
    function read_more($text, $maxLength = 150)
    {
        return utf8_decode(read_more_raw($text, $maxLength));
    }
}

if (!function_exists('number_to_roman')) {
    function number_to_roman($number)
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        $result = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $result .= $roman;
                    break;
                }
            }
        }
        return $result;
    }
}

if (!function_exists('rdd')) {
    function rdd($params = [])
    {
        $data = [
            'message' => 'Debug!',
            'request' => request()->all(),
            'data' => $params,
        ];
        return response()->json($data, 500);
    }
}

if (!function_exists('str_to_array')) {
    function str_to_array($string, $constraint = '|', $delimiter = ':')
    {
        if (is_string($string)) {
            $values = explode($constraint, $string);
            $string = [];
            foreach ($values as $item) {
                $col = explode($delimiter, $item);
                $key = trim($col[0]);
                $val = trim($col[1]);
                switch ($val) {
                    case 'true':
                        $string[$key] = true;
                        break;
                    case 'false':
                        $string[$key] = false;
                        break;
                    case 'null':
                        $string[$key] = null;
                        break;

                    default:
                        $string[$key] = $val;
                        break;
                }
            }
        }
        return $string;
    }
}

if (!function_exists('date_formater')) {
    function date_formater($date, $from = 'd/m/Y', $to = 'Y-m-d H:i:s')
    {
        if ($date) {
            $date = \Carbon\Carbon::createFromFormat($from, $date);
            $date = \Carbon\Carbon::parse($date)->translatedFormat($to);
            return $date;
        }
        return null;
    }
}

if (!function_exists('months')) {
    function months($number = null)
    {
        if (\App::getLocale() == 'id') {
            $months = [
                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
        } else {
            $months = [
                1 => 'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
        }
        if ($number) {
            return $months[(int) $number];
        }
        return $months;
    }
}

if (!function_exists('getRoot')) {
    function getRoot()
    {
        return OrgStruct::with('city')
            ->where('level', 'root')
            ->first();
    }
}

if (!function_exists('getCompanyCity')) {
    function getCompanyCity()
    {
        return getRoot()->city->name ?? '';
    }
}
if (!function_exists('getLogo')) {
    function getLogo($flag = 'perseroan')
    {
        $root = getRoot();
        if ($root) {
            if ($root->files()->where('flag', $flag)->count() > 0) {
                $file = $root->files()->where('flag', $flag)->first();
                return $file->file_url;
            }
        }
        return url(config('base.logo.print'));
    }
}
function isSqlsrv()
{
    return env('DB_CONNECTION') === 'sqlsrv';
}

function rut($name, $parameters = [], $absolute = true)
{
    if (in_array(env('APP_ENV'), ['production', 'staging'])) {
        return str_replace('10.11.12.219', 'auditor.tirtapatriot.net', app('url')->route($name, $parameters, $absolute));
    }
    return app('url')->route($name, $parameters, $absolute);
}

function yurl($path = null, $parameters = [], $secure = null)
{
    if (is_null($path)) {
        return app(UrlGenerator::class);
    }

    return str_replace('10.11.12.219', 'auditor.tirtapatriot.net', app(UrlGenerator::class)->to($path, $parameters, $secure));
}

function getDescriptionRaw($words)
{
    // Count the number of words in the project name
    $text_content_without_html = strip_tags($words);
    $wordCount = str_word_count($text_content_without_html);

    //    dd($wordCount);
    $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                <div class="symbol-group symbol-hover">';

    // Adjust the width and height for a rectangular shape
    $str .= '<div class="symbol symbol-rect symbol-light-success"
            data-toggle="tooltip" title="' . $text_content_without_html . '"
            data-html="true" data-placement="right"
            style="width: 80px; height: 30px;">
            <span style="width: 80px; height: 30px;" class="symbol-label font-weight-bold" style="white-space: nowrap;">' . $wordCount . ' Words</span>
        </div>';

    $str .= '
            </div>
        </div>';

    return $str;
}
