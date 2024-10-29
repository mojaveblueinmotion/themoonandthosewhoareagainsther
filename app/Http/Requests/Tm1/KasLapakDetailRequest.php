<?php

namespace App\Http\Requests\Tm1;

use App\Http\Requests\FormRequest;

class KasLapakDetailRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'tgl_input'         => 'required',
            'keterangan'             => 'required',
            'tipe'              => 'required',
            'total'             => 'required',
        ];
        return $rules;
    }
}
