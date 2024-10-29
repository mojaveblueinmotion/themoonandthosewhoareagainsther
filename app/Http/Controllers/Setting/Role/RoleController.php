<?php

namespace App\Http\Controllers\Setting\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Role\RoleRequest;
use App\Models\Auth\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $module = 'setting.role';
    protected $routes = 'setting.role';
    protected $views = 'setting.role';
    protected $perms = 'setting';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Hak Akses',
            'breadcrumb' => [
                'Pengaturan Umum' => rut($this->routes . '.index'),
                'Hak Akses' => rut($this->routes . '.index'),
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
                    $this->makeColumn('name:users|label:Jumlah User|className:text-center|width:200px'),
                    $this->makeColumn('name:permissions|label:Jumlah Akses|className:text-center|width:200px'),
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
        $records = Role::grid()
            ->filters()
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name;
            })
            ->addColumn('users', function ($record) {
                return $record->getUsersRaw();
            })
            ->addColumn('permissions', function ($record) {
                return $record->permissions()->count() . ' Permission';
            })
            ->editColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction($user, 'edit', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id;
                }
                if ($record->checkAction($user, 'create', $this->perms)) {
                    $actions[] = [
                        'page' => true,
                        'icon' => 'fa fa-check text-primary',
                        'label' => 'Assign Permission',
                        'url' => rut($this->routes . '.permit', $record->id)
                    ];
                }
                if ($record->checkAction($user, 'delete', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'id' => $record->id,
                            'attrs' => 'data-confirm-text="' . __('Hapus') . ' Role ' . $record->name . '?"',
                        ];
                 }
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(
                [
                    'name', 'users', 'permissions', 'action', 'updated_by'
                ]
            )
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(RoleRequest $request)
    {
        $record = new Role;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(Role $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(RoleRequest $request, Role $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Role $record)
    {
        return $record->handleDestroy();
    }

    public function permit(Role $record)
    {
        $this->prepare(
            [
                'breadcrumb' => [
                    'Pengaturan Umum' => rut($this->routes . '.index'),
                    'Hak Akses' => rut($this->routes . '.index'),
                    '<span class="ml-3 label label-lg label-inline label-danger text-nowrap text-bold">' . $record->name . '</span>' => rut($this->routes . '.permit', $record->id),
                ],
            ]
        );
        return $this->render($this->views . '.permit', compact('record'));
    }

    public function grant(Role $record, Request $request)
    {
        return $record->handleGrant($request);
    }
}
