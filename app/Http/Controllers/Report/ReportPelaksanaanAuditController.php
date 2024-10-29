<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Conducting\Closing\Closing;
use App\Models\Conducting\Kka\KkaFeedback;
use App\Models\Conducting\Kka\KkaSample;
use App\Models\Conducting\Kka\KkaWorksheet;
use App\Models\Conducting\MemoClosing\MemoClosing;
use App\Models\Conducting\MemoOpening\MemoOpening;
use App\Models\Conducting\Opening\Opening;
use App\Models\Globals\RevisiFiles;
use Illuminate\Http\Request;

class ReportPelaksanaanAuditController extends Controller
{
    protected $module   = 'report.pelaksanaan-audit';
    protected $routes   = 'report.pelaksanaan-audit';
    protected $views    = 'report.pelaksanaan-audit';
    protected $perms    = 'report';

    const TYPE = [
        'memo-opening'      => [
            'module'        => 'conducting.memo-opening',
            'route'         => 'conducting.memo-opening',
            'scope'         => 'gridMemoOpeningStatusCompleted',
            'show'          => 'Memo Opening',
        ],
        'opening'           => [
            'module'        => 'conducting.opening',
            'route'         => 'conducting.opening',
            'scope'         => 'gridOpeningStatusCompleted',
            'show'          => 'Opening Meeting',
        ],
        'sample'            => [
            'module'        => 'conducting.sample',
            'route'         => 'conducting.sample',
            'scope'         => 'gridSampleStatusCompleted',
            'show'          => 'Kertas Kerja',
        ],
        'feedback'          => [
            'module'        => 'conducting.feedback',
            'route'         => 'conducting.feedback',
            'scope'         => 'gridFeedbackStatusCompleted',
            'show'          => 'Tanggapan',
        ],
        'worksheet'         => [
            'module'        => 'conducting.worksheet',
            'route'         => 'conducting.worksheet',
            'scope'         => 'gridWorksheetStatusCompleted',
            'show'          => 'Opini & Rekomendasi',
        ],
        'commitment'          => [
            'module'        => 'conducting.commitment',
            'route'         => 'conducting.commitment',
            'show'          => 'Komentar Manajemen',
        ],
        'memo-closing'      => [
            'module'        => 'conducting.memo-closing',
            'route'         => 'conducting.memo-closing',
            'scope'         => 'gridMemoClosingStatusCompleted',
            'show'          => 'Memo Closing',
        ],
        'closing'           => [
            'module'        => 'conducting.closing',
            'route'         => 'conducting.closing',
            'scope'         => 'gridClosingStatusCompleted',
            'show'          => 'Closing Meeting',
        ],
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
                'title' => 'Pelaksanaan Audit',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Pelaksanaan Audit' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render(
            $this->views . '.index',
        );
    }

    public function grid()
    {
        return \DataTables::of([])
            ->make(true);
    }

    public function memoOpening()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.memo-opening-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }
    public function memoOpeningGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [MemoOpening::class],
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
            ->addColumn(
                'year',
                function ($record) use ($user) {
                    return $record->target->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) use ($user) {
                    return $record->target->summary->getDateRaw();
                }
            )
            ->addColumn(
                'object_id',
                function ($record) use ($user) {
                    return $record->target->summary->type->show_name . "<br>" . $record->target->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) use ($user) {
                    return $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn(
                'auditor',
                function ($record) use ($user) {
                    return $record->target->summary->getAuditorRaw();
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
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    function opening()
    {
        $this->prepare([
            'tableStruct' => [
                'url'   => route($this->routes . '.opening-grid'),
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:year|label:Tahun|className:text-center'),
                    $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                    $this->makeColumn('name:auditor'),
                    $this->makeColumn('name:version|label:Versi|className:text-center'),
                    $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                    $this->makeColumn('name:action'),
                ]
            ]
        ]);

        return $this->render($this->views . '.index');
    }
    public function openingGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [Opening::class],
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
            ->addColumn(
                'year',
                function ($record) use ($user) {
                    return $record->target->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) use ($user) {
                    return $record->target->summary->getDateRaw();
                }
            )
            ->addColumn(
                'object_id',
                function ($record) use ($user) {
                    return $record->target->summary->type->show_name . "<br>" . $record->target->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) use ($user) {
                    return $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn(
                'auditor',
                function ($record) use ($user) {
                    return $record->target->summary->getAuditorRaw();
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
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function sample()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.sample-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:memo|label:KKA|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.index');
    }
    public function sampleGrid()
    {
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [KkaSample::class],
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
            ->addColumn(
                'year',
                function ($record) {
                    return $record->target->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) {
                    return $record->target->summary->getDateRaw();
                }
            )
            ->addColumn('auditee', function ($record) {
                return $record->target->summary->departmentAuditee->getDepartments();
            })
            ->addColumn(
                'object_id',
                function ($record) {
                    return $record->target->summary->type->show_name . "<br>" . $record->target->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) {
                    return  $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn(
                'memo',
                function ($record) {
                    return $record->target->no_kka . "<br>" . $record->target->posting_date?->translatedFormat('d M Y');
                }
            )
            ->addColumn(
                'agenda_id',
                function ($record) {
                    if ($record->target->agenda) {
                        return \Base::makeLabel($record->target->agenda->aspect->name, 'primary') . "<br>" . $record->target->agenda->procedure;
                    }
                    return '-';
                }
            )
            ->addColumn(
                'auditor',
                function ($record) {
                    return $record->target->summary->getAuditorRaw();
                }
            )
            ->addColumn(
                'versi',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'auditee',
                    'memo',
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
    public function feedback()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.feedback-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:memo|label:KKA|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }
    public function feedbackGrid()
    {
        $records = RevisiFiles::with('target.sampleDetail.sample.summary')
            ->whereHasMorph(
                'target',
                [KkaFeedback::class],
                function ($q) {
                    $q->whereHas('sampleDetail.sample.summary', function ($q) {
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
            ->addColumn(
                'year',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getDateRaw();
                }
            )
            ->addColumn('auditee', function ($record) {
                return $record->target->sampleDetail->sample->summary->departmentAuditee->getDepartments();
            })
            ->addColumn(
                'object_id',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->type->show_name . "<br>" . $record->target->sampleDetail->sample->summary->subject->name;
                }
            )
            ->addColumn(
                'agenda_id',
                function ($record) {
                    return \Base::makeLabel($record->target->sampleDetail->sample->agenda->aspect->name, 'primary') . "<br>" . $record->target->sampleDetail->sample->agenda->procedure;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) {
                    return  $record->target->sampleDetail->sample->summary->getLetterNo()  . '<br>' . $record->target->sampleDetail->sample->summary->getDate();
                }
            )
            ->addColumn(
                'memo',
                function ($record) {
                    return $record->target->sampleDetail->sample->no_kka . "<br>" . $record->target->sampleDetail->sample->posting_date->translatedFormat('d M Y');
                }
            )
            ->addColumn(
                'auditor',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getAuditorRaw();
                }
            )
            ->addColumn(
                'versi',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
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
                    'memo',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function worksheet()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.worksheet-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:memo|label:KKA|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }
    public function worksheetGrid()
    {
        $records = RevisiFiles::with('target.sampleDetail.sample.summary')
            ->whereHasMorph(
                'target',
                [KkaWorksheet::class],
                function ($q) {
                    $q->whereHas('sampleDetail.sample.summary', function ($q) {
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
            ->addColumn(
                'year',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getDateRaw();
                }
            )
            ->addColumn('auditee', function ($record) {
                return $record->target->sampleDetail->sample->summary->departmentAuditee->getDepartments();
            })
            ->addColumn(
                'object_id',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->type->show_name . "<br>" . $record->target->sampleDetail->sample->summary->subject->name;
                }
            )
            ->addColumn(
                'agenda_id',
                function ($record) {
                    return \Base::makeLabel($record->target->sampleDetail->sample->agenda->aspect->name, 'primary') . "<br>" . $record->target->sampleDetail->sample->agenda->procedure;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getLetterNo()  . '<br>' . $record->target->sampleDetail->sample->summary->getDate();
                }
            )
            ->addColumn(
                'memo',
                function ($record) {
                    if ($record->target->no_kka) {
                        return $record->target->no_kka . "<br>" . $record->target->posting_date->translatedFormat('d M Y');
                    }
                    return '-';
                }
            )
            ->addColumn(
                'auditor',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getAuditorRaw();
                }
            )
            ->addColumn(
                'versi',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'memo',
                    'auditee',
                    'agenda_id',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function commitment()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => rut($this->routes . '.commitment-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:agenda_id|label:Langkah|className:text-center'),
                        $this->makeColumn('name:memo|label:KKA|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }
    function commitmentGrid()
    {
        $records = RevisiFiles::with('target.sampleDetail.sample.summary')
            ->whereHasMorph(
                'target',
                [KkaFeedback::class],
                function ($q) {
                    $q->whereHas('sampleDetail.sample.summary', function ($q) {
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
            ->addColumn(
                'year',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getDateRaw();
                }
            )
            ->addColumn('auditee', function ($record) {
                return $record->target->sampleDetail->sample->summary->departmentAuditee->getDepartments();
            })
            ->addColumn(
                'object_id',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->type->show_name . "<br>" . $record->target->sampleDetail->sample->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) {
                    return  $record->target->sampleDetail->sample->summary->getLetterNo()  . '<br>' . $record->target->sampleDetail->sample->summary->getDate();
                }
            )
            ->addColumn(
                'agenda_id',
                function ($record) {
                    if ($record->target->sampleDetail->sample->agenda) {
                        return \Base::makeLabel($record->target->sampleDetail->sample->agenda->aspect->name, 'primary') . "<br>" . $record->target->sampleDetail->sample->agenda->procedure;
                    }
                    return '-';
                }
            )
            ->addColumn(
                'memo',
                function ($record) {
                    return $record->target->sampleDetail->sample->no_kka . "<br>" . $record->target->sampleDetail->sample->posting_date->translatedFormat('d M Y');
                }
            )
            ->addColumn(
                'auditor',
                function ($record) {
                    return $record->target->sampleDetail->sample->summary->getAuditorRaw();
                }
            )
            ->addColumn(
                'versi',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'memo',
                    'auditee',
                    'agenda_id',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function memoClosing()
    {
        $this->prepare([
            'tableStruct' => [
                'url'   => route($this->routes . '.memo-closing-grid'),
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:year|label:Tahun|className:text-center'),
                    $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                    $this->makeColumn('name:memo|label:Memo|className:text-center'),
                    $this->makeColumn('name:auditor'),
                    $this->makeColumn('name:version|label:Versi|className:text-center'),
                    $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                    $this->makeColumn('name:action'),
                ]
            ]
        ]);

        return $this->render($this->views . '.index');
    }
    public function memoClosingGrid()
    {
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [MemoClosing::class],
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
            ->addColumn(
                'year',
                function ($record) {
                    return $record->target->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) {
                    return $record->target->summary->getDateRaw();
                }
            )
            ->addColumn(
                'object_id',
                function ($record) {
                    return $record->target->summary->type->show_name . "<br>" . $record->target->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) {
                    return $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn(
                'memo',
                function ($record) {
                    return $record->target->no_memo  . "<br>" . $record->target->date_memo->translatedFormat('d M Y');
                }
            )
            ->addColumn(
                'auditor',
                function ($record) {
                    return $record->target->summary->getAuditorRaw();
                }
            )
            ->addColumn(
                'versi',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->target->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
                }
            )
            ->rawColumns(
                [
                    'year',
                    'object_id',
                    'letter_no',
                    'memo',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function closing()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.closing-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:memo|label:Memo|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }
    public function closingGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [Closing::class],
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
            ->addColumn(
                'year',
                function ($record) use ($user) {
                    return $record->target->summary->rkia->year;
                }
            )
            ->addColumn(
                'month',
                function ($record) use ($user) {
                    return $record->target->summary->getDateRaw();
                }
            )
            ->addColumn(
                'object_id',
                function ($record) use ($user) {
                    return $record->target->summary->type->show_name . "<br>" . $record->target->summary->subject->name;
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) use ($user) {
                    return $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn(
                'memo',
                function ($record) use ($user) {
                    return $record->target->memoClosing->no_memo  . "<br>" . $record->target->memoClosing->date_memo->translatedFormat('d M Y');
                }
            )
            ->addColumn(
                'auditor',
                function ($record) use ($user) {
                    return $record->target->summary->getAuditorRaw();
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
                    'memo',
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
}
