<?php

namespace App\Http\Controllers\Master\Risk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\LevelDampakRequest;
use App\Models\Master\Risk\LevelDampak;
use Illuminate\Http\Request;

class LevelDampakController extends Controller
{
    protected $module = 'master.level-dampak';
    protected $routes = 'master.level-dampak';
    protected $views = 'master.level-dampak';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Tingkat Dampak',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Risiko' => rut($this->routes . '.index'),
                'Tingkat Dampak' => rut($this->routes . '.index'),
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
        $records = LevelDampak::grid()->filters()->get();

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

    public function store(LevelDampakRequest $request)
    {
        $record = new LevelDampak();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(LevelDampak $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(LevelDampak $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LevelDampakRequest $request, LevelDampak $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(LevelDampak $record)
    {
        return $record->handleDestroy();
    }
}
