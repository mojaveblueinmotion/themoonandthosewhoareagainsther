<?php

namespace App\Http\Requests\Master\Risk;

use App\Http\Requests\FormRequest;

class LastAuditRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $year = $this->year ?? 0;
        $subject_id = $this->object_id ?? 0;
        $type_id = $this->type_id;

        $rules = [
            'year' => 'required',
            'type_id' => 'required',
            'unitKerja'=> 'required',
            'code' => 'required',
            'date' => 'required',
        ];

        $unique = $id . ',id,year,' . $year . ',type_id,' . $type_id;

        if ($type_id == 2) {
            $rules['object_id'] = 'required|unique:ref_last_audits,object_id,' . $unique;
        } else {
            $rules += [
                'object_id' => 'required|unique:ref_last_audits,object_id,' . $unique,
                // 'object_type' => 'required'
            ];
        }

        return $rules;
    }
}
