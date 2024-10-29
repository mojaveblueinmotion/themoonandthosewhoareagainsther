<?php

namespace App\Exports;

use App\Models\BookingDetail;
use App\Models\Tm1\PembukuanLapak;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Builder;

class PembukuanLapakExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function view(): View
    {
        $data = PembukuanLapak::with('lapak', 'details', 'details.parts', 'details.parts.pembayaran')->where('perusahaan_id', $this->record->perusahaan_id)->where('month', $this->record->month)->first();
        return view('tm1.lapak.excel',
        [
            'data' => $data
        ]);
    }
}
