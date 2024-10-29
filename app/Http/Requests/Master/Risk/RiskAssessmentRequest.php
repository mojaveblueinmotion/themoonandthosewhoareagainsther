<?php

namespace App\Http\Requests\Master\Risk;

use App\Http\Requests\FormRequest;

class RiskAssessmentRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $object_id = $this->object_id ?? 0;
        $type_id = $this->type_id;
        $year = $this->year;

        $rules = [
            'year' => 'required',
            'type_id' => 'required',
            'object_id' => 'required',
            'details' => 'required|array',
            'details.*.description' => 'required|string|max:255|distinct',
            'details.*.risk_rating_id' => 'required',
            'details.*.source' => 'required|string',
        ];

        if ($type_id == 2) {
            $unique = $id . ',id,year,' . $year . ',type_id,' . $type_id . ',object_id,' . $object_id;
            $rules += ['key' => 'required|string|max:191|unique:ref_risk_assessments,key,' . $unique];
        } else {
            $rules += ['key' => 'required|string|max:191|unique:ref_risk_assessments,key'];
        }

        return $rules;
    }
}
