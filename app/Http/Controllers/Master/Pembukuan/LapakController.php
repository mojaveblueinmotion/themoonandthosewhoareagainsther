<?php

namespace App\Http\Controllers\Master\Pembukuan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Pembukuan\LapakRequest;
use App\Models\Master\Pembukuan\Lapak;
use Illuminate\Http\Request;

class LapakController extends Controller
{
    protected $module = 'master.lapak';
    protected $routes = 'master.lapak';
    protected $views = 'master.lapak';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Lapak',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Lapak' => rut($this->routes . '.index'),
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
        $records = Lapak::grid()->filters()->get();

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

    public function store(LapakRequest $request)
    {
        $record = new Lapak();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Lapak $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Lapak $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LapakRequest $request, Lapak $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Lapak $record)
    {
        return $record->handleDestroy();
    }
}
