<?php

namespace App\Http\Requests\Tm1;

use App\Http\Requests\FormRequest;

class PembukuanLapakRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'month'               => 'required', 
            // 'month'               => 'required|unique:trans_pembukuan_lapak,month,'.$id,
        ];
        return $rules;
    }
}
