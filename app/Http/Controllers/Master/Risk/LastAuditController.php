<?php

namespace App\Http\Controllers\Master\Risk;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Risk\LastAuditRequest;
use App\Models\Master\Risk\LastAudit;
use Illuminate\Http\Request;

class LastAuditController extends Controller
{
    protected $module = 'master.last-audit';
    protected $routes = 'master.last-audit';
    protected $views = 'master.last-audit';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Audit Terakhir',
            'breadcrumb' => [
                'Data Master' => route($this->routes . '.index'),
                'Audit' => route($this->routes . '.index'),
                'Audit Terakhir' => route($this->routes . '.index'),
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
                    $this->makeColumn('name:category|label:Subjek Audit|className:text-center'),
                    $this->makeColumn('name:auditee|label:Dept Auditee|className:text-center'),
                    $this->makeColumn('name:lampiran|label:Lampiran|className:text-center'),
                    $this->makeColumn('name:lhp|label:LHA|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = LastAudit::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('year', function ($record) {
                return $record->year;
            })
            ->addColumn('category', function ($record) {
                $subject = $record->object_id ? $record->subject->name : null;
                return $record->show_category .'<br>' . $subject;
            })
            ->addColumn('auditee', function ($record) {
                return $record->deptAuditee->getDepartments();
            })
            ->addColumn('lampiran', function ($record) {
                return $record->files()->count() . " Lampiran";
            })
            ->addColumn('lhp', function ($record) {
                if (!empty($record->lhp->date)) {
                    return $record->lhp->code . '<br>' . $record->lhp->date->translatedFormat('d F Y');
                }
                return $record->code . '<br>' . $record->date->translatedFormat('d F Y');
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
            ->rawColumns(
                [
                    'subject',
                    'auditee',
                    'year',
                    'rating',
                    'action',
                    'category',
                    'updated_by',
                    'category','lhp'
                ]
            )
            ->make(true);
    }

    public function create()
    {
        $record = new LastAudit;
        $startYear = date('Y');
        return $this->render($this->views . '.create', compact('record', 'startYear'));
    }

    public function store(LastAuditRequest $request)
    {
        $record = new LastAudit;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(LastAudit $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(LastAudit $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LastAuditRequest $request, LastAudit $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(LastAudit $record)
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

        $record = new LastAudit;
        return $record->handleImport($request);
    }
}
