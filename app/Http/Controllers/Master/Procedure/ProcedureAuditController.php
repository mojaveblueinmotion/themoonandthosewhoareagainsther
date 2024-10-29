<?php

namespace App\Http\Controllers\Master\Procedure;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Procedure\ProcedureAuditRequest;
use App\Models\Master\Procedure\ProcedureAudit;
use Illuminate\Http\Request;

class ProcedureAuditController extends Controller
{
    //
    protected $module = 'master.procedure';
    protected $routes = 'master.procedure';
    protected $views = 'master.procedure';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Langkah Kerja',
                'breadcrumb' => [
                    'Data Master' => rut($this->routes . '.index'),
                    'Audit' => rut($this->routes . '.index'),
                    'Langkah Kerja' => rut($this->routes . '.index'),
                ]
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
                        $this->makeColumn('name:procedure|label:Nama|className:text-left'),
                        $this->makeColumn('name:number|label:No Urut|className:text-center'),
                        $this->makeColumn('name:mandays|label:Mandays|className:text-center'),
                        // $this->makeColumn('name:description|label:Deskripsi|className:text-left'),
                        $this->makeColumn('name:category|label:Jenis Audit|className:text-center'),
                        $this->makeColumn('name:object|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:aspect|label:Lingkup Audit|className:text-center'),
                        $this->makeColumn('name:objective|label:Tujuan Audit|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center'),
                        $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = ProcedureAudit::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'procedure',
                function ($record) {
                    return $record->procedure;
                }
            )
            ->addColumn(
                'number',
                function ($record) {
                    return $record->number ?? '';
                }
            )
            ->addColumn(
                'aspect',
                function ($record) {
                    return $record->aspect->name;
                }
            )
            ->addColumn(
                'objective',
                function ($record) {
                    return $record->objective->name;
                }
            )
            ->addColumn(
                'criteria',
                function ($record) {
                    $criteria = $record->criteria->name ?? null;
                    return $criteria;
                }
            )
            ->addColumn(
                'category',
                function ($record) {
                    return $record->aspect ? $record->aspect->subject->typeAudit->name : '';
                }
            )
            ->addColumn(
                'object',
                function ($record) {
                    return $record->aspect ? $record->aspect->subject->name : '';
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show|id:' . $record->id,
                ];
                if ($record->checkAction($user, 'edit', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id;
                }
                if ($record->checkAction($user, 'delete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus') . ' ' . $record->name . '?"',
                    ];
                }
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(
                [
                    'object',
                    'action',
                    'updated_by',
                    'category',
                    'description',
                    'aspect',
                    'procedure'
                ]
            )
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(ProcedureAuditRequest $request)
    {
        $record = new ProcedureAudit;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(ProcedureAudit $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(ProcedureAudit $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(ProcedureAuditRequest $request, ProcedureAudit $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(ProcedureAudit $record)
    {
        return $record->handleDestroy();
    }
}
