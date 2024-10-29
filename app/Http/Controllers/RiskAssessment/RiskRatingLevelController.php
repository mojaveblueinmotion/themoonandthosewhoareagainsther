<?php

namespace App\Http\Controllers\RiskAssessment;

use App\Exports\RiskRegisterExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RiskAssessment\CurrentRiskDetailRequest;
use App\Http\Requests\RiskAssessment\RiskRatingRequest;
use App\Models\RiskAssessment\CurrentRisk;
use App\Models\RiskAssessment\CurrentRiskDetail;
use App\Models\RiskAssessment\InherentRisk;
use App\Models\RiskAssessment\RiskRating;
use App\Models\RiskAssessment\RiskRegister;
use App\Models\RiskAssessment\RiskRegisterDetail;
use App\Support\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RiskRatingLevelController extends Controller
{
    protected $module = 'risk-assessment.risk-rating-level';
    protected $routes = 'risk-assessment.risk-rating';
    protected $views = 'risk-assessment.risk-rating';
    protected $perms = 'risk-assessment.risk-rating';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Risk Rating',
            'breadcrumb' => [
                'Risk Assessment' => route($this->routes . '.index'),
                'Risk Rating' => route($this->routes . '.index'),
            ],
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:periode|label:Periode|className:text-center'),
                    $this->makeColumn('name:unit_kerja_id|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                    $this->makeColumn('name:residual_risk|label:Residual Risk|className:text-center'),
                    $this->makeColumn('name:rating|label:Rating|className:text-center'),
                    $this->makeColumn('name:action'),
                ],
            ]
        ]);

        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $location = auth()->user()->position->location ?? null;

        $compl = RiskRegister::where('status', 'completed')

            ->whereRelation('residualRisk', 'status', 'completed')
            ->get();
        foreach ($compl as $item) {
            $risk_rating = RiskRating::firstOrNew(
                [
                    'risk_assessment_register_id' => $item->id
                ]
            );
            $risk_rating->save();
        }

        $records = RiskRegister::gridCurrentRiskCompleted()
            ->where(
                function ($qq) use ($location, $user) {
                    if ($location) {
                        $qq->whereHas('subject', function ($qqq) use ($location) {
                            $qqq->whereIn('id', $location->getIdsWithChild());
                        })->orWhereHas('departmentAuditee', function ($qqq) use ($location) {
                            $qqq->whereHas('departments', function ($qqq) use ($location) {
                                $qqq->whereIn('id', $location->getIdsWithChild());
                            });
                        });
                    }
                    if (!empty($user->position) && $user->position->imAuditor()) {
                        $qq->orWhere(function ($q) {
                            $q->whereRaw('1 = 1'); // This is a placeholder condition that will always be true
                        });
                    }
                    $qq->orWhereHas('approval.details', function ($q) {
                        $q->where('user_id', auth()->user()->id)
                            ->orWhereIn('role_id', auth()->user()->getRoleIds());
                    });
                }
            )
            ->filters()
            ->when(
                request()->get('status') !== '*',
                function ($q) {
                    $status = request()->get('status');

                    if ($status === 'new') {
                        $q->whereHas('currentRisk', function ($qq) use ($status) {
                            $qq->filterBy(['status', '=']);
                        })->orWhereDoesntHave('currentRisk');
                    } else {
                        $q->whereHas('currentRisk', function ($qq) use ($status) {
                            $qq->filterBy(['status', '=']);
                        });
                    }
                }
            )
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'type_id',
                function ($record) use ($user) {
                    return $record->type->show_name;
                }
            )
            ->addColumn(
                'unit_kerja_id',
                function ($record) use ($user) {
                    return $record->type->show_name . '<br>' . $record->subject->name;
                }
            )
            ->addColumn(
                'auditee',
                function ($record) {
                    return $record->departmentAuditee->getDepartments();
                }
            )
            ->addColumn(
                'inherent_risk',
                function ($record) use ($user) {
                    $riskRating = CurrentRisk::select('*', DB::raw('(total_impact * total_likehood) as calculated_score'))
                        ->where('risk_register_id', $record->id)
                        ->orderByDesc(DB::raw('total_impact * total_likehood'))
                        ->first();
                    return '<div class="d-flex justify-content-between"><div>' . $riskRating->riskRegisterDetail->getTotalInherentScore() . '</div></div>';
                }
            )
            ->addColumn(
                'residual_risk',
                function ($record) use ($user) {
                    $riskRating = CurrentRisk::select('*', DB::raw('(total_impact * total_likehood) as calculated_score'))
                        ->where('risk_register_id', $record->id)
                        ->orderByDesc(DB::raw('total_impact * total_likehood'))
                        ->first();
                    return '<div class="d-flex justify-content-center">' . $riskRating->riskRegisterDetail->getTotalCurrentScore() . '</div>';
                }
            )
            ->addColumn(
                'peristiwa',
                function ($record) use ($user) {
                    return $record->getPeristiwaRaw();
                }
            )
            ->addColumn(
                'rating',
                function ($record) use ($user) {
                    $riskRating = CurrentRisk::select('*', DB::raw('(total_impact * total_likehood) as calculated_score'))
                        ->where('risk_register_id', $record->id)
                        ->orderByDesc(DB::raw('total_impact * total_likehood'))
                        ->first();
                    $result = $riskRating->total_impact * $riskRating->total_likehood;

                    $name = 'Medium Risk';
                    if ($result < 5) {
                        $name = 'Low Risk';
                    } elseif ($result >= 10) {
                        $name = 'High Risk';
                    }
                    return $name;
                }
            )
            ->addColumn(
                'periode',
                function ($record) use ($user) {
                    return $record->periode->format('Y');
                }
            )
            // ->addColumn('status', function ($record) use ($user) {
            //     return $record->labelStatus($record->riskRating->status ?? 'new');
            // })
            // ->addColumn(
            //     'version',
            //     function ($record) use ($user) {
            //         if ($record->riskRating) {
            //             return $record->riskRating->version;
            //         }
            //         return "0";
            //     }
            // )
            ->editColumn(
                'updated_by',
                function ($records) use ($user) {
                    if (($records->riskRating->status ?? 'new') == 'new' || empty($records->riskRating->status)) {
                        return '';
                    } else {
                        return $records->riskRating->createdByRaw();
                    }
                }
            )
            ->addColumn(
                'action',
                function ($records) use ($user) {
                    $actions = [];
                    // if (($records->riskRating->status ?? 'new') == 'new') {
                    //     if (isset($records->riskRating->status)) {
                    //         $actions[] = [
                    //             'type' => 'edit',
                    //             'label' => 'Tambah',
                    //             'icon'  => 'fa fa-plus text-primary',
                    //             'page' => true,
                    //             'url' => route($this->routes . '.edit', $records->riskRating->id),
                    //         ];
                    //     }
                    // } else {
                    if (isset($records->riskRating->status)) {
                    }
                    if ($records->riskRating->checkAction('show', $this->perms)) {
                        $actions[] = [
                            'type' => 'show',
                            'label' => 'Lihat',
                            'page' => true,
                            'url' => route($this->routes . '.show', $records->riskRating->id),
                        ];
                        $actions[] = [
                            'type' => 'print',
                            'icon'  => 'fas fa-file-excel text-success',
                            'label' => 'Export Excel',
                            'url' => route($this->routes . '.print', $records->riskRating->id),
                        ];
                    }
                    //     if ($records->riskRating->checkAction('edit', $this->perms)) {
                    //         $actions[] = [
                    //             'type' => 'edit',
                    //             'label' => 'Ubah',
                    //             'page' => true,
                    //             'url' => route($this->routes . '.edit', $records->riskRating->id),
                    //         ];
                    //     }
                    //     if ($records->riskRating->checkAction('approval', $this->perms)) {
                    //         $actions[] = 'type:approval|page:true';
                    //     }
                    //     if ($records->riskRating->checkAction('revisi', $this->perms)) {
                    //         $actions[] = [
                    //             'icon' => 'fa fa-sync text-warning',
                    //             'label' => 'Revisi',
                    //             'url' => route($this->routes . '.revisi', $records->riskRating->id),
                    //             'class' => 'base-form--postByUrl',
                    //             'attrs' => 'data-swal-ok="Revisi" data-swal-text="Revisi akan melalui proses approval terlebih dahulu. Data yang telah di-revisi akan dikembalikan ke status draft untuk dapat diperbarui!"',
                    //         ];
                    //     }
                    //     if ($records->riskRating->checkAction('print', $this->perms)) {
                    //         $actions[] = 'type:print';
                    //     }
                    //     if ($records->riskRating->checkAction('tracking', $this->perms)) {
                    //         $actions[] = 'type:tracking';
                    //     }
                    //     if ($records->riskRating->checkAction('history', $this->perms)) {
                    //         $actions[] = 'type:history';
                    //     }
                    // }


                    return $this->makeButtonDropdown($actions, $records->riskRating->id);
                }
            )
            ->rawColumns(['unit_kerja_id', 'main_process_id', 'peristiwa', 'periode', 'action', 'updated_by', 'status', 'type_id', 'inherent', 'residual', 'auditee', 'jenis_resiko', 'main_process_id', 'peristiwa', 'jenis_resiko', 'main_process_id', 'peristiwa', 'inherent_risk', 'residual_risk'])
            ->make(true);
    }

    public function create()
    {
        $record = new RiskRating;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(RiskRatingRequest $request)
    {
        $record = new RiskRating;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(RiskRating $record)
    {
        // dd($record);
        $this->prepare([

            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:internal_control|label:Internal Control|className:text-center'),
                    $this->makeColumn('name:tgl_realisasi|label:Tgl Realisasi|className:text-center'),
                    $this->makeColumn('name:realisasi|label:Realisasi|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url' => rut($this->routes . '.detailGrid', $record->riskRegister->id),
                'datatable_2' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:objective|label:Objective|className:text-center'),
                    $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                    $this->makeColumn('name:penyebab|label:Risk Cause|className:text-center'),
                    $this->makeColumn('name:dampak|label:Risk Impact|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url_2' => rut('risk-assessment.risk-register.detailGrid', $record->riskRegister->id),
                'datatable_3' => [
                    $this->makeColumn('name:num'),
                    // $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    // $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:level_dampak_id|label:Level Dampak|className:text-center'),
                    $this->makeColumn('name:level_kemungkinan_id|label:Level Kemungkinan|className:text-center'),
                    $this->makeColumn('name:tingkat_resiko_id|label:Tingkat Resiko|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url_3' => rut($this->routes . '.detailInherentGrid', $record->id),
            ]
        ]);
        return $this->render($this->views . '.show', compact('record'));
    }

    public function mapping(RiskRegisterDetail $record)
    {
        return $this->render($this->views . '.mapping', compact('record'));
    }

    public function edit(RiskRating $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:internal_control|label:Internal Control|className:text-center'),
                    $this->makeColumn('name:tgl_realisasi|label:Tgl Realisasi|className:text-center'),
                    $this->makeColumn('name:realisasi|label:Realisasi|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id),
                'datatable_2' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                    $this->makeColumn('name:penyebab|label:Risk Cause|className:text-center'),
                    $this->makeColumn('name:dampak|label:Risk Impact|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url_2' => rut('risk-assessment.risk-register.detailGrid', $record->id),
                'datatable_3' => [
                    $this->makeColumn('name:num'),
                    // $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    // $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:level_dampak_id|label:Level Dampak|className:text-center'),
                    $this->makeColumn('name:level_kemungkinan_id|label:Level Kemungkinan|className:text-center'),
                    $this->makeColumn('name:tingkat_resiko_id|label:Tingkat Resiko|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url_3' => route($this->routes . '.detailInherentGrid', $record->id),
            ]
        ]);
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(RiskRatingRequest $request, RiskRating $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function submit(RiskRating $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(RiskRating $record, RiskRatingRequest $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(RiskRating $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:internal_control|label:Internal Control|className:text-center'),
                    $this->makeColumn('name:tgl_realisasi|label:Tgl Realisasi|className:text-center'),
                    $this->makeColumn('name:realisasi|label:Realisasi|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id),
                'datatable_2' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                    $this->makeColumn('name:penyebab|label:Risk Cause|className:text-center'),
                    $this->makeColumn('name:dampak|label:Risk Impact|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url_2' => rut('risk-assessment.risk-register.detailGrid', $record->riskRegister->id),
                'datatable_3' => [
                    $this->makeColumn('name:num'),
                    // $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    // $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:level_dampak_id|label:Level Dampak|className:text-center'),
                    $this->makeColumn('name:level_kemungkinan_id|label:Level Kemungkinan|className:text-center'),
                    $this->makeColumn('name:tingkat_resiko_id|label:Tingkat Resiko|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url_3' => route($this->routes . '.detailInherentGrid', $record->id),
            ]
        ]);
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function detailGrid(RiskRating $record)
    {
        $user = auth()->user();
        if (!empty(request()->kode_resiko)) {
            $details = CurrentRiskDetail::whereHas('residualRisk', function ($q) use ($record) {
                $q->where('risk_register_id', $record->risk_assessment_register_id);
            })->grid()->filters()
                ->when(
                    $kode_resiko = request()->kode_resiko,
                    function ($q) use ($kode_resiko) {
                        $q->whereHas('residualRisk', function ($qq) use ($kode_resiko) {
                            $qq->whereHas('riskRegisterDetail', function ($qqq) use ($kode_resiko) {
                                $qqq->where('main_process_id', $kode_resiko);
                            });
                        });
                    }
                )
                ->get();

            return \DataTables::of($details)
                ->addColumn('num', function ($detail) {
                    return request()->start;
                })
                ->addColumn('tgl_realisasi', function ($detail) use ($user) {
                    return $detail->tgl_realisasi->translatedFormat('d F Y');
                })
                ->addColumn('internal_control', function ($detail) use ($user) {
                    return $detail->getInternalControlRaw();
                })
                ->addColumn('realisasi', function ($detail) use ($user) {
                    return $detail->getRealisasiRaw();
                })
                ->addColumn('updated_by', function ($detail) use ($user) {
                    return $detail->createdByRaw();
                })
                ->addColumn('action', function ($detail) use ($user) {
                    $actions = [];
                    if ($detail->residualRisk->checkAction('detailShow', $this->perms)) {
                        $actions[] = [
                            'type' => 'show',
                            'url' => route($this->routes . '.detailShow', $detail->id),

                        ];
                    }
                    if ($detail->residualRisk->checkAction('detailEdit', $this->perms)) {
                        $actions[] = [
                            'type' => 'edit',
                            'url' => route($this->routes . '.detailEdit', $detail->id),

                        ];
                    }
                    if ($detail->residualRisk->checkAction('detailDelete', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => route($this->routes . '.detailDestroy', $detail->id),
                        ];
                    }

                    return $this->makeButtonDropdown($actions, $detail->id);
                })
                ->addColumn('action_show', function ($detail) use ($user) {
                    $actions = [];
                    if ($detail->residualRisk->checkAction('detailShow', $this->perms)) {
                        $actions[] = [
                            'type' => 'show',
                            'url' => route($this->routes . '.detailInherentShow', $detail->id),
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $detail->id);
                })
                ->rawColumns(['action', 'action_show', 'updated_by', 'tgl_realisasi', 'realisasi', 'status', 'internal_control'])
                ->make(true);
        } else {
            $details = [];
            return \DataTables::of($details)
                ->addColumn('num', function ($detail) {
                    return request()->start;
                })
                ->addColumn('tgl_realisasi', function ($detail) use ($user) {
                    return '-';
                })
                ->addColumn('internal_control', function ($detail) use ($user) {
                    return '-';
                })
                ->addColumn('realisasi', function ($detail) use ($user) {
                    return '-';
                })
                ->addColumn('updated_by', function ($detail) use ($user) {
                    return '-';
                })
                ->addColumn('action', function ($detail) use ($user) {
                    $actions = [];

                    return $this->makeButtonDropdown($actions, $detail->id);
                })
                ->addColumn('action_show', function ($detail) use ($user) {
                    $actions = [];
                    return $this->makeButtonDropdown($actions, $detail->id);
                })
                ->rawColumns(['action', 'action_show', 'updated_by', 'tgl_realisasi', 'realisasi', 'status', 'internal_control'])
                ->make(true);
        }
    }

    public function detailShow(CurrentRiskDetail $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Residual Risk'
            ]
        );
        $record = $detail->residualRisk;
        return $this->render($this->views . '.detail.show', compact('record', 'detail'));
    }

    public function detailInherentGrid(RiskRating $record)
    {
        $user = auth()->user();
        $details = InherentRisk::where('risk_register_id', $record->risk_assessment_register_id)->grid()->filters()->get();

        return \DataTables::of($details)
            ->addColumn('num', function ($detail) {
                return request()->start;
            })
            ->addColumn('main_process_id', function ($detail) use ($user) {
                return $detail->riskRegisterDetail->mainProcess->name;
            })
            ->addColumn('sub_process_id', function ($detail) use ($user) {
                return $detail->riskRegisterDetail->jenisResiko->name;
            })
            ->addColumn('level_dampak_id', function ($detail) use ($user) {
                return $detail->levelDampak->name;
            })
            ->addColumn('level_kemungkinan_id', function ($detail) use ($user) {
                return $detail->levelKemungkinan->name;
            })
            ->addColumn('tingkat_resiko_id', function ($detail) use ($user) {
                return $detail->tingkatResiko->name;
            })
            ->addColumn('updated_by', function ($detail) use ($user) {
                return $detail->createdByRaw();
            })
            ->addColumn('action', function ($detail) use ($user) {
                $actions = [];
                if ($detail->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'url' => route($this->routes . '.detailInherentShow', $detail->id),

                    ];
                }
                if ($detail->checkAction('detailEdit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'url' => route($this->routes . '.detailEdit', $detail->id),

                    ];
                }
                if ($detail->checkAction('detailDelete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'url' => route($this->routes . '.detailDestroy', $detail->id),
                    ];
                }

                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->addColumn('action_show', function ($detail) use ($user) {
                $actions = [];
                if ($detail->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'url' => route($this->routes . '.detailInherentShow', $detail->id),
                    ];
                }
                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->rawColumns(['action', 'action_show', 'updated_by', 'tgl_realisasi', 'realisasi', 'status', 'internal_control'])
            ->make(true);
    }

    public function detailInherentShow(InherentRisk $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Inherent Risk'
            ]
        );
        return $this->render($this->views . '.detail.show-inherent', compact('detail'));
    }

    public function reject(RiskRating $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(RiskRating $record, Request $request)
    {
        $result = $record->handleApprove($request);
        if ($record->status == 'completed') {
            $this->print($record);
        }
        return $result;
    }

    public function revisi(RiskRating $record, Request $request)
    {
        return $record->handleRevisi($request);
    }

    public function history(RiskRating $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(RiskRating $record)
    {
        $this->prepare(['title' => 'Tracking Approval']);
        $module = $this->module;
        if ($record->status == 'waiting.approval.revisi') {
            $module = $module . '_upgrade';
        } else {
            $module = $this->module;
        }
        return $this->render('globals.tracking', compact('record', 'module'));
    }

    public function print(RiskRegister $record)
    {
        return Excel::download(new RiskRegisterExport($record), 'risk-report.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'autoSize' => true,
        ]);
    }

    public function getInherentRisk(Request $request)
    {
        $record = InherentRisk::withWhereHas(
            'riskRegisterDetail',
            function ($q) use ($request) {
                $q->where('risk_register_id', $request->risk_register_id)
                    ->where('main_process_id', $request->main_process_id)
                    ->where('sub_process_id', $request->sub_process_id);
            }
        )
            ->firstOrFail();
        return response()->json($record);
    }

    public function getCurrentRisk(Request $request)
    {
        $record = CurrentRisk::withWhereHas(
            'riskRegisterDetail',
            function ($q) use ($request) {
                $q->where('risk_register_id', $request->risk_register_id)
                    ->where('main_process_id', $request->main_process_id)
                    ->where('sub_process_id', $request->sub_process_id);
            }
        )
            ->firstOrFail();
        return response()->json($record);
    }
}
