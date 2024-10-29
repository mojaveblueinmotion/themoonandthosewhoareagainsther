<?php

namespace App\Http\Requests\Master\Risk;

use App\Http\Requests\FormRequest;

class RiskRatingRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|max:255|unique:ref_risk_ratings,name,'.$id,
            'description' => '',
        ];

        return $rules;
    }
}
