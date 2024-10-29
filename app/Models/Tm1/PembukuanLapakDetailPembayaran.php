<?php

namespace App\Models\Tm1;

use App\Models\Master\Pembukuan\Pembayaran;
use App\Models\Model;
use Carbon\Carbon;

class PembukuanLapakDetailPembayaran extends Model
{
    protected $table = 'trans_detaiL_pembukuan_lapak_pembayaran';

    protected $fillable = [
        'detail_id',
        'pembayaran_id',
        'total',
    ];

    /** MUTATOR **/
    
    public function setTotalAttribute($value = '')
    {
        $value = str_replace(['.', ','], '', $value);
        $this->attributes['total'] = (int) $value;
    }
    /** ACCESSOR **/
    
    /** RELATION **/
    public function detail()
    {
        return $this->belongsTo(PembukuanLapakDetail::class, 'detail_id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    /** SAVE DATA **/
    /** OTHER FUNCTIONS **/
}
