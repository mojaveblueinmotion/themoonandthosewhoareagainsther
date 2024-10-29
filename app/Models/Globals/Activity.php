<?php

namespace App\Models\Globals;

use App\Models\Auth\User;
use App\Models\Model;
use Carbon\Carbon;

class Activity extends Model
{
    protected $table = 'sys_activities';

    protected $guarded = [];

    protected $appends = [
        'show_module',
        'show_message',
    ];

    /** ACCESSOR **/
    public function getShowModuleAttribute()
    {
        $modules = \Base::getModules();
        return $modules[$this->module] ?? '[System]';
    }

    public function getShowMessageAttribute()
    {
        return $this->message;
    }


    /** RELATION **/
    public function target()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** SCOPE **/
    public function scopeGrid($query)
    {
        return $query
            ->when(
                !isSqlsrv(),
                function ($q) {
                    $q->latest();
                }
            );
    }

    public function scopeFilters($query)
    {
        return $query->filterBy('message')
            ->when($module = request()->post('module_name'), function ($q) use ($module) {
                $q->where('module', 'LIKE', '%' . $module . '%');
            })
            ->when($created_by = request()->post('created_by'), function ($q) use ($created_by) {
                $q->where('created_by', $created_by);
            })
            ->when($date_start = request()->post('date_start'), function ($q) use ($date_start) {
                $date_start = Carbon::createFromFormat('d/m/Y', $date_start);
                $q->whereDate('created_at', '>=', $date_start);
            })
            ->when($date_end = request()->post('date_end'), function ($q) use ($date_end) {
                $date_end = Carbon::createFromFormat('d/m/Y', $date_end);
                $q->whereDate('created_at', '<=', $date_end);
            });
    }

    public static function countDailyLoginForDashboard($start, $end, $obj)
    {
        $data = [];
        $startDate = Carbon::createFromFormat('d/m/Y', $start);
        $endDate = Carbon::createFromFormat('d/m/Y', $end);

        $days = $startDate->diffInDays($endDate, false);

        for ($i=0; $i <= $days; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->format('Y-m-d');

            $count = static::whereHas('user')
                ->whereDate('created_at', $currentDate)
                ->where('module', 'auth_login')
                ->when(
                    $obj,
                    function ($q) use ($obj) {
                        $q->whereHas(
                            'user',
                            function ($summary) use ($obj) {
                                $summary = $summary
                                    ->where('perusahaan_id', $obj);
                            }
                        );
                    }
                )
                ->distinct('user_id')
                ->count();
            if (empty($data['total'][$i])) {
                $data['total'][$i] = 0;
            }
            $data['total'][$i] = $data['total'][$i] + $count;
        }
        return $data;
    }

    public static function countMonthlyLoginForDashboard($start, $obj)
    {
        $data = [];

        $startDate = Carbon::createFromFormat('Y', $start)->startOfYear();

        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $startDate->copy();

            $startOfMonth = $currentMonth->startOfMonth()->format('Y-m-d');
            $endOfMonth = $currentMonth->endOfMonth()->format('Y-m-d');

            $count = static::whereHas('user')
                ->whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->where('module', 'auth_login')
                ->when(
                    $obj,
                    function ($q) use ($obj) {
                        $q->whereHas('user', function ($summary) use ($obj) {
                            $summary->where('perusahaan_id', $obj);
                        });
                    }
                )
                ->distinct('user_id')
                ->count();

            $data['total'][$i] = $count;

            $startDate->addMonth();
        }

        return $data;
    }
}
