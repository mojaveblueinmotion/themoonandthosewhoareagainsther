<?php

namespace App\Http\Controllers\Master\Risk;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\RiskRatingRequest;
use App\Models\Master\Risk\RiskRating;
use Illuminate\Http\Request;

class RiskRatingController extends Controller
{
    protected $module = 'master.risk-rating';
    protected $routes = 'master.risk-rating';
    protected $views = 'master.risk-rating';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Tingkat Risiko',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Risiko' => rut($this->routes . '.index'),
                'Tingkat Risiko' => rut($this->routes . '.index'),
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
        $records = RiskRating::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name;
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
            ->rawColumns(['name', 'action', 'updated_by', 'category', 'description'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(RiskRatingRequest $request)
    {
        $record = new RiskRating;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(RiskRating $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(RiskRating $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(RiskRatingRequest $request, RiskRating $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(RiskRating $record)
    {
        return $record->handleDestroy();
    }
}
