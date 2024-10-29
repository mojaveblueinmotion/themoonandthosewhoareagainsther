<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Globals\RevisiFiles;
use App\Models\Master\Risk\TypeAudit;
use App\Models\Preparation\Apm\Apm;
use App\Models\Preparation\Assignment\Assignment;
use App\Models\Preparation\Instruction\Instruction;
use App\Models\Rkia\Rkia;
use App\Models\Rkia\Summary;
use Illuminate\Http\Request;

class ReportPersiapanAuditController extends Controller
{
    protected $module   = 'report.persiapan-audit';
    protected $routes   = 'report.persiapan-audit';
    protected $views    = 'report.persiapan-audit';
    protected $perms    = 'report';

    const TYPE = [
        'assignment'    => [
            'module'    => 'preparation.assignment',
            'route'     => 'preparation.assignment',
            'scope'     => 'gridAssignmentStatusCompleted',
            'show'      => 'Surat Penugasan',
        ],
        'apm'           => [
            'module'    => 'preparation.apm',
            'route'     => 'preparation.apm',
            'scope'     => 'gridApmStatusCompleted',
            'show'      => 'Program Audit',
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
                'title' => 'Persiapan Audit',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Persiapan Audit' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:status|className:text-center width-80px'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ]
                ]
            ]
        );
        return $this->render($this->views . '.index');
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = [];

        return \DataTables::of($records)->make(true);
    }

    public function assignment()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.assignment-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
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

    public function assignmentGrid(Request $request)
    {
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [Assignment::class],
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
                    $tgl = $record->target->summary->getDateRaw();
                    return  $record->target->summary->getLetterNo() . $tgl;
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
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function apm()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.apm-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:letter_no|label:Surat Tugas|className:text-center'),
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

    public function apmGrid(Request $request)
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [Apm::class],
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
                    'auditor',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
}
