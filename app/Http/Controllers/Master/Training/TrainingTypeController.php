<?php

namespace App\Http\Controllers\Master\Training;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Training\TrainingTypeRequest;
use App\Models\Master\Training\TrainingType;
use Illuminate\Http\Request;

class TrainingTypeController extends Controller
{
    protected $module = 'master.training.training-type';
    protected $routes = 'master.training.training-type';
    protected $views = 'master.training.training-type';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Jenis Training',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Training' => rut($this->routes . '.index'),
                'Jenis Training' => rut($this->routes . '.index'),
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
        $records = TrainingType::grid()->filters()->get();

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
            ->rawColumns(['action', 'updated_by', 'category', 'description', 'name'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(TrainingTypeRequest $request)
    {
        $record = new TrainingType;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(TrainingType $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(TrainingType $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(TrainingTypeRequest $request, TrainingType $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(TrainingType $record)
    {
        return $record->handleDestroy();
    }
}
