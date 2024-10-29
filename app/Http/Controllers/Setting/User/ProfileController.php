<?php

namespace App\Http\Controllers\Setting\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\User\UserRequest;
use App\Models\Auth\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $module = 'setting.profile';
    protected $routes = 'setting.profile';
    protected $views = 'setting.profile';

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'title' => 'Profile',
                'breadcrumb' => [
                    'Profile' => rut($this->routes . '.index'),
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
                        $this->makeColumn('name:email|label:Email|className:text-center'),
                        $this->makeColumn('name:nik|label:NIK|className:text-center'),
                        $this->makeColumn('name:position|label:Jabatan|className:text-center'),
                        $this->makeColumn('name:role|label:Role|className:text-center'),
                        $this->makeColumn('name:updated_by|label:#|className:text-center width-10px'),
                        $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );
        return $this->render($this->views . '.index');
    }

    public function getModulesSecondary(Request $request)
    {
        $selectedValue = $request->input('selectedValue');
        $modulesSecondaryData = \Base::getModulesSecondary($selectedValue);

        return response()->json($modulesSecondaryData);
    }

    public function updateProfile(Request $request)
    {
        return auth()->user()->handleUpdateProfile($request);
    }

    public function notification()
    {
        auth()->user()
            ->notifications()
            ->wherePivot('readed_at', null)
            ->newPivotStatement()
            ->where('user_id', auth()->id())
            ->update(array('readed_at' => now()));

        $this->prepare(
            [
                'title' => 'Notifikasi',
                'breadcrumb' => [
                    'Notifikasi'    => rut($this->routes . '.notification'),
                ],
                'tableStruct' => [
                    'url' => rut($this->routes . '.gridNotification'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:module|label:Modul|className:text-left'),
                        $this->makeColumn('name:message|label:Deskripsi|className:text-left'),
                        $this->makeColumn('name:created_by|label:#|width:50px'),
                        // $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );

        return $this->render($this->views . '.notification');
    }

    public function gridNotification()
    {
        $records = auth()->user()
            ->notifications()
            ->latest()
            ->when(
                request()->has('module_name'),
                function ($q) {
                    $module_name = request()->post('module_name');
                    $parts = explode('_', $module_name);
                    $beforeUnderscore = $parts[0];
                    $afterUnderscore = $parts[1] ?? null;

                    $q->where('module', 'LIKE', '%' . $beforeUnderscore . '%');
                }
            )
            ->when(
                $submodule_name = request()->submodule_name,
                function ($q) use ($submodule_name) {
                    $q->where('module', $submodule_name);
                }
            )
            ->get();
        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'module',
                function ($record) {
                    return $record->show_module;
                }
            )
            ->addColumn(
                'message',
                function ($record) {
                    return $record->show_message;
                }
            )
            ->addColumn(
                'created_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) {
                    $buttons = '';
                    $buttons .= $this->makeButton(
                        [
                            'type'  => 'url',
                            'id'    => $record->id,
                            'url'   => $record->link
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'created_by'])
            ->make(true);
    }

    public function activity()
    {
        $this->prepare(
            [
                'title' => 'Aktivitas',
                'breadcrumb' => [
                    'Aktivitas'    => rut($this->routes . '.activity'),
                ],
                'tableStruct' => [
                    'url' => rut($this->routes . '.gridActivity'),
                    'datatable_1' => [
                        $this->makeColumn('name:num'),
                        $this->makeColumn('name:module|label:Modul|className:text-left'),
                        $this->makeColumn('name:message|label:Deskripsi|className:text-left'),
                        $this->makeColumn('name:created_by|label:#|width:50px'),
                        // $this->makeColumn('name:action'),
                    ],
                ],
            ]
        );

        return $this->render($this->views . '.activity');
    }

    public function gridActivity()
    {
        $records = auth()->user()
            ->activities()
            ->grid()
            ->filters()
            ->when(request()->has('module_name'), function ($q) {
                $module_name = request()->post('module_name');
                $parts = explode('_', $module_name);
                $beforeUnderscore = $parts[0];
                $afterUnderscore = $parts[1] ?? null;

                $q->where('module', 'LIKE', '%' . $beforeUnderscore . '%');
                // dd(request()->post('submodule_name'));
                if (request()->post('submodule_name') != null) {
                    $q->when(request()->has('submodule_name'), function ($subq) use ($afterUnderscore) {
                        $submodule_name = request()->post('submodule_name');
                        $parts = explode('_', $submodule_name);
                        $afterUnderscore = $parts[1] ?? $afterUnderscore;

                        $subq->where('module', 'LIKE', '%' . $afterUnderscore . '%');
                    });
                }
            })
            ->when(request()->has('submodule_name'), function ($q) {
                $submodule_name = request()->post('submodule_name');
                $parts = explode('_', $submodule_name);
                $afterUnderscore = $parts[1] ?? null;

                $q->where('module', 'LIKE', '%' . $afterUnderscore . '%');
            })
            ->get();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'module',
                function ($record) {
                    return $record->show_module;
                }
            )
            ->addColumn(
                'message',
                function ($record) {
                    return $record->show_message;
                }
            )
            ->editColumn(
                'created_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->rawColumns(['action', 'created_by'])
            ->make(true);
    }

    public function changePassword()
    {
        $this->prepare(
            [
                'title' => 'Ubah Password',
                'breadcrumb' => [
                    'Ubah Password'    => rut($this->routes . '.changePassword'),
                ],
            ]
        );
        return $this->render($this->views . '.changePassword');
    }

    public function updatePassword(Request $request)
    {
        $password_rules = [
            function ($attribute, $value, $fail) {
                $str = ':attribute harus: ';
                $msg = [];
                if (strlen($value) < 8) {
                    $msg[] = 'min 8 karakter';
                }
                if (!preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
                    $msg[] = 'berisi setidaknya satu huruf besar dan satu huruf kecil';
                }
                if (!preg_match('/\pL/u', $value)) {
                    // $fail('harus berisi setidaknya satu huruf.');
                }
                // if (!preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)) {
                //     $fail('harus mengandung setidaknya satu simbol.');
                // }
                if (!preg_match('/\pN/u', $value)) {
                    $msg[] = 'mengandung setidaknya satu angka';
                }
                if (count($msg)) {
                    $fail($str . '' . implode(', ', $msg));
                }
            },
        ];
        $request->validate(
            [
                'old_password'              => 'required|current_password',
                'new_password'              => [
                    'required',
                    'confirmed',
                    ...$password_rules,
                ],
                'new_password_confirmation' => [
                    'required',
                    ...$password_rules
                ],
            ]
        );

        return auth()->user()
            ->handleUpdatePassword($request);
    }
}
