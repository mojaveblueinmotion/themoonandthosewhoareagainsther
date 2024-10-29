<?php

namespace App\Http\Controllers\Tm2;

use App\Exports\PembukuanLapakExport;
use App\Support\Base;
use Illuminate\Http\Request;
use App\Models\Tm1\PembukuanLapak;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tm1\PembukuanLapakDetail;
use App\Http\Requests\Tm1\PembukuanLapakRequest;
use App\Http\Requests\Tm1\PembukuanLapakDetailRequest;

class PembukuanLapakController extends Controller
{
    protected $module = 'tm2.lapak';
    protected $routes = 'tm2.lapak';
    protected $views = 'tm2.lapak';
    protected $perms = 'tm2.lapak';

    public function __construct()
    {
        $this->prepare([
            'module' => $this->module,
            'routes' => $this->routes,
            'views' => $this->views,
            'perms' => $this->perms,
            'permission' => $this->perms . '.view',
            'title' => 'Pembukuan Lapak',
            'breadcrumb' => [
                'TM 2' => route($this->routes . '.index'),
                'Pembukuan Lapak' => route($this->routes . '.index'),
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
        $records = PembukuanLapak::grid()->filters()
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
        $record = new PembukuanLapak;
        return $this->render($this->views . '.create', compact('record'));
    }

    public function store(PembukuanLapakRequest $request)
    {
        $record = new PembukuanLapak;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(PembukuanLapak $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:tgl|label:Pengiriman|className:text-center'),
                    $this->makeColumn('name:berat_kotor|label:Berat Kotor|className:text-center'),
                    $this->makeColumn('name:potongan|label:Potongan|className:text-center'),
                    $this->makeColumn('name:berat_bersih|label:Berat Bersih|className:text-center'),
                    $this->makeColumn('name:pengeluaran|label:Pengeluaran|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi|width:50px'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);
        return $this->render($this->views . '.show', compact('record'));
    }

    public function edit(PembukuanLapak $record)
    {
        return $this->render($this->views . '.edit', compact('record'));
    }

    public function update(PembukuanLapakRequest $request, PembukuanLapak $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(PembukuanLapak $record)
    {
        return $record->handleDestroy();
    }

    public function detail(PembukuanLapak $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:tgl|label:Pengiriman|className:text-center'),
                    $this->makeColumn('name:berat_kotor|label:Berat Kotor|className:text-center'),
                    $this->makeColumn('name:potongan|label:Potongan|className:text-center'),
                    $this->makeColumn('name:berat_bersih|label:Berat Bersih|className:text-center'),
                    $this->makeColumn('name:pengeluaran|label:Pengeluaran|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);

        // dd('controller', $record->getTable(), $record->type->name, $record->subject->name);
        return $this->render($this->views . '.detail.index', compact('record'));
    }

    public function detailGrid(PembukuanLapak $record)
    {
        $user = auth()->user();
        $details = PembukuanLapakDetail::grid()
            ->whereHas(
                'pembukuanLapak',
                function ($q) use ($record) {
                    $q->where('id', $record->id);
                }
            )
            ->filters()
            ->dtGet();

        return \DataTables::of($details)
            ->addColumn('num', function ($detail) {
                return request()->start;
            })
            ->addColumn('tgl', function ($detail) {
                return '<span class="badge badge-success mb-1" data-toggle="tooltip" title="Tanggal Masuk">'. $detail->tgl_masuk->translatedFormat('d M Y') .'</span><br>
                <span class="badge badge-danger mb-1" data-toggle="tooltip" title="Tanggal Kirim Pabrik">'. $detail->kirim_pabrik->translatedFormat('d M Y') .'</span><br>
                <span class="badge badge-primary mb-1" data-toggle="tooltip" title="Vendor">'. $detail->vendor .'</span>';
            })
            ->addColumn('berat_kotor', function ($detail) {
                return '<span class="badge badge-danger mb-1" data-toggle="tooltip" title="Gross">'. number_format($detail->gross) .' Kg</span><br>
                <span class="badge badge-success mb-1" data-toggle="tooltip" title="Bruto">'. number_format($detail->bruto) .' Kg</span>';
            })
            ->addColumn('potongan', function ($detail) {
                return '<span class="badge badge-danger mb-1" data-toggle="tooltip" title="Refaksi">'. number_format($detail->refaksi) .' %</span><br>
                <span class="badge badge-warning mb-1" data-toggle="tooltip" title="Potongan">'. number_format($detail->potongan) .' Kg</span>';
            })
            ->addColumn('berat_bersih', function ($detail) {
                return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Nettp">'. number_format($detail->netto) .' Kg</span><br>
                <span class="badge badge-success mb-1" data-toggle="tooltip" title="Harga Satuan">Rp. '. number_format($detail->harga) .'</span>';
            })
            
            ->addColumn('pengeluaran', function ($detail) {
                return '<span class="badge badge-primary mb-1" data-toggle="tooltip" title="Total Harga">Rp. '. number_format($detail->jumlah) .'</span><br>
                <span class="badge badge-success mb-1" data-toggle="tooltip" title="Total Dibayarkan">Rp. '. number_format($detail->total_dibayar) .'</span>';
            })
            ->addColumn('updated_by', function ($detail) {
                return $detail->createdByRaw();
            })
            ->addColumn('action', function ($detail) {
                $actions = [];
                if ($detail->pembukuanLapak->checkAction('detailShow', $this->perms)) {
                    $actions[] = [
                        'type' => 'show',
                        'attrs' => 'data-modal-size="modal-xl" data-modal-position="modal-dialog-centered"',
                        'url' => route($this->routes . '.detailShow', $detail->id),

                    ];
                }
                if ($detail->pembukuanLapak->checkAction('detailEdit', $this->perms)) {
                    $actions[] = [
                        'type' => 'edit',
                        'attrs' => 'data-modal-size="modal-xl" data-modal-position="modal-dialog-centered"',
                        'url' => route($this->routes . '.detailEdit', $detail->id),

                    ];
                }
                if ($detail->pembukuanLapak->checkAction('detailDelete', $this->perms)) {
                    $actions[] = [
                        'type' => 'delete',
                        'url' => route($this->routes . '.detailDestroy', $detail->id),
                    ];
                }

                return $this->makeButtonDropdown($actions, $detail->id);
            })
            ->addColumn('action_show', function ($detail) use ($user) {
                $actions = [];
                if ($detail->pembukuanLapak->checkAction('detailShow', $this->perms)) {
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
                'berat_kotor',
                'updated_by',
                'potongan',
                'berat_bersih',
                'pengeluaran',
                'action',
                'action_show',
            ])
            ->make(true);
    }

    public function detailCreate(PembukuanLapak $record)
    {
        $this->prepare(
            [
                'title' => 'Detail Pembukuan Lapak'
            ]
        );

        return $this->render($this->views . '.detail.create', compact('record'));
    }

    public function detailStore(PembukuanLapakDetailRequest $request, PembukuanLapakDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailShow(PembukuanLapakDetail $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Pembukuan Lapak'
            ]
        );
        // $record = $detail->PembukuanLapak;
        return $this->render($this->views . '.detail.show', compact('detail'));
    }

    public function detailEdit(PembukuanLapakDetail $detail)
    {
        $this->prepare(
            [
                'title' => 'Detail Pembukuan Lapak'
            ]
        );
        return $this->render($this->views . '.detail.edit', compact('detail'));
    }

    public function detailUpdate(PembukuanLapakDetailRequest $request, PembukuanLapakDetail $detail)
    {
        return $detail->handleDetailStoreOrUpdate($request);
    }

    public function detailDestroy(PembukuanLapakDetail $detail)
    {
        return $detail->handleDetailDestroy($detail);
    }

    public function submit(PembukuanLapak $record)
    {
        $flowApproval = $record->getFlowApproval($this->module);
        return $this->render($this->views . '.submit', compact('record', 'flowApproval'));
    }

    public function submitSave(PembukuanLapak $record, Request $request)
    {
        $request->validate(['cc' => 'nullable|array']);
        return $record->handleSubmitSave($request);
    }

    public function approval(PembukuanLapak $record)
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:main_process_id|label:Main Process|className:text-center'),
                    $this->makeColumn('name:sub_process_id|label:Sub Process|className:text-center'),
                    $this->makeColumn('name:objective|label:Objective|className:text-center'),
                    $this->makeColumn('name:peristiwa|label:Risk Event|className:text-center'),
                    $this->makeColumn('name:penyebab|label:Risk Cause|className:text-center'),
                    $this->makeColumn('name:dampak|label:Risk Impact|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action_show|label:Aksi'),
                ],
                'url' => route($this->routes . '.detailGrid', $record->id)
            ]
        ]);
        return $this->render($this->views . '.approval', compact('record'));
    }

    public function reject(PembukuanLapak $record, Request $request)
    {
        $request->validate(['note' => 'required|string']);
        return $record->handleReject($request);
    }

    public function approve(PembukuanLapak $record, Request $request)
    {
        $result = $record->handleApprove($request);
        if ($record->status == 'completed') {
            $this->print($record);
        }
        return $result;
    }

    public function revisi(PembukuanLapak $record, Request $request)
    {
        return $record->handleRevisi($request);
    }

    public function history(PembukuanLapak $record)
    {
        $this->prepare(['title' => 'History Aktivitas']);
        return $this->render('globals.history', compact('record'));
    }

    public function tracking(PembukuanLapak $record)
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

    

    public function print(PembukuanLapak $record)
    {
        return Excel::download(new PembukuanLapakExport($record), 'PEMBUKUAN-'.$record->lapak->name.'-'.$record->month->translatedFormat('F Y').'.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'autoSize' => true,
        ]);
    }
}
