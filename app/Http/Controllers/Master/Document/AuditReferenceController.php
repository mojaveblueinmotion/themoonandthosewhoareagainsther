<?php

namespace App\Http\Controllers\Master\Document;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Document\AuditReferenceRequest;
use App\Models\Master\Document\AuditReference;
use Illuminate\Http\Request;

class AuditReferenceController extends Controller
{
    protected $module = 'master.audit-reference';
    protected $routes = 'master.audit-reference';
    protected $views = 'master.audit-reference';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Kriteria / Standard',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Kriteria / Standard' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:name|label:Nama|className:text-left'),
                    $this->makeColumn('name:category|label:Jenis Audit|className:text-center'),
                    $this->makeColumn('name:object|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:aspect|label:Lingkup Audit|className:text-center'),
                    $this->makeColumn('name:tujuan|label:Tujuan Audit|className:text-center'),
                    $this->makeColumn('name:langkah|label:Langkah Audit|className:text-center'),
                    $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = AuditReference::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return  $record->name ?? null;
            })
            ->addColumn('tujuan', function ($record) {
                return $record->procedure->objective->name;
            })
            ->addColumn('langkah', function ($record) {
                return $record->procedure->number .'. '. $record->procedure->procedure;
            })
            ->addColumn('aspect', function ($record) {
                $aspect = $record->aspect ? $record->aspect->name : '';
                return $aspect;
            })
            ->addColumn('category', function ($record) {
                return $record->aspect ? $record->aspect->subject->typeAudit->name : '';
            })
            ->addColumn('object', function ($record) {
                return $record->aspect->subject->name;
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
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
                    'category', 'description', 'aspect', 'name'
                ]
            )
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(AuditReferenceRequest $request)
    {
        $record = new AuditReference;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(AuditReference $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(AuditReference $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(AuditReferenceRequest $request, AuditReference $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(AuditReference $record)
    {
        return $record->handleDestroy();
    }
}
