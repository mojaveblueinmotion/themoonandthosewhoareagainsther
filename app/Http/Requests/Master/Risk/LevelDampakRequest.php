<?php

namespace App\Http\Requests\Master\Risk;

use Illuminate\Foundation\Http\FormRequest;

class LevelDampakRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;

        $rules = [
            'name' => 'required|string|unique:ref_level_dampak,name,'.$id,
        ];

        return $rules;
    }
}
