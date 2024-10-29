<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reporting\CostRealization\CostRealizationController;
use App\Models\Globals\RevisiFiles;
use App\Models\Reporting\Lha\Lha;
use Illuminate\Http\Request;

class ReportPelaporanAuditController extends Controller
{
    protected $module   = 'report.pelaporan-audit';
    protected $routes   = 'report.pelaporan-audit';
    protected $views    = 'report.pelaporan-audit';
    protected $perms    = 'report';

    const TYPE = [
        'lhp'               => [
            'module'        => 'reporting.lhp',
            'route'         => 'reporting.lhp',
            'scope'         => 'gridLhaStatusCompleted',
            'show'          => 'LHA',
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
                'title' => 'Pelaporan Audit',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Pelaporan Audit' => route($this->routes . '.index'),
                ],
            ]
        );
    }

    public function index()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.lhp-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:year|label:Tahun|className:text-center'),
                        $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                        $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:surat_tugas|label:Surat Tugas|className:text-center'),
                        $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                        $this->makeColumn('name:auditor'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
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

    public function lhp()
    {
        $this->prepare([
            'tableStruct' => [
                'url'   => route($this->routes . '.lhp-grid'),
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:year|label:Tahun|className:text-center'),
                    $this->makeColumn('name:object_id|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                    $this->makeColumn('name:surat_tugas|label:Surat Tugas|className:text-center'),
                    $this->makeColumn('name:letter_no|label:LHA|className:text-center'),
                    $this->makeColumn('name:auditor'),
                    $this->makeColumn('name:version|label:Versi|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ]
            ]
        ]);

        return $this->render($this->views . '.index');
    }

    public function lhpGrid()
    {
        $records = RevisiFiles::with('target.summary')
            ->whereHasMorph(
                'target',
                [Lha::class],
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
            ->addColumn('main_process_id', function ($record) {
                $str = '';
                foreach ($record->target->summary->aspects as $aspect) {
                    $str .= $aspect->mainProcess->name . '<br>';
                }
                return $str;
            })
            ->addColumn('sub_process_id', function ($record) {
                $str = '';
                foreach ($record->target->summary->aspects as $aspect) {
                    $str .= $aspect->name . '<br>';
                }
                return $str;
            })
            ->addColumn('auditee', function ($record) {
                return $record->target->summary->departmentAuditee->getDepartments();
            })
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
                'surat_tugas',
                function ($record) {
                    return $record->target->summary->getLetterNo()  . '<br>' . $record->target->summary->getDate();
                }
            )
            ->addColumn(
                'letter_no',
                function ($record) {
                    return  $record->target->no_memo  . "<br>" . $record->target->date_memo->translatedFormat('d F Y');
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
                    'main_process_id',
                    'sub_process_id',
                    'auditee',
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
