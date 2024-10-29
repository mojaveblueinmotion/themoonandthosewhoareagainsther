<?php

namespace App\Http\Requests\Master\Risk;

use Illuminate\Foundation\Http\FormRequest;

class RiskStatusRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|unique:ref_risk_status,name,'.$id,
        ];

        return $rules;
    }
}
