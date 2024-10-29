<?php

namespace App\Http\Controllers\Master\Document;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Document\DocumentItemRequest;
use App\Models\Master\Document\DocumentItem;
use Illuminate\Http\Request;

class DocumentItemController extends Controller
{
    protected $module = 'master.document-item';
    protected $routes = 'master.document-item';
    protected $views = 'master.document-item';
    protected $perms = 'master';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Dokumen Audit',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Audit' => rut($this->routes . '.index'),
                'Dokumen Audit' => rut($this->routes . '.index'),
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
                    // $this->makeColumn('name:description|label:Deskripsi|className:text-left'),
                    $this->makeColumn('name:aspect|label:Lingkup Audit|className:text-center|width:300px'),
                    $this->makeColumn('name:category|label:Jenis Audit|className:text-center'),
                    $this->makeColumn('name:object|label:Subjek Audit|className:text-center'),
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
        $records = DocumentItem::grid()->filters()->get();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return  $record->name ?? null;
            })
            ->addColumn('aspect', function ($record) {
                $aspect = $record->aspect ? $record->aspect->name : '';
                return $aspect;
            })
            ->addColumn('category', function ($record) {
                return $record->aspect ? $record->aspect->subject->typeAudit->name : '';
            })
            ->addColumn('object', function ($record) {
                return $record->aspect ? $record->aspect->subject->name : '';
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
            ->rawColumns(
                [
                    'object',
                    'action',
                    'updated_by',
                    'category', 'description', 'aspect', 'name'
                ]
            )
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(DocumentItemRequest $request)
    {
        $record = new DocumentItem;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(DocumentItem $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(DocumentItem $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(DocumentItemRequest $request, DocumentItem $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(DocumentItem $record)
    {
        return $record->handleDestroy();
    }
}
