<?php

namespace App\Http\Requests\Master\Aspect;

use App\Http\Requests\FormRequest;

class AspectRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $object_id = $this->object_id ?? 0;

        $rules = [
            'name' => 'required|string|max:255|unique:ref_aspects,name,' . $id . ',id,main_process_id,' . $this->main_process_id,
            'object_id' => 'required',
            'type_id' => 'required',
            // 'main_process_id' => 'required|unique:ref_aspects,main_process_id,' . $id . ',id,name,' . $this->name,
            'main_process_id' => 'required',
            // 'object_id' => 'required'
        ];
        return $rules;
    }
}
