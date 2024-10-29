<?php

namespace App\Http\Controllers\RiskAssessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiskAssessment\RiskRegisterDetailRequest;
use App\Http\Requests\RiskAssessment\RiskRegisterRequest;
use App\Models\RiskAssessment\RiskRegister;
use App\Models\RiskAssessment\RiskRegisterDetail;
use App\Support\Base;
use Illuminate\Http\Request;

class RiskRegisterController extends Controller
{
    protected $module = 'risk-assessment.risk-register';
    protected $routes = 'risk-assessment.risk-register';
    protected $views = 'risk-assessment.risk-register';
    protected $perms = 'risk-assessment.risk-register';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Risk Register',
            'breadcrumb' => [
                'Risk Assessment' => route($this->routes . '.index'),
                'Risk Register' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:type_id|label:Jenis Audit|className:text-center'),
                    $this->makeColumn('name:unit_kerja_id|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                    $this->makeColumn('name:jumlah_risk|label:Jumlah Risk|className:text-center'),
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
        $records = RiskRegister::grid()->filters()
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
            ->when(request()->get('status') !== '*', function ($q) {
                $q->filterBy(['status', '=']);
            })
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'type_id',
                function ($record) use ($user) {
                    return $record->type->show_name ?? '';
                }
            )
            ->addColumn(
                'unit_kerja_id',
                function ($record) use ($user) {
                    return $record->subject->name;
                }
            )
            ->addColumn('auditee', function ($record) {
                return $record->departmentAuditee->getDepartments();
            })
            ->addColumn(
                'periode',
                function ($record) use ($user) {
                    return $record->periode->format('Y');
                }
            )
            ->addColumn(
                'jumlah_risk',
                function ($record) use ($user) {
                    return $record->details()->count();
                }
            )
            ->addColumn('status', function ($record) use ($user) {
                return $record->labelStatus();
            })
            ->addColumn('updated_by', function ($record) use ($user) {
                return $record->createdByRaw();
            })
            ->addColumn(
                'version',
                function ($record) use ($user) {
                    if ($record) {
                        return $record->version;
                    }
                    return "0";
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'page' => true,
                        'url' => route($this->routes . '.show', $record->id),
                    ];
                }
                if ($record->checkAction('edit', $this->perms) && !in_array($record->status, ['waiting.approval', 'completed'])) {
                    $actions[] = [
                        'type' => 'create',
                        'label' => 'Detail',
                        'page' => true,
                        'url' => route($this->routes . '.detail', $record->id),
                    ];
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit';
                }
                if ($record->checkAction('approval', $this->perms)) {
                    $actions[] = [
                        'type' => 'approval',
                        'page' => true,
                    ];
                }
                if ($record->checkAction('revisi', $this->perms)) {
                    $actions[] = [
                        'icon' => 'fa fa-sync text-warning',
                        'label' => 'Revisi',
                        'url' => route($this->routes . '.revisi', $record->id),
                        'class' => 'base-form--postByUrl',
                        'attrs' => 'data-swal-ok="Revisi" data-swal-text="Revisi akan melalui proses approval terlebih dahulu. Data yang telah di-revisi akan dikembalikan ke status draft untuk dapat diperbarui!"',
                    ];
                }
                if ($record->checkAction('print', $this->perms)) {
                    $actions[] = 'type:print';
                }
                if ($record->checkAction('tracking', $this->perms)) {
                    $actions[] = 'type:tracking';
                }
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = 'type:delete';
                }
                if ($record->checkAction('history', $this->perms)) {
                    $actions[] = 'type:history';
                }

                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['type_id', 'sasaran', 'unit_kerja_id', 'periode', 'auditee', 'action', 'updated_by', 'status'])
            ->make(true);
    }

    public function create()
    {
        $record = new RiskRegister;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(RiskRegisterRequest $request)
    {
        $record = new RiskRegister;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(RiskRegister $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
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
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(RiskRegister $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(RiskRegisterRequest $request, RiskRegister $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(RiskRegister $record)
    {
        return $record->handleDestroy();
    }

    public function detail(RiskRegister $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:objective|label:Objective|className:text-center'),
                    $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                    $this->makeColumn('name:penyebab|label:Risk Cause|className:text-center'),
                    $this->makeColumn('name:dampak|label:Risk Impact|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);

        // dd('controller', $record->getTable(), $record->type->name, $record->subject->name);
        return $this->render($this->views . '.detail.index', compact('record'));
    }

    public function detailGrid(RiskRegister $record)
    {
        $user = auth()->user();
        $details = RiskRegisterDetail::grid()
            ->whereHas(
                'riskRegister',
                function ($q) use ($record) {
                    $q->where('id', $record->id);
                }
            )
            ->filters()
            ->dtGet();

        return \DataTables::of($details)
            ->addColumn('num', function ($detail) {
                return request()->start;
            })
            ->addColumn('main_process_id', function ($detail) {
                return $detail->kodeResiko->name;
            })
            ->addColumn('sub_process_id', function ($detail) {
                return $detail->id_resiko . '<br>' . $detail->jenisResiko->name;
            })
            ->addColumn('objective', function ($detail) {
                return $detail->getObjectiveRaw();
            })
            ->addColumn('peristiwa', function ($detail) {
                return $detail->getPeristiwaRaw();
            })
            ->addColumn('penyebab', function ($detail) {
                return $detail->getPenyebabRaw();
            })
            ->addColumn('dampak', function ($detail) {
                return $detail->getDampakRaw();
            })
            ->addColumn('updated_by', function ($detail) {
                return $detail->createdByRaw();
            })
            ->addColumn('action', function ($detail) {
                $actions = [];
                if ($detail->riskRegister->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'url' => route($this->routes . '.detailShow', $detail->id),

                    ];
                }
                if ($detail->riskRegister->checkAction('detailEdit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'url' => route($this->routes . '.detailEdit', $detail->id),

                    ];
                }
                if ($detail->riskRegister->checkAction('detailDelete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'url' => route($this->routes . '.detailDestroy', $detail->id),
                    ];
                }

                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->addColumn('action_show', function ($detail) use ($user) {
                $actions = [];
                if ($detail->riskRegister->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'url' => route($this->routes . '.detailShow', $detail->id),
                    ];
                }
                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->rawColumns([
                'objective',
                'peristiwa',
                'sub_process_id', 'status',
                'main_process_id', 'dampak',
                'penyebab',
                'updated_by',
                'action',
                'action_show',
            ])
            ->make(true);
    }

    public function detailCreate(RiskRegister $record)
    {
        $this->prepare(
            [
                'title' => 'Detail Risk Register'
            ]
        );

        return $this->render($this->views . '.detail.create', compact('record'));
    }

    public function detailStore(RiskRegisterDetailRequest $request, RiskRegisterDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailShow(RiskRegisterDetail $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Risk Register'
            ]
        );
        // $record = $detail->riskRegister;
        return $this->render($this->views . '.detail.show', compact('detail'));
    }

    public function detailEdit(RiskRegisterDetail $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Risk Register'
            ]
        );
        return $this->render($this->views . '.detail.edit', compact('detail'));
    }

    public function detailUpdate(RiskRegisterDetailRequest $request, RiskRegisterDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailDestroy(RiskRegisterDetail $detail)
    {
        return $detail->handleDetailDestroy($detail);
    }

    public function submit(RiskRegister $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(RiskRegister $record, Request $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(RiskRegister $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
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
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function reject(RiskRegister $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(RiskRegister $record, Request $request)
    {
        $result = $record->handleApprove($request);
        if ($record->status == 'completed') {
            $this->print($record);
        }
        return $result;
    }

    public function revisi(RiskRegister $record, Request $request)
    {
        return $record->handleRevisi($request);
    }

    public function history(RiskRegister $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(RiskRegister $record)
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
