<?php

namespace App\Http\Controllers\Master\Geografis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Geografis\ProvinceRequest;
use App\Models\Master\Geografis\Province;
use App\Support\Base;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    protected $module = 'master.province';
    protected $routes = 'master.geografis.province';
    protected $views = 'master.geografis.provinsi';
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
                'title' => 'Provinsi',
                'breadcrumb' => [
                    'Data Master' => rut($this->routes . '.index'),
                    'Geografis' => rut($this->routes . '.index'),
                    'Provinsi' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:code|label:Kode|className:text-center'),
                        $this->makeColumn('name:name|label:Provinsi|className:text-center'),
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
        $records = Province::filters()->get();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->editColumn(
                'code',
                function ($record) {
                    return $record->code;
                }
            )
            ->addColumn(
                'name',
                function ($record) {
                    return $record->name;
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) use ($user) {
                    return $record->createdByRaw();
                }
            )
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
            ->rawColumns(
                [
                    'code', 'name',
                    'action', 'updated_by'
                ]
            )
            ->make(true);
    }
    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(ProvinceRequest $request)
    {
        $record = new Province;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Province $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Province $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(ProvinceRequest $request, Province $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Province $record)
    {
        return $record->handleDestroy();
    }
}
