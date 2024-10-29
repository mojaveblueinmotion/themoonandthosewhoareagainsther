<?php

namespace App\Http\Controllers\Master\Risk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\RiskStatusRequest;
use App\Models\Master\Risk\RiskStatus;
use Illuminate\Http\Request;

class RiskStatusController extends Controller
{
    protected $module = 'master.risk-status';
    protected $routes = 'master.risk-status';
    protected $views = 'master.risk-status';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Status Risiko',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Risiko' => rut($this->routes . '.index'),
                'Status Risiko' => rut($this->routes . '.index'),
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
        $records = RiskStatus::grid()->filters()->get();

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

    public function store(RiskStatusRequest $request)
    {
        $record = new RiskStatus();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(RiskStatus $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(RiskStatus $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(RiskStatusRequest $request, RiskStatus $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(RiskStatus $record)
    {
        return $record->handleDestroy();
    }
}
