<?php

namespace App\Http\Requests\Master\Org;

use App\Http\Requests\FormRequest;

class BodRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'parent_id' => [
                'required',
                // 'different:id',
                'exists:ref_org_structs,id'
            ],
            'code'  => 'required|string|max:2|min:2|unique:ref_org_structs,code,'.$id.',id',
            'name'      => [
                'required',
                'max:255',
                'unique:ref_org_structs,name,'.$id.',id,level,bod'
            ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'different' => 'zxc'
        ];
    }
}
