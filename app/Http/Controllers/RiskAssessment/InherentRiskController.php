<?php

namespace App\Http\Controllers\RiskAssessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiskAssessment\InherentRiskRequest;
use App\Models\RiskAssessment\InherentRisk;
use App\Models\RiskAssessment\RiskRegister;
use App\Models\RiskAssessment\RiskRegisterDetail;
use App\Support\Base;
use Illuminate\Http\Request;

class InherentRiskController extends Controller
{
    protected $module = 'risk-assessment.inherent-risk';
    protected $routes = 'risk-assessment.inherent-risk';
    protected $views = 'risk-assessment.inherent-risk';
    protected $perms = 'risk-assessment.inherent-risk';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Inherent Risk',
            'breadcrumb' => [
                'Risk Assessment' => route($this->routes . '.index'),
                'Inherent Risk' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:subject_id|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-left'),
                    $this->makeColumn('name:sasaran|label:Sub Process|className:text-left'),
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

        $records = RiskRegisterDetail::gridCompleted()
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
                'periode',
                function ($record) use ($user) {
                    return $record->riskRegister->periode->format('Y');
                }
            )
            ->addColumn(
                'subject_id',
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
                'main_process_id',
                function ($record) use ($user) {
                    return  $record->kodeResiko->name;
                }
        )
            ->addColumn(
                'sasaran',
                function ($record) use ($user) {
                    return $record->jenisResiko->name ?? null;
                }
            )
            ->addColumn(
                'peristiwa',
                function ($record) use ($user) {
                    return $record->getPeristiwaRaw();
                }
            )
            ->addColumn(
                'total',
                function ($record) use ($user) {
                    return '<div class="d-flex justify-content-around"><div>' . $record->getInherentLikelihoodScore() . '</div><div>' . $record->getInherentImpactScore() . '</div></div>';
                }
            )
            ->addColumn(
                'version',
                function ($record) use ($user) {
                    if ($record->inherentRisk) {
                        return $record->inherentRisk->version;
                    }
                    return "0";
                }
            )
            ->addColumn('status', function ($record) use ($user) {
                return $record->InherentRisk->labelStatus();
            })
            ->editColumn(
                'updated_by',
                function ($records) use ($user) {
                    if ($records->inherentRisk->status == 'new' || empty($records->inherentRisk->status)) {
                        return '';
                    } else {
                        return $records->inherentRisk->createdByRaw();
                    }
                }
            )
            ->addColumn(
                'action',
                function ($records) use ($user) {
                    $actions = [];
                    if ($records->inherentRisk->status == 'new' || empty($records->inherentRisk->status)) {
                        $actions[] = [
                            'type' => 'edit',
                            'label' => 'Tambah',
                            'icon'  => 'fa fa-plus text-primary',
                            'page' => true,
                            'url' => route($this->routes . '.detail', $records->inherentRisk->id),
                        ];
                    } else {
                        if ($records->inherentRisk->checkAction('show', $this->perms)) {
                            $actions[] = [
                                'type' => 'show',
                                'label' => 'Lihat',
                                'page' => true,
                                'url' => route($this->routes . '.show', $records->inherentRisk->id),
                            ];
                        }
                        if ($records->inherentRisk->checkAction('edit', $this->perms)) {
                            $actions[] = [
                                'type' => 'edit',
                                'label' => 'Ubah',
                                'page' => true,
                                'url' => route($this->routes . '.detail', $records->inherentRisk->id),
                            ];
                        }
                        if ($records->inherentRisk->checkAction('approval', $this->perms)) {
                            $actions[] = 'type:approval|page:true';
                        }
                        if ($records->inherentRisk->checkAction('revisi', $this->perms)) {
                            $actions[] = [
                                'icon' => 'fa fa-sync text-warning',
                                'label' => 'Revisi',
                                'url' => route($this->routes . '.revisi', $records->id),
                                'class' => 'base-form--postByUrl',
                                'attrs' => 'data-swal-ok="Revisi" data-swal-text="Revisi akan melalui proses approval terlebih dahulu. Data yang telah di-revisi akan dikembalikan ke status draft untuk dapat diperbarui!"',
                            ];
                        }
                        if ($records->inherentRisk->checkAction('print', $this->perms)) {
                            $actions[] = 'type:print';
                        }
                        if ($records->inherentRisk->checkAction('tracking', $this->perms)) {
                            $actions[] = 'type:tracking';
                        }
                        if ($records->inherentRisk->checkAction('history', $this->perms)) {
                            $actions[] = 'type:history';
                        }
                    }


                    return $this->makeButtonDropdown($actions, $records->inherentRisk->id);
                }
            )
            ->rawColumns(
                [
                    'auditee',
                    'sasaran',
                    'subject_id',
                    'main_process_id',
                    'peristiwa',
                    'periode',
                    'total',
                    'status',
                    'updated_by',
                    'action',
                ]
            )
            ->make(true);
    }

    public function create()
    {
        $record = new InherentRisk;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(InherentRiskRequest $request)
    {
        $record = new InherentRisk;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(InherentRisk $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(InherentRisk $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(InherentRiskRequest $request, InherentRisk $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(InherentRisk $record)
    {
        return $record->handleDestroy();
    }

    public function detail(InherentRisk $record)
    {
        return $this->render($this->views . '.detail.index', compact('record'));
    }

    public function submit(InherentRisk $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(InherentRisk $record, InherentRiskRequest $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(InherentRisk $record)
    {
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function reject(InherentRisk $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(InherentRisk $record, Request $request)
    {
        $result = $record->handleApprove($request);
        if ($record->status == 'completed') {
            $this->print($record);
        }
        return $result;
    }

    public function revisi(InherentRisk $record, Request $request)
    {
        return $record->handleRevisi($request);
    }

    public function history(InherentRisk $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(InherentRisk $record)
    {
        $this->prepare(['title' => 'Tracking Approval']);
        $module = $this->module;
        if ($record->status == 'waiting.approval.revisi') {
            $module = $module . '_upgrade';
        } else {
            $module = $this->module;
        }
        return $this->render('globals.tracking', compact('record'));
    }

    public function print(InherentRisk $record)
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
