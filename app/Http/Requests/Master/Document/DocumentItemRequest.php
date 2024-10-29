<?php

namespace App\Http\Requests\Master\Document;

use App\Http\Requests\FormRequest;

class DocumentItemRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $aspect_id = $this->aspect_id ?? 0;
        $object_id = $this->object_id ?? 0;
        $type_id = $this->type_id ?? 0;

        $rules = [
            'type_id' => 'required',
            'aspect_id' => 'required',
            'subject_id' => 'required',
            'name' => 'required|string|max:255',
        ];
        if ($id) {
            unset($rules['type_id']);
            // unset($rules['object_id']);
            // unset($rules['object_type']);
            unset($rules['aspect_id']);
        }
        return $rules;
    }
}
