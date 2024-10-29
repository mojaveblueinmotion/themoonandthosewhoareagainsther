<?php

namespace App\Http\Requests\Master\Org;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentAuditeeRequest extends FormRequest
{

    public function rules(): array
    {
        $id = $this->record->id ?? 0;
        $subject_id = $this->subject_id ?? 0;
        $type_id = $this->type_id;

        return [
            'year' => 'required|string|max:255|unique:ref_department_auditee,year,' . $id . ',id,type_id,' . $type_id . ',subject_id,' . $subject_id,
            'type_id' => "required",
            'subject_id' => "required",
            "departments.*" => 'required',
        ];
    }
}
