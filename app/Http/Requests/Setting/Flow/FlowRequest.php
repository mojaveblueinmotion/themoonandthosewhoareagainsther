<?php

namespace App\Http\Requests\Setting\Flow;

use App\Http\Requests\FormRequest;

class FlowRequest extends FormRequest
{
    public function rules()
    {
        $rules = [];
        if (is_array($this->flow)) {
            $rules = [
                'flows' => 'required|array',
                'flows.*.role_id' => 'required|distinct|exists:sys_roles,id',
                'flows.*.type'     => 'required'
            ];
        }

        return $rules;
    }
}
