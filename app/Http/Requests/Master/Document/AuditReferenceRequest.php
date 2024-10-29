<?php

namespace App\Http\Requests\Master\Document;

use App\Http\Requests\FormRequest;

class AuditReferenceRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $aspect_id = $this->aspect_id ?? 0;
        $object_id = $this->object_id ?? 0;
        $type_id = $this->type_id ?? 0;

        $rules = [
            'type_id' => 'required',
            'subject_id' => 'required',
            'aspect_id' => 'required',
            'procedure_id' => 'required',
            'name' => 'required|string|max:255|unique:ref_audit_reference,name,' . $id . ',id,procedure_id,' . $this->procedure_id,
        ];
        if ($id) {
            unset($rules['type_id']);
            unset($rules['subject_id']);
            unset($rules['procedure_id']);
            unset($rules['aspect_id']);
        }

        return $rules;
    }
}
