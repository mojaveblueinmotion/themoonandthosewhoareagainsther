<?php

namespace App\Http\Requests\Master\Pembukuan;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|unique:ref_pembayaran_lainnya,name,'.$id,
        ];

        return $rules;
    }
}
