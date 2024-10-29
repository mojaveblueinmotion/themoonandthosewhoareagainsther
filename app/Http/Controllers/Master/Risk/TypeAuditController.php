<?php

namespace App\Http\Controllers\Master\Risk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\TypeAuditRequest;
use App\Models\Master\Risk\TypeAudit;
use Illuminate\Http\Request;

class TypeAuditController extends Controller
{
    protected $module = 'master.type-audit';
    protected $routes = 'master.type-audit';
    protected $views = 'master.type-audit';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Jenis Audit',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Audit' => rut($this->routes . '.index'),
                'Jenis Audit' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:name|label:Nama|orderable:false|sortable:false|className:text-left'),
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
        $records = TypeAudit::grid()->filters()->get();

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
            ->rawColumns(['action', 'updated_by', 'category', 'description', 'name'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(TypeAuditRequest $request)
    {
        $record = new TypeAudit;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(TypeAudit $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(TypeAudit $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(TypeAuditRequest $request, TypeAudit $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(TypeAudit $record)
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

        $record = new TypeAudit;
        return $record->handleImport($request);
    }
}
