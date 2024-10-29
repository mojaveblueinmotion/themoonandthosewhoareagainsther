<?php

namespace App\Http\Controllers\Master\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Org\DepartmentAuditeeRequest;
use App\Models\Master\Org\DepartmentAuditee;
use Illuminate\Http\Request;

class DepartmentAuditeeController extends Controller
{
    protected $module = 'master.org.department-auditee';
    protected $routes = 'master.org.department-auditee';
    protected $views = 'master.org.department-auditee';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Department Auditee',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Department Auditee' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:year|label:Tahun|className:text-center'),
                    $this->makeColumn('name:type_id|label:Jenis Audit|className:text-center'),
                    $this->makeColumn('name:subject|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:departments|label:Department|className:text-center'),
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
        $records = DepartmentAuditee::grid()
            ->filters()
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('year', function ($record) {
                return $record->year;
            })
            ->addColumn('type_id', function ($record) {
                return $record->type->name;
            })
            ->addColumn('subject', function ($record) {
                return $record->subject->name;
            })
            ->addColumn('departments', function ($record) {
                return $record->getDepartments();
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
            ->rawColumns(['action', 'updated_by', 'name', 'aspect', 'description', 'departments'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(DepartmentAuditeeRequest $request)
    {
        $record = new DepartmentAuditee();
        return $record->handleStoreOrUpdate($request);
    }

    public function show(DepartmentAuditee $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(DepartmentAuditee $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(DepartmentAuditeeRequest $request, DepartmentAuditee $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(DepartmentAuditee $record)
    {
        return $record->handleDestroy();
    }
}
