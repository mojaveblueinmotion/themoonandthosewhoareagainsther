<?php

namespace App\Http\Controllers\Master\Org;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\PositionRequest;
use App\Models\Master\Org\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected $module = 'master.org.position';
    protected $routes = 'master.org.position';
    protected $views = 'master.org.position';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Jabatan',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Stuktur Organisasi' => rut($this->routes . '.index'),
                'Jabatan' => rut($this->routes . '.index'),
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
                    $this->makeColumn('name:location|label:Struktur|className:text-center'),
                    $this->makeColumn('name:level|label:Level|className:text-center'),
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
        $records = Position::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name ?? null;
            })
            ->addColumn('location', function ($record) {
                return $record->location->name;
            })
            ->addColumn('level', function ($record) {
                if ($record->level) {
                    return $record->level->name ?? null;
                }
                return '-';
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
            ->rawColumns(['action', 'updated_by', 'location', 'name', 'level'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(PositionRequest $request)
    {
        $record = new Position;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Position $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Position $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(PositionRequest $request, Position $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Position $record)
    {
        return $record->handleDestroy();
    }
}
