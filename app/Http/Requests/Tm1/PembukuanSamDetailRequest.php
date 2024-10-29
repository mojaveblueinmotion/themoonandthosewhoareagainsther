<?php

namespace App\Http\Requests\Tm1;

use App\Http\Requests\FormRequest;

class PembukuanSamDetailRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            // 'no_timbangan'      => 'required',
            'tgl_masuk'         => 'required',
            // 'kirim_pabrik'         => 'required',
            // 'vendor',
            'gross'             => 'required',
            'tere'              => 'required',
            'bruto'             => 'required',
            'refaksi'           => 'required',
            'potongan'          => 'required',
            'netto'             => 'required',
            'harga'             => 'required',
            'jumlah'            => 'required',
            // 'parts.*.total' => 'required_with:parts.*.pembayaran_id',
            // 'premi_supir',
            // 'premi_agen',
            // 'total_dibayar',
            // 'bongkaran',
            // 'pengeluaran_lapak',
        ];
        return $rules;
    }
}
