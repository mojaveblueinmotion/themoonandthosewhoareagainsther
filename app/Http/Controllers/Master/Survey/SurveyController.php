<?php

namespace App\Http\Controllers\Master\Survey;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Survey\SurveyRequest;
use App\Http\Requests\Master\Survey\SurveyStatementRequest;
use App\Models\Master\Survey\Survey;
use App\Models\Master\Survey\SurveyCategory;
use App\Models\Master\Survey\SurveyStatement;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    protected $module = 'master.survey';
    protected $routes = 'master.survey';
    protected $views = 'master.survey';
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
                'title' => 'Pernyataan Survey',
                'breadcrumb' => [
                    'Data Master' => rut($this->routes . '.index'),
                    'Survey' => rut($this->routes . '.index'),
                    'Pernyataan Survey' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:version|label:Versi|width:80px'),
                        $this->makeColumn('name:description|label:Deskripsi|className:text-left'),
                        $this->makeColumn('name:statements|label:Jumlah Pernyataan|className:text-center|width:200px'),
                        $this->makeColumn('name:status'),
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
        $records = Survey::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'version',
                function ($record) {
                    return $record->version ?? "0";
                }
            )
            ->addColumn(
                'description',
                function ($record) {
                    return read_more($record->description);
                }
            )
            ->addColumn(
                'statements',
                function ($record) {
                    return $record->statements->count() . ' ' . __('Pernyataan');
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->labelStatus();
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    $actions = [];


                    $actions[] = [
                        'type' => 'show',
                        'page' => true,
                        'url' => rut($this->routes . '.statement', $record->id),
                    ];
                    $actions[] = [
                        'type' => 'edit',
                    ];
                    if (in_array($record->status, ['new', 'draft', 'nonactive'])) {
                        $actions[] = [
                            'type' => 'show',
                            'label' => 'Detail',
                            'icon' => 'fa fa-plus text-primary',
                            'page' => true,
                            'url' => rut($this->routes . '.statement', $record->id),
                        ];
                    }

                    if (in_array($record->status, ['active', 'nonactive'])) {
                        $actions[] = [
                            'label' => 'Clone',
                            'icon' => 'fa fa-clone text-warning',
                            'class' => 'base-form--postByUrl',
                            'url' => rut($this->routes . '.clone', $record->id),
                        ];
                    }

                    if ($record->canDeleted()) {
                        $actions[] = [
                            'type' => 'delete',
                            'text' => 'Versi ' . $record->version,
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $record->id);
                }
            )
            ->rawColumns(['action', 'updated_by', 'status', 'version', 'description', 'statements'])
            ->make(true);
    }

    public function create()
    {
        $record = new Survey;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(SurveyRequest $request)
    {
        $record = new Survey;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Survey $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Survey $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(SurveyRequest $request, Survey $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Survey $record)
    {
        return $record->handleDestroy();
    }

    public function statement(Survey $record)
    {
        $this->prepare(
            [
                'title' => 'Pernyataan Survey',
                'tableStruct' => [
                    'url' => rut($this->routes . '.statementGrid', $record->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:category|label:Kategori|className:text-left'),
                        $this->makeColumn('name:statement|label:Pernyataan|className:text-left'),
                        $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );

        return $this->render($this->views . '.statement.index', compact('record'));
    }

    public function statementGrid(Survey $record)
    {
        $user = auth()->user();
        $records = SurveyStatement::where('survey_id', $record->id)->filters()->get();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($statement) {
                    return request()->start;
                }
            )
            ->addColumn(
                'statement',
                function ($statement) {
                    return $statement->statement;
                }
            )
            ->addColumn(
                'category',
                function ($statement) {
                    return $statement->kategori->name;
                }
            )
            ->addColumn(
                'action',
                function ($statement) use ($user) {
                    $actions = [];
                    $actions[] = [
                        'type' => 'show',
                        'url' => rut($this->routes . '.statementShow', $statement->id),
                    ];

                    if (in_array($statement->survey->status, ['new', 'draft'])) {
                        $actions[] = [
                            'type' => 'edit',
                            'url' => rut($this->routes . '.statementEdit', $statement->id),
                        ];
                        $actions[] = [
                            'type' => 'delete',
                            'url' => rut($this->routes . '.statementDestroy', $statement->id),
                            'text' => 'pernyataan ini',
                        ];
                    }
                    return $this->makeButtonDropdown($actions, $statement->id);
                }
            )
            ->rawColumns(['action', 'updated_by', 'version', 'statement'])
            ->make(true);
    }

    public function statementCreate(Survey $record)
    {
        $this->prepare(['title' => 'Pernyataan Survey']);
        $category = SurveyCategory::get();
        return $this->render($this->views . '.statement.create', compact('record', 'category'));
    }

    public function statementStore(Survey $record, SurveyStatementRequest $request)
    {
        $statement = new SurveyStatement;
        return $record->handleStatementStoreOrUpdate($request, $statement);
    }

    public function statementShow(SurveyStatement $statement)
    {
        $this->prepare(['title' => 'Pernyataan Survey']);
        $record = $statement->survey;
        return $this->render($this->views . '.statement.show', compact('record', 'statement'));
    }

    public function statementEdit(SurveyStatement $statement)
    {
        $this->prepare(['title' => 'Pernyataan Survey']);
        $record = $statement->survey;
        $category = SurveyCategory::get();
        return $this->render($this->views . '.statement.edit', compact('record', 'statement', 'category'));
    }

    public function statementUpdate(SurveyStatement $statement, SurveyStatementRequest $request)
    {
        $record = $statement->survey;
        return $record->handleStatementStoreOrUpdate($request, $statement);
    }

    public function statementDestroy(SurveyStatement $statement)
    {
        $record = $statement->survey;
        return $record->handleStatementDestroy($statement);
    }

    public function activate(Survey $record)
    {
        return $record->handleActivate();
    }

    public function clone(Survey $record)
    {
        return $record->handleClone();
    }
}
