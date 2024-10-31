<?php

namespace App\Http\Controllers\Tm1;

use App\Exports\LoaderExport;
use App\Support\Base;
use Illuminate\Http\Request;
use App\Models\Tm1\Loader;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tm1\LoaderDetail;
use App\Http\Requests\Tm1\LoaderRequest;
use App\Http\Requests\Tm1\LoaderDetailRequest;

class LoaderController extends Controller
{
    protected $module = 'tm1.loader';
    protected $routes = 'tm1.loader';
    protected $views = 'tm1.loader';
    protected $perms = 'tm1.loader';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Loader',
            'breadcrumb' => [
                'TM 1' => route($this->routes . '.index'),
                'Loader' => route($this->routes . '.index'),
            ],
        ]);
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:month|label:Bulan|className:text-center'),
                    $this->makeColumn('name:total|label:Total Data|className:text-center'),
                    $this->makeColumn('name:version|label:Versi|className:text-center'),
                    $this->makeColumn('name:status'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ]
        ]);

        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $location = auth()->user()->position->location ?? null;
        $records = Loader::grid()->filters()
            ->when(request()->get('status') !== '*', function ($q) {
                $q->filterBy(['status', '=']);
            })
            ->dtGet();

        return \DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'month',
                function ($record) use ($user) {
                    return $record->month->format('F Y');
                }
            )
            ->addColumn(
                'total',
                function ($record) use ($user) {
                    return $record->details()->count() . ' Detail';
                }
            )
            ->addColumn('status', function ($record) use ($user) {
                return $record->labelStatus();
            })
            ->addColumn('updated_by', function ($record) use ($user) {
                return $record->createdByRaw();
            })
            ->addColumn(
                'version',
                function ($record) use ($user) {
                    if ($record) {
                        return $record->version;
                    }
                    return "0";
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'page' => true,
                        'url' => route($this->routes . '.show', $record->id),
                    ];
                }
                if ($record->checkAction('edit', $this->perms) && !in_array($record->status, ['waiting.approval', 'completed'])) {
                    $actions[] = [
                        'type' => 'create',
                        'label' => 'Detail',
                        'page' => true,
                        'url' => route($this->routes . '.detail', $record->id),
                    ];
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit';
                }
                if ($record->checkAction('revisi', $this->perms)) {
                    $actions[] = [
                        'icon' => 'fa fa-sync text-warning',
                        'label' => 'Revisi',
                        'url' => route($this->routes . '.revisi', $record->id),
                        'class' => 'base-form--postByUrl',
                        'attrs' => 'data-swal-ok="Revisi" data-swal-text="Data yang telah di-revisi akan dikembalikan ke status draft untuk dapat diperbarui!"',
                    ];
                }
                if($record->details()->count() > 0){
                    if ($record->checkAction('print', $this->perms)) {
                        $actions[] = 'type:print';
                    }
                }
                
                if ($record->checkAction('delete', $this->perms)) {
                    $actions[] = 'type:delete';
                }
                if ($record->checkAction('history', $this->perms)) {
                    $actions[] = 'type:history';
                }

                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(['month', 'total', 'action', 'updated_by', 'status'])
            ->make(true);
    }

    public function create()
    {
        $record = new Loader;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(LoaderRequest $request)
    {
        $record = new Loader;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Loader $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:tgl|label:Tanggal|className:text-center'),
                    $this->makeColumn('name:keterangan|label:Keterangan|className:text-center'),
                    $this->makeColumn('name:debet|label:Debet|className:text-center'),
                    $this->makeColumn('name:kredit|label:Kredit|className:text-center'),
                    $this->makeColumn('name:saldo|label:Saldo|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi|width:50px'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(Loader $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(LoaderRequest $request, Loader $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Loader $record)
    {
        return $record->handleDestroy();
    }

    public function detail(Loader $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:tgl|label:Tanggal|className:text-center'),
                    $this->makeColumn('name:keterangan|label:Keterangan|className:text-center'),
                    $this->makeColumn('name:debet|label:Debet|className:text-center'),
                    $this->makeColumn('name:kredit|label:Kredit|className:text-center'),
                    $this->makeColumn('name:saldo|label:Saldo|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);

        // dd('controller', $record->getTable(), $record->type->name, $record->subject->name);
        return $this->render($this->views . '.detail.index', compact('record'));
    }

    public function detailGrid(Loader $record)
    {
        $user = auth()->user();
        $details = LoaderDetail::grid()
            ->whereHas(
                'loader',
                function ($q) use ($record) {
                    $q->where('id', $record->id);
                }
            )
            ->filters()
            ->orderBy('id', 'desc')->get();
            // ->dtGet();

        $historyData = LoaderDetail::orderBy('id', 'asc')->where('loader_id', $record->id)->get();
        $saldoHistory = [];  // Store saldo for each transaction
        $currentSaldo = 0;   // Initialize starting saldo
        
        // Step 1: Calculate the saldo in correct (ascending) order
        foreach ($historyData as $detail) {
            if ($detail->tipe == 1) {  // Debit: Subtract from saldo
                $currentSaldo -= $detail->total;
            } else {  // Credit: Add to saldo
                $currentSaldo += $detail->total;
            }
            
            // Store the calculated saldo for each detail (by ID)
            $saldoHistory[$detail->id] = $currentSaldo;
        }

        return \DataTables::of($details)
            ->addColumn('num', function ($detail) {
                return request()->start;
            })
            ->addColumn('tgl', function ($detail) {
                return $detail->tgl_input->translatedFormat('d M Y');
            })
            ->addColumn('keterangan', function ($detail) {
                return $detail->keterangan;
            })
            ->addColumn('debet', function ($detail) {
                if($detail->tipe == 2){
                    return '<span class="badge badge-success mb-1" data-toggle="tooltip" title="Debet">Rp. '. number_format($detail->total) .'</span>';
                }
                return '';
            })
            ->addColumn('kredit', function ($detail) {
                if($detail->tipe == 1){
                    return '<span class="badge badge-danger mb-1" data-toggle="tooltip" title="Kredit">Rp. '. number_format($detail->total) .'</span>';
                }
                return '';
            })
            ->addColumn('saldo', function ($detail) use (&$saldoHistory) {
                $saldo = $saldoHistory[$detail->id];
                // if($detail->tipe == 1){
                //     if(LoaderDetail::orderBy('id', 'asc')->first()->id != $detail->id){
                //         $findLastSaldo = LoaderDetail::where('id', '<', $detail->id)->orderBy('id', 'desc')->first()->saldo_sisa;
                //         return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Saldo Sisa">Rp. '. number_format($findLastSaldo - $detail->total) .'</span>';
                //     }
                //     return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Saldo Sisa">Rp. '. number_format($detail->saldo_sisa) .'</span>';
                // }else{
                //     if(LoaderDetail::orderBy('id', 'asc')->first()->id != $detail->id){
                //         $findLastSaldo = LoaderDetail::where('id', '<', $detail->id)->orderBy('id', 'desc')->first()->saldo_sisa;
                //         return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Saldo Sisa">Rp. '. number_format($findLastSaldo + $detail->total) .'</span>';
                //     }
                //     return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Saldo Sisa">Rp. '. number_format($detail->saldo_sisa) .'</span>';
                // }
                // Determine the new saldo based on transaction type
                // if ($detail->tipe == 1) {
                //     $saldo = $previousSaldo - $detail->total;
                // } else {
                //     $saldo = $previousSaldo + $detail->total;
                // }

                // Update the previous saldo for the next row
                $previousSaldo = $saldo;

                // Return the saldo with formatted HTML output
                return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Saldo Sisa">Rp. ' 
                    . number_format($saldo) . '</span>';
                
            })
            ->addColumn('updated_by', function ($detail) {
                return $detail->createdByRaw();
            })
            ->addColumn('action', function ($detail) {
                $actions = [];
                if ($detail->loader->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'attrs' => 'data-modal-size="modal-xl" data-modal-position="modal-dialog-centered"',
                        'url' => route($this->routes . '.detailShow', $detail->id),

                    ];
                }
                if ($detail->loader->checkAction('detailEdit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'attrs' => 'data-modal-size="modal-xl" data-modal-position="modal-dialog-centered"',
                        'url' => route($this->routes . '.detailEdit', $detail->id),

                    ];
                }
                if ($detail->loader->checkAction('detailDelete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'url' => route($this->routes . '.detailDestroy', $detail->id),
                    ];
                }

                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->addColumn('action_show', function ($detail) use ($user) {
                $actions = [];
                if ($detail->loader->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'attrs' => 'data-modal-size="modal-xl" data-modal-position="modal-dialog-centered"',
                        'url' => route($this->routes . '.detailShow', $detail->id),
                    ];
                }
                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->rawColumns([
                'tgl',
                'debet',
                'updated_by',
                'kredit',
                'saldo',
                'action',
                'action_show',
            ])
            ->make(true);
    }

    public function detailCreate(Loader $record)
    {
        $this->prepare(
            [
                'title' => 'Detail Loader'
            ]
        );

        return $this->render($this->views . '.detail.create', compact('record'));
    }

    public function detailStore(LoaderDetailRequest $request, LoaderDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailShow(LoaderDetail $detail)
    {
        $historyData = LoaderDetail::orderBy('id', 'asc')->where('loader_id', $detail->loader->id)->get();
        $saldoHistory = [];  // Store saldo for each transaction
        $currentSaldo = 0;   // Initialize starting saldo
        
        // Step 1: Calculate the saldo in correct (ascending) order
        foreach ($historyData as $loader) {
            if ($loader->tipe == 1) {  // Debit: Subtract from saldo
                $currentSaldo -= $loader->total;
            } else {  // Credit: Add to saldo
                $currentSaldo += $loader->total;
            }
            
            // Store the calculated saldo for each detail (by ID)
            $saldoHistory[$loader->id] = $currentSaldo;
        }
        $this->prepare(
            [
                'title' => 'Detail Loader'
            ]
        );
        // $record = $detail->loader;
        return $this->render($this->views . '.detail.show', compact('detail', 'saldoHistory'));
    }

    public function detailEdit(LoaderDetail $detail)
    {
        $historyData = LoaderDetail::orderBy('id', 'asc')->where('loader_id', $detail->loader->id)->get();
        $saldoHistory = [];  // Store saldo for each transaction
        $currentSaldo = 0;   // Initialize starting saldo
        
        // Step 1: Calculate the saldo in correct (ascending) order
        foreach ($historyData as $loader) {
            if ($loader->tipe == 1) {  // Debit: Subtract from saldo
                $currentSaldo -= $loader->total;
            } else {  // Credit: Add to saldo
                $currentSaldo += $loader->total;
            }
            
            // Store the calculated saldo for each detail (by ID)
            $saldoHistory[$loader->id] = $currentSaldo;
        }
        $this->prepare(
            [
                'title' => 'Detail Loader'
            ]
        );
        return $this->render($this->views . '.detail.edit', compact('detail', 'saldoHistory'));
    }

    public function detailUpdate(LoaderDetailRequest $request, LoaderDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailDestroy(LoaderDetail $detail)
    {
        return $detail->handleDetailDestroy($detail);
    }

    public function submit(Loader $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(Loader $record, Request $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(Loader $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:tgl|label:Tanggal|className:text-center'),
                    $this->makeColumn('name:keterangan|label:Keterangan|className:text-center'),
                    $this->makeColumn('name:debet|label:Debet|className:text-center'),
                    $this->makeColumn('name:kredit|label:Kredit|className:text-center'),
                    $this->makeColumn('name:saldo|label:Saldo|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function reject(Loader $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(Loader $record, Request $request)
    {
        $result = $record->handleApprove($request);
        if ($record->status == 'completed') {
            $this->print($record);
        }
        return $result;
    }

    public function revisi(Loader $record, Request $request)
    {
        return $record->handleRevisi($request);
    }

    public function history(Loader $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(Loader $record)
    {
        $this->prepare(['title' => 'Tracking Approval']);
        $module = $this->module;
        if ($record->status == 'waiting.approval.revisi') {
            $module = $module . '_upgrade';
        } else {
            $module = $this->module;
        }
        return $this->render('globals.tracking', compact('record', 'module'));
    }

    

    public function print(Loader $record)
    {
        return Excel::download(new LoaderExport($record), 'PEMBUKUAN-SAM-'.$record->lapak->name.'-'.$record->month->translatedFormat('F Y').'.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'autoSize' => true,
        ]);
    }
}
