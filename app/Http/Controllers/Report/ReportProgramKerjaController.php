<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JadwalKegiatan\JadwalKegiatanController;
use App\Models\Globals\RevisiFiles;
use App\Models\Rkia\Rkia;
use Illuminate\Http\Request;

class ReportProgramKerjaController extends Controller
{
    protected $module   = 'report.program-kerja';
    protected $routes   = 'report.program-kerja';
    protected $views    = 'report.program-kerja';
    protected $perms    = 'report';

    const TYPE = [
        'schedule'              => [
            'module'            => 'rencana-audit.rkia',
            'route'             => 'rkia.operation',
            'scope'             => 'statusCompleted',
            'show'              => 'Jadwal Kegiatan',
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
                'title' => 'Audit Plan',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Audit Plan' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:no_audit_plan|label:No. Program Kerja|className:text-center'),
                        $this->makeColumn('name:date_audit_plan|label:Tgl Program Kerja|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $records = RevisiFiles::with('target')
            ->whereHasMorph(
                'target',
                [Rkia::class],
                function ($q) {
                    $q->filters();
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
                    return $record->target->year;
                }
            )
            ->addColumn(
                'no_audit_plan',
                function ($record) {
                    return $record->target->no_audit_plan;
                }
            )
            ->addColumn(
                'date_audit_plan',
                function ($record) {
                    return $record->target->date_audit_plan->format('d M Y');
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
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function schedule()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.schedule-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:no_audit_plan|label:No. Program Kerja|className:text-center'),
                        $this->makeColumn('name:date_audit_plan|label:Tgl Program Kerja|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    function scheduleGrid()
    {
        $records = RevisiFiles::with('target')
            ->where('module', 'rencana-audit.rkia')
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
                    return $record->target->year;
                }
            )
            ->addColumn(
                'no_audit_plan',
                function ($record) {
                    return $record->target->no_audit_plan;
                }
            )
            ->addColumn(
                'date_audit_plan',
                function ($record) {
                    return $record->target->date_audit_plan->format('d M Y');
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
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
    public function costPlan()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.cost-plan-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:no_audit_plan|label:No. Program Kerja|className:text-center'),
                        $this->makeColumn('name:date_audit_plan|label:Tgl Program Kerja|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    function costPlanGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.rkia')
            ->where('module', 'rencana-audit.rencana-biaya')
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
                    return $record->target->rkia->year;
                }
            )
            ->addColumn(
                'no_audit_plan',
                function ($record) {
                    return $record->target->rkia->no_audit_plan;
                }
            )
            ->addColumn(
                'date_audit_plan',
                function ($record) {
                    return $record->target->rkia->date_audit_plan->format('d M Y');
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
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function planDocument()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.plan-document-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:no_audit_plan|label:No. Program Kerja|className:text-center'),
                        $this->makeColumn('name:date_audit_plan|label:Tgl Program Kerja|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    function planDocumentGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.rkia')
            ->where('module', 'rencana-audit.document-rencana')
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
                    return $record->target->rkia->year;
                }
            )
            ->addColumn(
                'no_audit_plan',
                function ($record) {
                    return $record->target->rkia->no_audit_plan;
                }
            )
            ->addColumn(
                'date_audit_plan',
                function ($record) {
                    return $record->target->rkia->date_audit_plan->format('d M Y');
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
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function stage()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.stage-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:auditor|label:Auditor|className:text-center width-100px'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px|sortable:false'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    function stageGrid()
    {
        return app(JadwalKegiatanController::class)->grid(true);
    }
}
