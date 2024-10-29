<?php

namespace App\Http\Controllers\Master\Risk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\MainProcessRequest;
use App\Models\Master\Risk\MainProcess;
use Illuminate\Http\Request;

class MainProcessController extends Controller
{
    protected $module = 'master.main-process';
    protected $routes = 'master.main-process';
    protected $views = 'master.main-process';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Main Process',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Risiko' => rut($this->routes . '.index'),
                'Main Process' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:name|label:Nama|className:text-center'),
                    $this->makeColumn('name:type|label:Jenis Audit|className:text-center'),
                    $this->makeColumn('name:subject|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:description|label:Deskripsi|className:text-center'),
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
        $records = MainProcess::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name;
            })
            ->addColumn('type', function ($record) {
                return $record->subject->typeAudit->name;
            })
            ->addColumn('subject', function ($record) {
                return $record->subject->name;
            })
            ->addColumn('description', function ($record) {
                return $record->getDescriptionRaw($record->description);
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
            ->rawColumns(['action', 'updated_by', 'name', 'description'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(MainProcessRequest $request)
    {
        $record = new MainProcess();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(MainProcess $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(MainProcess $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(MainProcessRequest $request, MainProcess $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(MainProcess $record)
    {
        return $record->handleDestroy();
    }
}
