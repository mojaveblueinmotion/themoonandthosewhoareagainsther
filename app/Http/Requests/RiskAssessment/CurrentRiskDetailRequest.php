<?php

namespace App\Http\Requests\RiskAssessment;

use App\Http\Requests\FormRequest;

class CurrentRiskDetailRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'internal_control' => 'required',
            'tgl_realisasi' => 'required',
            'realisasi' => 'required',
        ];

        return $rules;
    }
}
