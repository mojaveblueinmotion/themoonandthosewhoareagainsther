<?php

namespace App\Exports;

use App\Models\Tm1\KasLapak;
use App\Models\Tm1\Loader;
use App\Models\Tm1\PembukuanSam;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Builder;

class LoaderExport implements FromView
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
        $data = Loader::with('lapak', 'details', 'details', 'details')->where('perusahaan_id', $this->record->perusahaan_id)->where('month', $this->record->month)->first();
        return view('tm1.loader.excel',
        [
            'data' => $data
        ]);
    }
}