<?php

namespace App\Http\Controllers\Setting\User;

use App\Exports\Setting\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\User\UserServiceProviderRequest;
use App\Models\Auth\User;
use Illuminate\Http\Request;

class UserServiceProviderController extends Controller
{
    protected $module = 'setting.user-service-provider';
    protected $routes = 'setting.user-service-provider';
    protected $views = 'setting.user-service-provider';
    protected $perms = 'setting';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'User Penyedia Jasa',
                'breadcrumb' => [
                    'Pengaturan Umum' => rut($this->routes . '.index'),
                    'User Penyedia Jasa' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:name|label:Nama|className:text-center'),
                        $this->makeColumn('name:username|label:Username|className:text-center'),
                        // $this->makeColumn('name:email|label:Email|className:text-center'),
                        // $this->makeColumn('name:nik|label:NIK|className:text-center'),
                        $this->makeColumn('name:provider|label:Penyedia Jasa|className:text-center'),
                        $this->makeColumn('name:jabatan_provider|label:Jabatan|className:text-center width-10px'),
                        $this->makeColumn('name:status|className:text-center width-10px'),
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
        $records = User::grid()
            ->filters()
            ->where('type', 'provider')
            ->orderBy('updated_at', 'DESC')
            ->dtGet();

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
                'username',
                function ($record) {
                    return $record->username;
                }
            )
            ->addColumn(
                'provider',
                function ($record) {
                    return $record->provider->name;
                }
            )
            ->addColumn(
                'jabatan_provider',
                function ($record) {
                    return $record->jabatan_provider;
                }
            )
            ->addColumn(
                'role',
                function ($record) {
                    if ($record->roles()->exists()) {
                        $x = '';
                        foreach ($record->roles as $role) {
                            $x .= $role->name;
                            $x .= '<br>';
                        }
                        return $x;
                    }
                    return '-';
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    return $record->labelStatus();
                }
            )
            ->editColumn(
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
                        'attrs' => 'data-modal-size="modal-lg"',
                        'id' => $record->id
                    ];
                    $actions[] = [
                        'type' => 'edit',
                        'attrs' => 'data-modal-size="modal-lg"',
                        'id' => $record->id,
                    ];

                    if ($user->id == 1) {
                        $actions[] = [
                            'label' => 'Reset Password',
                            'icon' => 'fa fa-retweet text-warning',
                            'class' => 'base-form--postByUrl',
                            'attrs' => 'data-swal-text="Reset password akan mengubah password menjadi: qwerty123456"',
                            'id' => $record->id,
                            'url' => rut($this->routes . '.resetPassword', $record->id)
                        ];
                    }

                    $actions[] = [
                        'type' => 'delete',
                        'id' => $record->id,
                        'attrs' => 'data-confirm-text="' . __('Hapus') . ' User ' . $record->name . '?"',
                    ];
                    return $this->makeButtonDropdown($actions);
                }
            )
            ->rawColumns(['action', 'email', 'updated_by', 'status', 'position', 'role', 'username', 'name'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(UserServiceProviderRequest $request)
    {
        $record = new User;
        return $record->handleStoreOrUpdate($request, 'provider');
    }

    public function show(User $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(User $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(UserServiceProviderRequest $request, User $record)
    {
        return $record->handleStoreOrUpdate($request, 'provider');
    }

    public function destroy(User $record)
    {
        return $record->handleDestroy();
    }

    public function resetPassword(User $record)
    {
        return $record->handleResetPassword();
    }

    public function import()
    {
        if (request()->get('download') == 'template') {
            return $this->template();
        }
        return $this->render($this->views . '.import');
    }

    public function template()
    {
        $fileName = date('Y-m-d') . ' Template Import Data ' . $this->prepared('title') . '.xlsx';
        return \Excel::download(new UserTemplateExport, $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate(
            [
                'uploads.uploaded' => 'required'
            ],
            [],
            [
                'uploads.uploaded' => 'Lampiran'
            ]
        );

        $record = new User;
        return $record->handleImport($request);
    }
}
