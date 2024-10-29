<?php

namespace App\Http\Requests\Master\Letter;

use App\Http\Requests\FormRequest;

class LetterRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            // 'format' => 'required|string|max:20',
            'no_formulir' => 'required|string',
            'no_formulir_tambahan' => 'required|string',
            'is_available' => 'required'
        ];

        return $rules;
    }
}