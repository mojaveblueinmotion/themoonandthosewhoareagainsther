<?php

namespace App\Http\Controllers\Master\Procedure;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Procedure\CriteriaRequest;
use App\Models\Master\Procedure\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    //
    protected $module = 'master.criteria';
    protected $routes = 'master.criteria';
    protected $views = 'master.criteria';
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
                'title' => 'Kriteria',
                'breadcrumb' => [
                    'Data Master' => rut($this->routes . '.index'),
                    'Audit' => rut($this->routes . '.index'),
                    'Kriteria' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:description|label:Deskripsi|className:text-left'),
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
        $records = Criteria::grid()->filters()->get();

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
                    return $record->name;
                }
            )
            ->addColumn(
                'description',
                function ($record) {
                    return getDescriptionRaw($record->description);
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
                }
            )
            ->rawColumns(
                [
                    'object',
                    'action',
                    'updated_by',
                    'category',
                    'description',
                    'name'
                ]
            )
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(CriteriaRequest $request)
    {
        $record = new Criteria;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Criteria $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Criteria $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(CriteriaRequest $request, Criteria $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Criteria $record)
    {
        return $record->handleDestroy();
    }
}
