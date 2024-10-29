<?php

namespace App\Http\Requests\Master\Pembukuan;

use Illuminate\Foundation\Http\FormRequest;

class KendaraanRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|unique:ref_kendaraan,name,'.$id,
            // 'no_kendaraan' => 'required',
        ];

        return $rules;
    }
}
