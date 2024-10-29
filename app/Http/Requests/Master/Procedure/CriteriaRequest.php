<?php

namespace App\Http\Requests\Master\Procedure;

use App\Http\Requests\FormRequest;

class CriteriaRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|max:255',
            // 'description' => 'required',
        ];
        return $rules;
    }
}
