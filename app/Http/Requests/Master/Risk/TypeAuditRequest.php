<?php

namespace App\Http\Requests\Master\Risk;

use Illuminate\Foundation\Http\FormRequest;

class TypeAuditRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|unique:ref_type_audit,name,'.$id,
        ];

        return $rules;
    }
}
