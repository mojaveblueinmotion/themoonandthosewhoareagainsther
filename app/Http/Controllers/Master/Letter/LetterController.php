<?php

namespace App\Http\Controllers\Master\Letter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Letter\LetterRequest;
use App\Models\Master\Letter\Letter;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    protected $module = 'master.letter';
    protected $routes = 'master.letter';
    protected $views = 'master.letter';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'title' => 'Formulir',
            'breadcrumb' => [
                'Data Master' => rut($this->routes . '.index'),
                'Formulir' => rut($this->routes . '.index'),
            ]
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:type|label:Menu|className:text-left'),
                    // $this->makeColumn('name:format|label:No. Surat|className:text-center'),
                    $this->makeColumn('name:formulir|label:No Formulir|className:text-center'),
                    $this->makeColumn('name:is_available|label:Tersedia|className:text-center'),
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
        $records = Letter::grid()->filters();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('type', function ($record) {
                return $record->show_type;
            })
            ->addColumn('format', function ($record) {
                return $record->format;
            })
            ->addColumn('formulir', function ($record) {
                return $record->no_formulir . ' | ' . $record->no_formulir_tambahan;
            })
            ->addColumn('is_available', function ($record) {
                if ($record->is_available != 'noactive') {
                    return "Tersedia";
                }
                return "Tidak Tersedia";
            })
            ->addColumn('updated_by', function ($record) {
                return $record->createdByRaw();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show|id:' . $record->id,
                    'type:edit|id:' . $record->id,
                ];
                return $this->makeButtonDropdown($actions);
            })
            ->rawColumns(['action', 'updated_by', 'type', 'format', 'formulir', 'is_available'])
            ->make(true);
    }

    public function create()
    {
        return $this->render($this->views . '.create');
    }

    public function store(LetterRequest $request)
    {
        $record = new Letter;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Letter $record)
    {
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Letter $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LetterRequest $request, Letter $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Letter $record)
    {
        return $record->handleDestroy();
    }
}
