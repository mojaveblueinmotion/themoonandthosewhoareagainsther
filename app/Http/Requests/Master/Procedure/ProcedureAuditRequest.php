<?php

namespace App\Http\Requests\Master\Procedure;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class ProcedureAuditRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $procedure_id = $this->procedure_id ?? 0;
        $aspect_id = $this->aspect_id ?? 0;
        $subject_id = $this->subject_id ?? 0;
        $type_id = $this->type_id ?? 0;

        $rules = [
            'type_id'       => 'required',
            'subject_id'    => 'required',
            'aspect_id'     => 'required',
            'objective_id'   => 'required',
            'procedure'    => [
                'required',
                'unique:ref_procedure_audit,procedure,'
                    . $procedure_id . ',id'
                    . ',objective_id,' . $this->objective_id
                    // . ',number,' . $this->number
            ],
            'number'    => [
                'required',
                'unique:ref_procedure_audit,number,'
                    . $procedure_id . ',id'
                    . ',objective_id,' . $this->objective_id
                    // . ',procedure,' . $this->procedure
            ],

            'mandays'       => 'required',
        ];
        if ($id) {
            unset($rules['type_id']);
            unset($rules['objective_id']);
            unset($rules['aspect_id']);
        }
        return $rules;
    }
}
