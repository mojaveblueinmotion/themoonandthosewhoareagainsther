<?php

namespace App\Http\Controllers\Master\Risk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\LevelKemungkinanRequest;
use App\Models\Master\Risk\LevelKemungkinan;
use Illuminate\Http\Request;

class LevelKemungkinanController extends Controller
{
    protected $module = 'master.level-kemungkinan';
    protected $routes = 'master.level-kemungkinan';
    protected $views = 'master.level-kemungkinan';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Tingkat Kemungkinan',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Risiko' => rut($this->routes . '.index'),
                'Tingkat Kemungkinan' => rut($this->routes . '.index'),
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
        $records = LevelKemungkinan::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name ?? null;
            })
            ->addColumn('description', function ($record) {
                return $record->getDescriptionRaw($record->description);
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show',
                    'type:edit',
                ];
                if ($record->canDeleted()) {
                    $actions[] = [
                        'type' => 'delete',
                        'text' => $record->name,
                    ];
                }
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['action', 'updated_by', 'name', 'description'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(LevelKemungkinanRequest $request)
    {
        $record = new LevelKemungkinan();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(LevelKemungkinan $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(LevelKemungkinan $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LevelKemungkinanRequest $request, LevelKemungkinan $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(LevelKemungkinan $record)
    {
        return $record->handleDestroy();
    }
}
