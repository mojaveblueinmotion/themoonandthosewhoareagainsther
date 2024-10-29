<?php

namespace App\Http\Controllers\RiskAssessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiskAssessment\CurrentRiskDetailRequest;
use App\Http\Requests\RiskAssessment\CurrentRiskRequest;
use App\Models\RiskAssessment\CurrentRisk;
use App\Models\RiskAssessment\CurrentRiskDetail;
use App\Models\RiskAssessment\RiskRegister;
use App\Models\RiskAssessment\RiskRegisterDetail;
use App\Support\Base;
use Illuminate\Http\Request;

class CurrentRiskController extends Controller
{
    protected $module = 'risk-assessment.current-risk';
    protected $routes = 'risk-assessment.current-risk';
    protected $views = 'risk-assessment.current-risk';
    protected $perms = 'risk-assessment.current-risk';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Residual Risk',
            'breadcrumb' => [
                'Risk Assessment' => route($this->routes . '.index'),
                'Residual Risk' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:subject|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-left'),
                    $this->makeColumn('name:jenis_resiko|label:Sub Process|className:text-left'),
                    $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                    $this->makeColumn('name:total|label:Total Score|className:text-center'),
                    $this->makeColumn('name:version|label:Versi|className:text-center'),
                    $this->makeColumn('name:status'),
                    $this->makeColumn('name:updated_by'),
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

        $records = RiskRegisterDetail::gridInherentRiskCompleted()
            ->whereHas('riskRegister', function ($q) use ($location, $user) {
                $q->where(
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
                );
            })
            ->filters()
            ->when(
                request()->get('status') !== '*',
                function ($q) {
                    $status = request()->get('status');

                    if ($status === 'new') {
                        $q->whereHas('inherentRisk', function ($qq) use ($status) {
                            $qq->filterBy(['status', '=']);
                        })->orWhereDoesntHave('inherentRisk');
                    } else {
                        $q->whereHas('inherentRisk', function ($qq) use ($status) {
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
                'subject',
                function ($record) use ($user) {
                    return $record->riskRegister->type->show_name . '<br>' . $record->riskRegister->subject->name;
                }
            )
            ->addColumn(
                'auditee',
                function ($record) {
                    return $record->riskRegister->departmentAuditee->getDepartments();
                }
            )
            ->addColumn(
                'total',
                function ($record) use ($user) {
                    return '<div class="d-flex justify-content-around"><div>' . $record->getCurrentLikelihoodScore() . '</div><div>' . $record->getCurrentImpactScore() . '</div></div>';
                }
            )
            ->addColumn(
                'periode',
                function ($record) use ($user) {
                    return $record->riskRegister->periode->format('Y');
                }
            )
            ->addColumn(
                'jenis_resiko',
                function ($record) use ($user) {
                    return $record->jenisResiko->name ?? null;
                }
            )
            ->addColumn(
                'main_process_id',
                function ($record) use ($user) {
                    return $record->kodeResiko->name;
                }
            )
            ->addColumn(
                'peristiwa',
                function ($record) use ($user) {
                    return $record->getPeristiwaRaw();
                }
            )
            ->addColumn(
                'version',
                function ($record) use ($user) {
                    if ($record->currentRisk) {
                        return $record->currentRisk->version;
                    }
                    return "0";
                }
            )
            ->addColumn('status', function ($record) use ($user) {
                if ($record->currentRisk) {
                    return $record->currentRisk->labelStatus();
                }
            })
            ->editColumn(
                'updated_by',
                function ($records) use ($user) {
                    if ($records->currentRisk->status != 'new') {
                        return $records->currentRisk->createdByRaw();
                    }
                }
            )
            ->addColumn(
                'action',
                function ($records) use ($user) {
                    $actions = [];
                    if ($records->currentRisk->status == 'new' || empty($records->currentRisk->status)) {
                        $actions[] = [
                            'type' => 'edit',
                            'label' => 'Tambah',
                            'icon'  => 'fa fa-plus text-primary',
                            'page' => true,
                            'url' => route($this->routes . '.detail', $records->currentRisk->id),
                        ];
                    } else {
                        if ($records->currentRisk->checkAction('show', $this->perms)) {
                            $actions[] = [
                                'type' => 'show',
                                'label' => 'Lihat',
                                'page' => true,
                                'url' => route($this->routes . '.show', $records->currentRisk->id),
                            ];
                        }
                        if ($records->currentRisk->checkAction('edit', $this->perms)) {
                            $actions[] = [
                                'type' => 'edit',
                                'label' => 'Ubah',
                                'page' => true,
                                'url' => route($this->routes . '.detail', $records->currentRisk->id),
                            ];
                        }
                        if ($records->currentRisk->checkAction('approval', $this->perms)) {
                            $actions[] = 'type:approval|page:true';
                        }
                        if ($records->currentRisk->checkAction('revisi', $this->perms)) {
                            $actions[] = [
                                'icon' => 'fa fa-sync text-warning',
                                'label' => 'Revisi',
                                'url' => route($this->routes . '.revisi', $records->id),
                                'class' => 'base-form--postByUrl',
                                'attrs' => 'data-swal-ok="Revisi" data-swal-text="Revisi akan melalui proses approval terlebih dahulu. Data yang telah di-revisi akan dikembalikan ke status draft untuk dapat diperbarui!"',
                            ];
                        }
                        if ($records->currentRisk->checkAction('print', $this->perms)) {
                            $actions[] = 'type:print';
                        }
                        if ($records->currentRisk->checkAction('tracking', $this->perms)) {
                            $actions[] = 'type:tracking';
                        }
                        if ($records->currentRisk->checkAction('history', $this->perms)) {
                            $actions[] = 'type:history';
                        }
                    }


                    return $this->makeButtonDropdown($actions, $records->currentRisk->id);
                }
            )
            ->rawColumns(['jenis_resiko', 'subject', 'main_process_id', 'peristiwa', 'periode', 'action', 'updated_by', 'status', 'auditee', 'total'])
            ->make(true);
    }

    public function create()
    {
        $record = new CurrentRisk;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(CurrentRiskRequest $request)
    {
        $record = new CurrentRisk;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(CurrentRisk $record)
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
                'url_2' => rut('risk-assessment.risk-register.detailGrid', $record->riskRegister->id)
            ]
        ]);
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(CurrentRisk $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(CurrentRiskRequest $request, CurrentRisk $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(CurrentRisk $record)
    {
        return $record->handleDestroy();
    }

    public function detail(CurrentRisk $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:internal_control|label:Internal Control|className:text-center'),
                    $this->makeColumn('name:tgl_realisasi|label:Tgl Realisasi|className:text-center'),
                    $this->makeColumn('name:realisasi|label:Realisasi|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
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
                'url_2' => rut('risk-assessment.risk-register.detailGrid', $record->id)
            ],
        ]);
        return $this->render($this->views . '.detail.index', compact('record'));
    }

    public function detailGrid(CurrentRisk $record)
    {
        $user = auth()->user();
        $details = CurrentRiskDetail::where('current_risk_id', $record->id)->grid()->filters()->get();

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
                        'url' => route($this->routes . '.detailShow', $detail->id),
                    ];
                }
                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->rawColumns(['action', 'action_show', 'updated_by', 'tgl_realisasi', 'realisasi', 'status', 'internal_control'])
            ->make(true);
    }

    public function detailCreate(CurrentRisk $record)
    {
        $this->prepare(
            [
                'title' => 'Detail Residual Risk'
            ]
        );
        return $this->render($this->views . '.detail.create', compact('record'));
    }

    public function detailStore(CurrentRiskDetailRequest $request, CurrentRiskDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
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

    public function detailEdit(CurrentRiskDetail $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Residual Risk'
            ]
        );
        $record = $detail->residualRisk;
        return $this->render($this->views . '.detail.edit', compact('record', 'detail'));
    }

    public function detailUpdate(CurrentRiskDetailRequest $request, CurrentRiskDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailDestroy(CurrentRiskDetail $detail)
    {
        return $detail->handleDetailDestroy($detail);
    }

    public function submit(CurrentRisk $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(CurrentRisk $record, CurrentRiskRequest $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(CurrentRisk $record)
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
            ]
        ]);
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function reject(CurrentRisk $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(CurrentRisk $record, Request $request)
    {
        $result = $record->handleApprove($request);
        if ($record->status == 'completed') {
            $this->print($record);
        }
        return $result;
    }

    public function revisi(CurrentRisk $record, Request $request)
    {
        return $record->handleRevisi($request);
    }

    public function history(CurrentRisk $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(CurrentRisk $record)
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

    public function print(CurrentRisk $record)
    {
        $title = $this->prepared('title');
        $module = $this->prepared('module');
        $pdf = \PDF::loadView(
            $this->views . '.print',
            compact('title', 'module', 'record')
        )
            ->setPaper('a4', 'portrait');
        $file_path = 'report/' . str_replace('.', '/', $module) . '/' . $record->id . '/' . $record->version . '.pdf';
        $record->revisionFiles()->where('module', $module)->where('version', '<', $record->version)->delete();
        if (\Storage::exists($file_path)) {
            $file = $record->revisionFiles()
                ->where('module', $module)
                ->where('version', $record->version)
                ->where('flag', $record->status)
                ->first();
            if ($file && $record->status == 'completed') {
                return $pdf->stream(date('Y-m-d') . ' ' . $title . '.pdf');
                return response()->file(storage_path('app/' . $file->file_path));
            } else {
                \Storage::delete($file_path);
            }
        }
        \Storage::put($file_path, $pdf->output());
        $files = $record->revisionFiles()
            ->firstOrNew([
                'module'    => $module,
                'version'   => $record->version,
                'flag'      => $record->status,
            ]);
        $files->file_path = $file_path;
        $files->save();
        return $pdf->stream(date('Y-m-d') . ' ' . $title . '.pdf');
        return response()->file(storage_path('app/' . $file_path));
    }
}
