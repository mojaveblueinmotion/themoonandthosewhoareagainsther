<?php

namespace App\Http\Controllers\Master\Org;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\DivisionRequest;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    protected $module = 'master.org.division';
    protected $routes = 'master.org.division';
    protected $views = 'master.org.division';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Divisi',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Stuktur Organisasi' => rut($this->routes . '.index'),
                'Divisi' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:code|label:Kode|className:text-center'),
                    $this->makeColumn('name:name|label:Nama|className:text-left'),
                    $this->makeColumn('name:parent|label:Parent|className:text-center'),
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
        $records = OrgStruct::division()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('code', function ($record) {
                return $record->code ?? null;
            })
            ->addColumn(
                'name',
                function ($record) {
                    $str = '';
                    if ($record->type == 3 || (isset($record->parent->type) && $record->parent->type == 3)) {
                        $str = "<span class='badge badge-primary cursor-pointer' data-toggle='tooltip' style='border-radius: 100%; scale: 0.5' title='Pemilik Aplikasi'><i class='fa fa-check text-white'></i><span>";
                    }
                    return  $record->name . $str;
                }
            )
            ->addColumn('parent', function ($record) {
                return $record->parent->name ?? '';
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
            ->rawColumns(['action', 'updated_by', 'parent', 'name', 'code'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(DivisionRequest $request)
    {
        $record = new OrgStruct;
        return $record->handleStoreOrUpdate($request, 'division');
    }

    public function show(OrgStruct $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(OrgStruct $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(DivisionRequest $request, OrgStruct $record)
    {
        return $record->handleStoreOrUpdate($request, 'division');
    }

    public function destroy(OrgStruct $record)
    {
        return $record->handleDestroy();
    }
}
