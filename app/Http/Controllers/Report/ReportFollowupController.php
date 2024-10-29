<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Followup\FollowupMinutesController;
use App\Http\Controllers\Followup\FollowupMonitorController;
use App\Http\Controllers\Followup\FollowupRescheduleController;
use App\Http\Controllers\Followup\MemoTindakLanjutController;
use App\Models\Followup\FollowupMonitor;
use App\Models\Followup\FollowupRegItem;
use App\Models\Followup\FollowupReview;
use App\Models\Followup\MemoTindakLanjut;
use App\Models\Globals\RevisiFiles;
use App\Models\Rkia\Pkpt;
use App\Models\Rkia\Rkia;
use App\Models\Rkia\Summary;
use App\Support\Base;
use Illuminate\Http\Request;

class ReportFollowupController extends Controller
{
    protected $module   = 'report.followup';
    protected $routes   = 'report.followup';
    protected $views    = 'report.followup';
    protected $perms    = 'report';

    const TYPE = [
        'memo'              => [
            'module'        => 'followup.memo-tindak-lanjut',
            'route'         => 'followup.memo',
            'scope'         => 'gridFollowupMemoTindakLanjutStatusCompleted',
            'show'          => 'Memo Tindak Lanjut',
        ],
        'reschedule'        => [
            'module'        => 'followup.reschedule',
            'route'         => 'followup.reschedule',
            'scope'         => 'gridFollowupRescheduleStatusCompleted',
            'show'          => 'Jadwal Ulang',
        ],
        'monitor'           => [
            'module'        => 'followup.followup-monitor',
            'route'         => 'followup.monitor',
            'scope'         => 'gridFollowupMonitorStatusCompleted',
            'show'          => 'Monitoring',
        ],
        'review'            => [
            'module'        => 'followup.followup-review',
            'route'         => 'followup.review',
            'scope'         => 'gridFollowupReviewStatusCompleted',
            'show'          => 'Review Monitoring',
        ],
        // 'minutes'           => [
        //     'module'        => 'followup.followup-minutes',
        //     'route'         => 'followup.minutes',
        //     'scope'         => 'gridFollowupMinutesStatusCompleted',
        //     'show'          => 'Berita Acara',
        // ],
    ];

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Tindak Lanjut Audit',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Tindak Lanjut Audit' => route($this->routes . '.index'),
                ],
            ]
        );
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:kka|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );
        return $this->render($this->views . '.index');
    }

    public function grid(Request $request)
    {
        return \DataTables::of([])->make(true);
    }

    public function memo()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.memo-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:kka|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function memoGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [MemoTindakLanjut::class],
                function ($q) {
                    $q->whereHas('summary', function ($q) {
                        $q->filters();
                    });
                }
            )
            ->where('flag', 'completed')
            ->dtGet();
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn('year', function ($record) use ($user) {
                return $record->target->summary->rkia->year;
            })
            ->addColumn('auditee', function ($record) {
                return $record->target->reg->struct->name;
            })
            ->addColumn(
                'surat_tugas',
                function ($record) use ($user) {
                    return  $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn('kka', function ($record) use ($user) {
                return $record->target->reg->items()->count();
            })
            ->addColumn('month', function ($record) use ($user) {
                return $record->target->summary->getDateRaw();
            })
            ->addColumn('object_id', function ($record) use ($user) {
                return $record->target->summary->type->show_name . '<br>' . $record->target->summary->subject->name;
            })
            ->addColumn('letter_no', function ($record) use ($user) {
                $no = $record->target->summary->lha->no_memo ?? '-';
                $tgl = isset($record->target->summary->lha) ? $record->target->summary->lha->date_memo->translatedFormat('d M Y') : '-';

                return $no . '<br>' . $tgl;
            })
            ->addColumn('auditor', function ($record) use ($user) {
                return $record->target->summary->getAuditorRaw();
            })
            ->addColumn(
                'versi',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) use ($user) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'auditee',
                    'agenda_id',
                    'temuan',
                    'pic',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
    public function reschedule()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.reschedule-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:temuan|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function rescheduleGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.regItem.reg')
            ->where('module', 'followup.reschedule')
            ->whereHasMorph(
                'target',
                [FollowupMonitor::class],
                function ($q) {
                    $q->whereHas('regItem', function ($q) {
                        $q->whereHas('reg', function ($q) {
                            $q->whereHas('summary', function ($q) {
                                $q->filters();
                            });
                        });
                    });
                }
            )
            ->where('flag', 'completed')
            ->dtGet();
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn('year', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->rkia->year;
            })
            ->addColumn('auditee', function ($record) {
                return $record->target->regItem->reg->struct->name;
            })
            ->addColumn(
                'surat_tugas',
                function ($record) use ($user) {
                    return  $record->target->regItem->reg->summary->getLetterNo()  . '<br>' . $record->target->regItem->reg->summary->getDate();
                }
            )
            ->addColumn('month', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->getDateRaw();
            })
            ->addColumn('object_id', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->type->show_name . '<br>' . $record->target->regItem->reg->summary->subject->name;
            })
            ->addColumn('letter_no', function ($record) use ($user) {
                $no = $record->target->regItem->reg->summary->lha->no_memo ?? '-';
                $tgl = isset($record->target->regItem->reg->summary->lha) ? $record->target->regItem->reg->summary->lha->date_memo->translatedFormat('d M Y') : '-';
                return $no . '<br>' . $tgl;
            })
            ->addColumn('temuan', function ($record) use ($user) {
                $tgl = null;
                if (isset($record->target->regItem->sampleDetail->reschedule->deadline_akhir)) {
                    $tgl = $record->target->regItem->sampleDetail->reschedule->deadline_akhir->translatedFormat('d M Y');
                } else {
                    $tgl = $record->target->regItem->sampleDetail->regItem->show_deadline_formated;
                }
                return $record->target->regItem->sampleDetail->id_temuan . '<br>' . $tgl;
            })
            ->addColumn(
                'agenda_id',
                function ($record) use ($user) {
                    return Base::makeLabel($record->target->regItem->sampleDetail->sample->agenda->aspect->name, 'primary') . "<br>" . $record->target->regItem->sampleDetail->sample->agenda->procedure;
                }
            )
            ->addColumn('auditor', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->getAuditorRaw();
            })
            ->addColumn(
                'versi',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) use ($user) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'temuan',
                    'auditee',
                    'agenda_id',
                    'pic',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
    public function monitor()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.monitor-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:temuan|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function monitorGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.regItem.reg')
            ->where('module', 'followup.followup-monitor')
            ->whereHasMorph(
                'target',
                [FollowupMonitor::class],
                function ($q) {
                    $q->whereHas('regItem', function ($q) {
                        $q->whereHas('reg', function ($q) {
                            $q->whereHas('summary', function ($q) {
                                $q->filters();
                            });
                        });
                    });
                }
            )
            ->where('flag', 'completed')
            ->dtGet();
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn('year', function ($record) use ($user) {
                $str = '';
                if (isset($record->target->regItem->monitor->reviewMonitoring->status)) {
                    if ($record->target->regItem->monitor->reviewMonitoring->status  == "completed") {
                        $str = "<span class='badge badge-success cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Sesuai'><i class='fa fa-check text-white'></i><span>";
                    } elseif ($record->target->regItem->monitor->reviewMonitoring->status  == "rejected") {
                        $str = "<span class='badge badge-danger cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Tidak Sesuai'><i class='fa fa-times text-white'></i><span>";
                    } else {
                        $str = "<span class='badge badge-warning cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Belum Direview'><i class='fa fa-minus text-white'></i><span>";
                    }
                }
                // return $record->target->regItem->reg->summary->rkia->year . '<br>' . $str;
                return $record->target->regItem->reg->summary->rkia->year;
            })
            ->addColumn('auditee', function ($record) {
                return $record->target->regItem->reg->struct->name;
            })
            ->addColumn(
                'surat_tugas',
                function ($record) use ($user) {
                    return  $record->target->regItem->reg->summary->getLetterNo()
                        . '<br>' . $record->target->regItem->reg->summary->getDate();
                }
            )
            ->addColumn('month', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->getDateRaw();
            })
            ->addColumn('object_id', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->type->show_name
                    . '<br>' . $record->target->regItem->reg->summary->subject->name;
            })
            ->addColumn('letter_no', function ($record) use ($user) {
                $no = $record->target->regItem->reg->summary->lha->no_memo ?? '-';
                $tgl = isset($record->target->regItem->reg->summary->lha) ? $record->target->regItem->reg->summary->lha->date_memo->translatedFormat('d M Y') : '-';

                return $no . '<br>' . $tgl;
            })
            ->addColumn('temuan', function ($record) use ($user) {
                $tgl = null;
                if (isset($record->target->regItem->reschedule->detail->deadline_akhir)) {
                    $tgl = $record->target->regItem->reschedule->detail->deadline_akhir->translatedFormat('d M Y');
                } else {
                    $tgl = $record->target->regItem->show_deadline_formated;
                }
                return $record->target->regItem->sampleDetail->id_temuan . '<br>' . $tgl;
            })
            ->addColumn(
                'agenda_id',
                function ($record) use ($user) {
                    return \Base::makeLabel($record->target->regItem->sampleDetail->sample->agenda->aspect->name, 'primary') . "<br>" . $record->target->regItem->sampleDetail->sample->agenda->procedure;
                }
            )
            ->addColumn('auditor', function ($record) use ($user) {
                return $record->target->regItem->reg->summary->getAuditorRaw();
            })
            ->addColumn(
                'versi',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) use ($user) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'auditee',
                    'agenda_id',
                    'temuan',
                    'pic',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function review()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.review-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:temuan|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.index');
    }
    public function reviewGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.monitor.regItem.reg')
            ->where('module', 'followup.followup-review')
            ->whereHasMorph(
                'target',
                [FollowupReview::class],
                function ($q) {
                    $q->whereHas('monitor', function ($q) {
                        $q->whereHas('regItem', function ($q) {
                            $q->whereHas('reg', function ($q) {
                                $q->whereHas('summary', function ($q) {
                                    $q->filters();
                                });
                            });
                        });
                    });
                }
            )
            ->where('flag', 'completed')
            ->dtGet();
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn('year', function ($record) {
                // return $reg->summary->rkia->year;
                $str = '';
                if (isset($record->target->monitor->reviewMonitoring->status)) {
                    if ($record->target->monitor->reviewMonitoring->status  == "completed") {
                        $str = "<span class='badge badge-success cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Sesuai'><i class='fa fa-check text-white'></i><span>";
                    } elseif ($record->target->monitor->reviewMonitoring->status  == "rejected") {
                        $str = "<span class='badge badge-danger cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Tidak Sesuai'><i class='fa fa-times text-white'></i><span>";
                    } else {
                        $str = "<span class='badge badge-warning cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Belum Direview'><i class='fa fa-minus text-white'></i><span>";
                    }
                }
                // return $record->target->monitor->regitem->reg->summary->rkia->year . '<br>' . $str;
                return $record->target->monitor->regitem->reg->summary->rkia->year;
            })
            ->addColumn('auditee', function ($record) {
                return $record->target->monitor->regItem->reg->struct->name;
            })
            ->addColumn(
                'surat_tugas',
                function ($record) {
                    return $record->target->monitor->regItem->reg->summary->getLetterNo()  . '<br>' . $record->target->monitor->regItem->reg->summary->getDate();
                }
            )
            ->addColumn('month', function ($record) {
                return $record->target->monitor->regItem->reg->summary->getDateRaw();
            })
            ->addColumn('object_id', function ($record) {
                return $record->target->monitor->regItem->reg->summary->type->show_name . '<br>' . $record->target->monitor->regItem->reg->summary->subject->name;
            })
            ->addColumn('letter_no', function ($record) {
                if ($record->target->monitor->regItem->reg->summary->assignment) {
                    $tgl = $record->target->monitor->regItem->reg->summary->getDateRaw();
                } else {
                    $tgl = $record->target->monitor->regItem->reg->summary->getMonthPlanRaw();
                }

                if (!isset($record->target->monitor->regItem->reg->summary->lha->no_memo)) {
                    return '';
                }
                $no = $record->target->monitor->regItem->reg->summary->lha->no_memo ?? '-';
                $tgl = isset($record->target->monitor->regItem->reg->summary->lha) ? $record->target->monitor->regItem->reg->summary->lha->date_memo->translatedFormat('d M Y') : '-';

                return $no . '<br>' . $tgl;
            })
            ->addColumn('temuan', function ($record) {
                $tgl = null;
                if (isset($record->target->monitor->regItem->reschedule->detail->deadline_akhir)) {
                    $tgl = $record->target->monitor->regItem->reschedule->detail->deadline_akhir->translatedFormat('d M Y');
                } else {
                    $tgl = $record->target->monitor->regItem->show_deadline_formated;
                }
                return $record->target->monitor->regItem->sampleDetail->id_temuan . '<br>' . $tgl;
            })
            ->addColumn(
                'agenda_id',
                function ($record) {
                    return Base::makeLabel($record->target->monitor->regItem->sampleDetail->sample->agenda->aspect->name, 'primary')
                        . "<br>" . $record->target->monitor->regItem->sampleDetail->sample->agenda->procedure;
                }
            )
            ->addColumn('auditor', function ($record) {
                return $record->target->monitor->regItem->reg->summary->getAuditorRaw();
            })
            ->addColumn(
                'versi',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) use ($user) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'auditee',
                    'agenda_id',
                    'temuan',
                    'pic',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function minutes()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.minutes-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:temuan|label:Temuan|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.index');
    }
    public function minutesGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.monitor.summary')
            ->where('module', 'followup.followup-minutes')
            ->dtGet();
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'year',
                function ($record) use ($user) {
                    return $record->target->monitor->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) use ($user) {
                    return $record->target->monitor->summary->getDateRaw();
                }
            )
            ->addColumn(
                'object_id',
                function ($record) use ($user) {
                    return $record->target->monitor->summary->type->show_name . "<br>" . $record->target->monitor->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) use ($user) {
                    return $record->target->monitor->summary->getLetterNo()  . '<br>' . $record->target->monitor->summary->getDate();
                }
            )
            ->addColumn(
                'temuan',
                function ($record) use ($user) {
                    if (!empty($record->target->monitor->reg->items->deadline)) {
                        return $record->target->monitor->reg->sampleDetail->id_temuan . "<br>" . $record->target->monitor->reg->items->deadline->translatedFormat('d F Y');
                    }
                    return $record->target->monitor->reg->sampleDetail->id_temuan . "<br>" . '-';
                }
            )
            ->addColumn(
                'pic',
                function ($record) use ($user) {
                    return $record->target->monitor->reg->sampleDetail->sample->pic->username . "<br>" . $record->target->monitor->reg->sampleDetail->sample->details()->finding()->count() . " Temuan";
                }
            )
            ->addColumn(
                'auditor',
                function ($record) use ($user) {
                    return $record->target->monitor->reg->summary->getAuditorRaw();
                }
            )
            ->addColumn(
                'versi',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) use ($user) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'temuan',
                    'pic',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
}
