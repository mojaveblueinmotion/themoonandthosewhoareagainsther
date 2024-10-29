<?php

namespace App\Http\Controllers\Master\Aspect;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Aspect\AspectRequest;
use App\Models\Master\Aspect\Aspect;
use Illuminate\Http\Request;

class AspectController extends Controller
{
    protected $module = 'master.aspect';
    protected $routes = 'master.aspect';
    protected $views = 'master.aspect';
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
                'title' => 'Lingkup Audit',
                'breadcrumb' => [
                    'Data Master' => rut($this->routes . '.index'),
                    'Audit' => rut($this->routes . '.index'),
                    'Lingkup Audit' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:name|label:Nama|className:text-left'),
                        $this->makeColumn('name:type_id|label:Jenis Audit|className:text-center'),
                        $this->makeColumn('name:subject|label:Subjek Audit|className:text-center'),
                        $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
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
        $records = Aspect::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'name',
                function ($record) {
                    return  $record->name ?? null;
                }
            )
            ->addColumn(
                'type_id',
                function ($record) {
                    return $record->subject->typeAudit->name;
                }
            )
            ->addColumn(
                'subject',
                function ($record) {
                    return $record->subject->name;
                }
            )
            ->addColumn(
                'main_process_id',
                function ($record) {
                    return $record->mainProcess->name;
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
            ->rawColumns(['action', 'updated_by', 'type_id', 'subject', 'name', 'main_process_id'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(AspectRequest $request)
    {
        $record = new Aspect;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Aspect $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Aspect $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(AspectRequest $request, Aspect $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Aspect $record)
    {
        return $record->handleDestroy();
    }
}
