<?php

namespace App\Http\Requests\RiskAssessment;

use App\Http\Requests\FormRequest;

class CurrentRiskRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            // Likelihood
            'complexity' => 'required',
            'volume' => 'required',
            'known_issue' => 'required',
            'chaning_process' => 'required',
            
            // Impact
            'materiality' => 'required',
            'legal' => 'required',
            'operational' => 'required',

            'condition' => 'required',
            'notes' => 'required',

        ];

        return $rules;
    }
}
