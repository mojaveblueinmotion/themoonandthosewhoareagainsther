<?php

namespace App\Http\Controllers\Master\Survey;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Survey\SurveyCategoryRequest;
use App\Models\Master\Survey\SurveyCategory;
use Illuminate\Http\Request;

class SurveyCategoryController extends Controller
{
    protected $module = 'master.survey-category';
    protected $routes = 'master.survey-category';
    protected $views = 'master.survey-category';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Kategori Pernyataan',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Survey' => rut($this->routes . '.index'),
                'Kategori Pernyataan' => rut($this->routes . '.index'),
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
        $records = SurveyCategory::grid()->filters()->get();

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

    public function store(SurveyCategoryRequest $request)
    {
        $record = new SurveyCategory;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(SurveyCategory $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(SurveyCategory $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(SurveyCategoryRequest $request, SurveyCategory $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(SurveyCategory $record)
    {
        return $record->handleDestroy();
    }
}
