<?php

namespace App\Http\Requests\RiskAssessment;

use App\Http\Requests\FormRequest;

class RiskRegisterRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'periode'               => 'required',
            'type_id'               => 'required',
            'object_id'             => 'required',
            'unitKerja'             => 'required',
        ];
        return $rules;
    }
}
