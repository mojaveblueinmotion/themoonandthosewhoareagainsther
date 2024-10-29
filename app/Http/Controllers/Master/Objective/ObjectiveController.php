<?php

namespace App\Http\Controllers\Master\Objective;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ObjectiveRequest;
use App\Models\Master\Objective\Objective\Objective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    protected $module = 'master.objective';
    protected $routes = 'master.objective';
    protected $views = 'master.objective';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Tujuan Audit',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Tujuan Audit' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:name|label:Tujuan Audit|className:text-center'),
                    $this->makeColumn('name:description|label:Deskripsi|className:text-center'),
                    $this->makeColumn('name:type_id|label:Jenis Audit|className:text-center'),
                    $this->makeColumn('name:subject|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:aspect|label:Lingkup Audit|className:text-center'),
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
        $records = Objective::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('type_id', function ($record) {
                return $record->aspect->subject->typeAudit->name;
            })
            ->addColumn('subject', function ($record) {
                return $record->aspect->subject->name;
            })
            ->addColumn('name', function ($record) {
                return $record->name;
            })
            ->addColumn('aspect', function ($record) {
                return $record->aspect->name;
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
            ->rawColumns(['action', 'updated_by', 'name','aspect', 'description'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(ObjectiveRequest $request)
    {
        $record = new Objective();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Objective $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Objective $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(ObjectiveRequest $request, Objective $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Objective $record)
    {
        return $record->handleDestroy();
    }
}
