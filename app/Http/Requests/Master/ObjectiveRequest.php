<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class ObjectiveRequest extends FormRequest
{

    public function rules(): array
    {
        $id = $this->record->id ?? 0;
        return [
            'name' => 'required|string|max:255|unique:ref_objective,name,' . $id . ',id,aspect_id,' . $this->aspect_id,
            'type_id' => "required",
            'object_id' => "required",
            "aspect_id" => 'required',
            "main_process_id" => 'required',
        ];
    }
}
