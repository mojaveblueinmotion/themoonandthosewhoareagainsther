<?php

namespace App\Http\Controllers\Master\Subject;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Subject\SubjectRequest;
use App\Models\Master\Org\OrgStruct;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $module = 'master.subject-audit';
    protected $routes = 'master.subject-audit';
    protected $views = 'master.subject-audit';
    protected $perms = 'master';


    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Subject Audit',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Audit' => rut($this->routes . '.index'),
                'Subject Audit' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:code|label:Kode|orderable:false|sortable:false|className:text-center'),
                    $this->makeColumn('name:name|label:Nama|orderable:false|sortable:false|className:text-left'),
                    $this->makeColumn('name:type|label:Jenis Audit|orderable:false|sortable:false|className:text-center'),
                    // $this->makeColumn('name:subject_id|label:Dept Auditee|orderable:false|sortable:false|className:text-center'),
                    $this->makeColumn('name:description|label:Deskripsi|orderable:false|sortable:false|className:text-center'),
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
        $records = OrgStruct::subject()
            ->grid()
            ->filters()
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name;
            })
            ->addColumn('type', function ($record) {
                return $record->typeAudit->name;
            })
            ->addColumn('subject_id', function ($record) {
                return $record->getUnitKerja();
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
            ->rawColumns(['action', 'updated_by', 'category', 'description', 'name','subject_id'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(SubjectRequest $request)
    {
        $record = new OrgStruct;
        return $record->handleStoreOrUpdate($request, 'subject');
    }

    public function show(OrgStruct $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(OrgStruct $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(SubjectRequest $request, OrgStruct $record)
    {
        return $record->handleStoreOrUpdate($request, 'subject');
    }

    public function destroy(OrgStruct $record)
    {
        return $record->handleDestroy();
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
        $view = $this->views . '.template';
        $data = [];
        return \Excel::download(new GenerateExport($view, $data), $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate([
            'uploads.uploaded' => 'required',
            'uploads.temp_files_ids.*' => 'required',
        ], [], [
            'uploads.uploaded' => 'Lampiran',
            'uploads.temp_files_ids.*' => 'Lampiran',
        ]);

        $record = new OrgStruct;
        return $record->handleImport($request);
    }
}


