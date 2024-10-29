<?php

namespace App\Exports;

use App\Models\BookingDetail;
use App\Models\RiskAssessment\RiskRegisterDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Builder;

class RiskRegisterExport implements FromView
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
        $risk = RiskRegisterDetail::with('currentRisk', 'inherentRisk', 'kodeResiko', 'jenisResiko')->where('risk_register_id', $this->record->id)->get();
        return view('report.risk-assessment.excel',
        [
            'risk' => $risk
        ]);
    }
}
