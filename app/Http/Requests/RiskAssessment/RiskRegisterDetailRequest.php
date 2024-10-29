<?php

namespace App\Http\Requests\RiskAssessment;

use App\Http\Requests\FormRequest;

class RiskRegisterDetailRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->id ?? 0;

        $rules = [
            'main_process_id'   => 'required',
            'peristiwa'         => 'required',
            'sub_process_id'    => [
                'required',
                'unique:trans_risk_assessment_register_detail,sub_process_id,'
                    . $id . ',id'
                    . ',risk_register_id,' . $this->risk_register_id
                    . ',main_process_id,' . $this->main_process_id
                    . ',sub_process_id,' . $this->sub_process_id
            ],
            'penyebab'          => 'required',
            'dampak'            => 'required',
            'objective'            => 'required',
        ];

        return $rules;
    }
}
