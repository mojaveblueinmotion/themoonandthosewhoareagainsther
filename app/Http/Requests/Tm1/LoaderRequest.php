<?php

namespace App\Http\Requests\Tm1;

use App\Http\Requests\FormRequest;

class LoaderRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'month'               => 'required', 
        ];
        return $rules;
    }
}