<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class DivisionRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'parent_id' => 'required|exists:ref_org_structs,id',
            'code'      => 'required|string|max:2|min:2|unique:ref_org_structs,code,' . $id . ',id,level,division,parent_id,' . $this->parent_id,
            'name'      => 'required|string|max:255|unique:ref_org_structs,name,' . $id . ',id,level,division,parent_id,' . $this->parent_id,
        ];

        return $rules;
    }
}
