<?php

namespace App\Http\Requests\Master\Risk;

use Illuminate\Foundation\Http\FormRequest;

class MainProcessRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'subject_id'    => 'required',
            'name'          => 'required|string|unique:ref_main_process,name,'.$id,
        ];

        return $rules;
    }
}
