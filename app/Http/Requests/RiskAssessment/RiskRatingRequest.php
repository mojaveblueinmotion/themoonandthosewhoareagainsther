<?php

namespace App\Http\Requests\RiskAssessment;

use App\Http\Requests\FormRequest;

class RiskRatingRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'risk_rating_id' => 'required',
        ];

        return $rules;
    }
}
