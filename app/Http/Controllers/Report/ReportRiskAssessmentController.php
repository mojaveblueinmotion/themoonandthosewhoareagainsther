<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Globals\RevisiFiles;
use App\Models\RiskAssessment\CurrentRisk;
use App\Models\RiskAssessment\InherentRisk;
use App\Models\RiskAssessment\RiskRating;
use App\Models\RiskAssessment\RiskRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportRiskAssessmentController extends Controller
{
    protected $module   = 'report.risk-assessment';
    protected $routes   = 'report.risk-assessment';
    protected $views    = 'report.risk-assessment';
    protected $perms    = 'report';

    const TYPE = [
        'register'              => [
            'module'            => 'risk-assessment.risk-register',
            'route'             => 'risk-assessment.risk-register',
            'scope'             => 'statusCompleted',
            'show'              => 'Risk Register',
        ],
        'inherent'              => [
            'module'            => 'risk-assessment.inherent-risk',
            'route'             => 'risk-assessment.inherent-risk',
            'scope'             => 'statusCompleted',
            'show'              => 'Inherent Risk',
        ],
        'residual'              => [
            'module'            => 'risk-assessment.current-risk',
            'route'             => 'risk-assessment.current-risk',
            'scope'             => 'statusCompleted',
            'show'              => 'Residual Risk',
        ],
        'mapping'               => [
            'module'            => 'risk-assessment.risk-mapping',
            'route'             => 'risk-assessment.risk-mapping',
            'scope'             => 'statusCompleted',
            'show'              => 'Risk Mapping',
        ],
        'rating'                => [
            'module'            => 'risk-assessment.risk-rating',
            'route'             => 'risk-assessment.risk-rating',
            'scope'             => 'statusCompleted',
            'show'              => 'Risk Rating',
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
                'title' => 'Risk Assessment',
                'breadcrumb' => [
                    'Pelaporan' => route($this->routes . '.index'),
                    'Risk Assessment' => route($this->routes . '.index'),
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
                        $this->makeColumn('name:periode|label:Periode|className:text-center'),
                        $this->makeColumn('name:type_id|label:Jenis Audit|className:text-center'),
                        $this->makeColumn('name:unit_kerja_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:jumlah_risk|label:Jumlah Risk|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        // $this->makeColumn('name:status'),
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
        return \DataTables::of([])
            ->make(true);
    }

    public function register()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.register-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:periode|label:Periode|className:text-center'),
                        $this->makeColumn('name:type_id|label:Jenis Audit|className:text-center'),
                        $this->makeColumn('name:unit_kerja_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:jumlah_risk|label:Jumlah Risk|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        // $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function registerGrid()
    {
        $user = auth()->user();
        $request = request();
        $records = RevisiFiles::with('target')
            ->whereHasMorph(
                'target',
                [RiskRegister::class],
                function ($q) use ($request) {
                    $q->filters();
                }
            )
            ->where('flag', 'completed')
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'type_id',
                function ($record) {
                    return $record->target->type->show_name;
                }
            )
            ->addColumn(
                'unit_kerja_id',
                function ($record) {
                    return $record->target->subject->name;
                }
            )
            ->addColumn(
                'periode',
                function ($record) {
                    return $record->target->periode->format('Y');
                }
            )
            ->addColumn('auditee', function ($record) {
                return $record->target->departmentAuditee->getDepartments();
            })
            ->addColumn(
                'jumlah_risk',
                function ($record) {
                    return $record->target->details()->count();
                }
            )
            ->addColumn('status', function ($record) {
                return $record->target->labelStatus();
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn(
                'version',
                function ($record) use ($user) {
                    return $record->version;
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                return "<a href='" . $record->signed_url . "' target='_blank'><i class='pb-1 mr-3 fa fa-print text-dark'></i></a>";
            })
            ->rawColumns(
                [
                    'unit_kerja_id',
                    'auditee',
                    'periode',
                    'type_id',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }
    public function inherent()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.inherent-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:periode|label:Periode|className:text-center'),
                        $this->makeColumn('name:unit_kerja_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                        $this->makeColumn('name:sasaran|label:Sub Process|className:text-center'),
                        $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                        $this->makeColumn('name:total|label:Total Score|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        // $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function inherentGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.riskRegister.type')
            ->whereHasMorph(
                'target',
                [InherentRisk::class],
                function ($q) {
                    $q->filters();
                }
            )
            ->where('flag', 'completed')
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'unit_kerja_id',
                function ($record) {
                    return $record->target->riskRegister->type->show_name . '<br>' . $record->target->riskRegister->subject->name;
                }
            )
            ->addColumn(
                'auditee',
                function ($record) {
                    return $record->target->riskRegister->departmentAuditee->getDepartments();
                }
            )
            ->addColumn(
                'periode',
                function ($record) {
                    return $record->target->riskRegister->periode->format('Y');
                }
            )
            ->addColumn(
                'main_process_id',
                function ($record) {
                    return '<span class="">' . $record->target->riskRegisterDetail->kodeResiko->name . '</span>';
                }
            )
            ->addColumn(
                'sasaran',
                function ($record) {
                    return $record->target->getDescriptionRaw($record->jenisResiko->name ?? null);
                }
            )
            ->addColumn(
                'peristiwa',
                function ($record) {
                    return $record->target->riskRegisterDetail->getPeristiwaRaw();
                }
            )
            ->addColumn(
                'total',
                function ($record) {
                    return '<div class="d-flex justify-content-around"><div>' . $record->target->riskRegisterDetail->getInherentLikelihoodScore() . '</div><div>' . $record->target->riskRegisterDetail->getInherentImpactScore() . '</div></div>';
                }
            )
            ->addColumn(
                'version',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn('status', function ($record) {
                return $record->target->labelStatus();
            })
            ->editColumn(
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
                    'unit_kerja_id',
                    'auditee',
                    'main_process_id',
                    'sasaran',
                    'peristiwa',
                    'periode',
                    'total',
                    'action', 'updated_by', 'status'
                ]
            )
            ->make(true);
    }

    public function residual()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.residual-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:periode|label:Periode|className:text-center'),
                        $this->makeColumn('name:unit_kerja_id|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                        $this->makeColumn('name:sasaran|label:Sub Process|className:text-center'),
                        $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                        $this->makeColumn('name:total|label:Total Score|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        // $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function residualGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.riskRegister.type')
            ->whereHasMorph(
                'target',
                [CurrentRisk::class],
                function ($q) {
                    $q->filters();
                }
            )
            ->where('flag', 'completed')
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'unit_kerja_id',
                function ($record) use ($user) {
                    return $record->target->riskRegister->type->show_name . '<br>' . $record->target->riskRegister->subject->name;
                }
            )
            ->addColumn(
                'auditee',
                function ($record) {
                    return $record->target->riskRegister->departmentAuditee->getDepartments();
                }
            )
            ->addColumn(
                'periode',
                function ($record) use ($user) {
                    return $record->target->riskRegister->periode->format('Y');
                }
            )
            ->addColumn(
                'main_process_id',
                function ($record) use ($user) {
                    return '<span class="">' . $record->target->riskRegisterDetail->kodeResiko->name . '</span>';
                }
            )
            ->addColumn(
                'sasaran',
                function ($record) use ($user) {
                    return $record->target->getDescriptionRaw($record->jenisResiko->name ?? null);
                }
            )
            ->addColumn(
                'peristiwa',
                function ($record) use ($user) {
                    return $record->target->riskRegisterDetail->getPeristiwaRaw();
                }
            )
            ->addColumn(
                'total',
                function ($record) {
                    return '<div class="d-flex justify-content-around"><div>' . $record->target->riskRegisterDetail->getCurrentLikelihoodScore() . '</div><div>' . $record->target->riskRegisterDetail->getCurrentImpactScore() . '</div></div>';
                }
            )
            ->addColumn(
                'version',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn('status', function ($record) {
                return $record->target->labelStatus();
            })
            ->editColumn(
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
                    'unit_kerja_id',
                    'main_process_id',
                    'auditee',
                    'sasaran',
                    'peristiwa',
                    'periode',
                    'total',
                    'action',
                    'updated_by',
                    'status'
                ]
            )
            ->make(true);
    }

    public function mapping()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.mapping-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:periode|label:Periode|className:text-center'),
                        $this->makeColumn('name:unit_kerja_id|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                        $this->makeColumn('name:sasaran|label:Sub Process|className:text-center'),
                        $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                        $this->makeColumn('name:inherent_risk|label:Inherent Risk|className:text-center'),
                        $this->makeColumn('name:residual_risk|label:Residual Risk|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        // $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function mappingGrid()
    {
        $records = RevisiFiles::with('target.riskRegister.type')
            ->whereHasMorph(
                'target',
                [CurrentRisk::class],
                function ($q) {
                    $q
                        ->filters()
                        ->whereHas('riskRegister.riskRating');
                }
            )
            ->where('flag', 'completed')
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'unit_kerja_id',
                function ($record) {
                    return $record->target->riskRegister->type->show_name . '<br>' . $record->target->riskRegister->subject->name;
                }
            )
            ->addColumn(
                'auditee',
                function ($record) {
                    return $record->target->riskRegister->departmentAuditee->getDepartments();
                }
            )
            ->addColumn(
                'periode',
                function ($record) {
                    return $record->target->riskRegister->periode->format('Y');
                }
            )
            ->addColumn(
                'main_process_id',
                function ($record) {
                    return '<span class="">' . $record->target->riskRegisterDetail->kodeResiko->name . '</span>';
                }
            )
            ->addColumn(
                'sasaran',
                function ($record) {
                    return $record->target->getDescriptionRaw($record->jenisResiko->name ?? null);
                }
            )
            ->addColumn(
                'peristiwa',
                function ($record) {
                    return $record->target->riskRegisterDetail->getPeristiwaRaw();
                }
            )
            ->addColumn(
                'inherent_risk',
                function ($record) {
                    return '<div class="d-flex justify-content-around"><div>' . $record->target->riskRegisterDetail->getInherentLikelihoodScore() . '</div><div>' . $record->target->riskRegisterDetail->getInherentImpactScore() . '</div><div>' . $record->target->riskRegisterDetail->getTotalInherentScore() . '</div></div>';
                }
            )
            ->addColumn(
                'residual_risk',
                function ($record) {
                    return '<div class="d-flex justify-content-around"><div>' . $record->target->riskRegisterDetail->getCurrentLikelihoodScore() . '</div><div>' . $record->target->riskRegisterDetail->getCurrentImpactScore() . '</div><div>' . $record->target->riskRegisterDetail->getTotalCurrentScore() . '</div></div>';
                }
            )
            ->addColumn(
                'version',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn('status', function ($record) {
                return $record->target->labelStatus();
            })
            ->editColumn(
                'updated_by',
                function ($records) {
                    return $records->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    $actions = [];
                    $actions[] = [
                        'icon' => 'fa fa-map text-warning',
                        'label' => 'Mapping',
                        // 'page' => true,
                        'url' => route('risk-assessment.risk-mapping.mapping', $record->target->riskRegisterDetail->id),
                    ];
                    return $this->makeButtonDropdown($actions, $record->id);
                }
            )
            ->rawColumns(
                [
                    'periode',
                    'unit_kerja_id',
                    'auditee',
                    'main_process_id',
                    'peristiwa',
                    'sasaran',
                    'peristiwa',
                    'inherent_risk',
                    'residual_risk',
                    'action',
                    'updated_by',
                    'status'
                ]
            )
            ->make(true);
    }

    public function rating()
    {
        $this->prepare(
            [
                'tableStruct' => [
                    'url'   => route($this->routes . '.rating-grid'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:periode|label:Periode|className:text-center'),
                        $this->makeColumn('name:unit_kerja_id|label:Unit Kerja|className:text-center'),
                        $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                        $this->makeColumn('name:residual_risk|label:Residual Risk|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|className:text-center'),
                        // $this->makeColumn('name:status'),
                        $this->makeColumn('name:updated_by'),
                        $this->makeColumn('name:action'),
                    ],
                ]
            ]
        );

        return $this->render($this->views . '.index');
    }

    public function ratingGrid()
    {
        $user = auth()->user();
        $records = RevisiFiles::with('target.type')
            ->whereHasMorph(
                'target',
                [RiskRegister::class],
                function ($q) {
                    $q
                        ->filters()
                        ->whereHas('riskRating');
                }
            )
            ->where('flag', 'completed')
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function () {
                return request()->start;
            })
            ->addColumn(
                'periode',
                function ($record) {
                    return $record->target->periode->format('Y');
                }
            )
            ->addColumn(
                'unit_kerja_id',
                function ($record) {
                    return $record->target->type->show_name . '<br>' . $record->target->subject->name;
                }
            )
            ->addColumn(
                'auditee',
                function ($record) {
                    return $record->target->departmentAuditee->getDepartments();
                }
            )
            ->addColumn(
                'residual_risk',
                function ($record) {
                    $riskRating = CurrentRisk::select('*', DB::raw('(total_impact * total_likehood) as calculated_score'))
                        ->where('risk_register_id', $record->target->id)
                        ->orderByDesc(DB::raw('total_impact * total_likehood'))
                        ->first();
                    return '<div class="d-flex justify-content-center">' . $riskRating->riskRegisterDetail->getTotalCurrentScore() . '</div>';
                }
            )
            ->addColumn(
                'version',
                function ($record) {
                    return $record->version;
                }
            )
            ->addColumn('status', function ($record) {
                return $record->target->labelStatus();
            })
            ->editColumn(
                'updated_by',
                function ($records) {
                    return $records->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    $actions = [];
                    $actions[] = [
                        'type' => 'print',
                        'icon'  => 'fas fa-file-excel text-success',
                        'label' => 'Export Excel',
                        'url' => route('risk-assessment.risk-rating.print', $record->target->riskRating->id),
                    ];
                    return $this->makeButtonDropdown($actions, $record->id);
                }
            )
            ->rawColumns(
                [
                    'unit_kerja_id',
                    'main_process_id',
                    'auditee',
                    'peristiwa',
                    'periode',
                    'residual_risk',
                    'action',
                    'updated_by',
                    'status'
                ]
            )
            ->make(true);
    }
}
