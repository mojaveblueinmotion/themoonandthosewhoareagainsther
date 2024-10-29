<?php

namespace App\Http\Requests\Master\Pembukuan;

use Illuminate\Foundation\Http\FormRequest;

class LapakRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|unique:ref_lapak,name,'.$id,
        ];

        return $rules;
    }
}
