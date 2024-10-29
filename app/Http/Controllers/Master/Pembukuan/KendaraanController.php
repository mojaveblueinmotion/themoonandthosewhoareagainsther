<?php

namespace App\Http\Controllers\Master\Pembukuan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Pembukuan\KendaraanRequest;
use App\Models\Master\Pembukuan\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    protected $module = 'master.kendaraan';
    protected $routes = 'master.kendaraan';
    protected $views = 'master.kendaraan';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Kendaraan',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Kendaraan' => rut($this->routes . '.index'),
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
                    $this->makeColumn('name:no_kendaraan|label:Plat Nomor|className:text-center'),
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
        $records = Kendaraan::grid()->filters()->get();

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

    public function store(KendaraanRequest $request)
    {
        $record = new Kendaraan();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Kendaraan $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Kendaraan $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(KendaraanRequest $request, Kendaraan $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Kendaraan $record)
    {
        return $record->handleDestroy();
    }
}
