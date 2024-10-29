<?php

namespace App\Http\Controllers\Master\Org;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\LevelPositionRequest;
use App\Models\Auth\User;
use App\Models\Master\Org\LevelPosition;
use Illuminate\Http\Request;

class LevelPositionController extends Controller
{
    protected $module = 'master.org.level-position';
    protected $routes = 'master.org.level-position';
    protected $views = 'master.org.level-position';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Level Jabatan',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Stuktur Organisasi' => rut($this->routes . '.index'),
                'Level Jabatan' => rut($this->routes . '.index'),
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
        $records = LevelPosition::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name ?? null;
            })
            ->addColumn('description', function ($record) {
                return $record->getDescriptionRaw2($record->description);
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
            ->rawColumns(['action', 'updated_by', 'category', 'description', 'name'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(LevelPositionRequest $request)
    {
        $record = new LevelPosition;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(LevelPosition $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(LevelPosition $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LevelPositionRequest $request, LevelPosition $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(LevelPosition $record)
    {
        return $record->handleDestroy();
    }
}
