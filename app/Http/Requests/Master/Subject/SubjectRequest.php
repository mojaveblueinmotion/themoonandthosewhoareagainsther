<?php

namespace App\Http\Requests\Master\Subject;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->record->id ?? 0;
        return [
            'code'      => 'required|string|max:3|min:3|unique:ref_org_structs,code,' . $id . ',id,level,subject',
            'type_id'   => 'required',
            'name'      => 'required|string|max:255',
        ];
    }
}
