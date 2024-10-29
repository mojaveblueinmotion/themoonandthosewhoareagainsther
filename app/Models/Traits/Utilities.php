<?php

namespace App\Models\Traits;

use App\Models\Master\Org\DepartmentAuditee;

trait Utilities
{
    public function canDeleted()
    {
        return true;
    }

    public function labelStatus($status = null)
    {
        return \Base::getStatus($status ?? $this->status);
    }

    public function labelVersion($version = 0)
    {
        return $this->version ?? $version;
    }

    public function getDescriptionRaw($words)
    {
        // Count the number of words in the project name
        $wordCount = str_word_count($words);

        $str = '<div class="d-flex align-items-center justify-content-center make-td-py-0">
                <div class="symbol-group symbol-hover">';

        // Adjust the width and height for a rectangular shape
        $str .= '<div class="symbol symbol-rect symbol-light-success"
                data-toggle="tooltip" title="' . $words . '"
                data-html="true" data-placement="right"
                style="width: 80px; height: 30px;">
                <span style="width: 80px; height: 30px;" class="symbol-label font-weight-bold" style="white-space: nowrap;">' . $wordCount . ' Words</span>
            </div>';

        $str .= '
                </div>
            </div>';

        return $str;
    }

    public function getDescriptionRaw2($words)
    {
        // Count the number of words in the project name
        $text_content_without_html = strip_tags($words);
        $wordCount = str_word_count($text_content_without_html);

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
}
