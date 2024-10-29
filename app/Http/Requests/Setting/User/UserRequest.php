<?php

namespace App\Http\Requests\Setting\User;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function rules()
    {
        // dd($this->roles);
        $id = $this->record->id ?? 0;
        $rules = [
            'username'    => 'required|string|max:60|unique:sys_users,username,' . $id,
            'name'        => 'required|string|max:255',
            'username'              => 'required|string|unique:sys_users,username,' . $id,
            'npp'        => 'required|numeric|unique:sys_users,npp,' . $id,
            'email'       => 'required|string|max:60|email|unique:sys_users,email,' . $id,
            'position_id' => 'required|exists:ref_positions,id',
            'status'      => 'required',
            'roles.*'      => 'required',
            'location_id'      => 'required',
        ];

        if (in_array(1, $this->roles)) {
            unset($rules['location_id']);
            unset($rules['npp']);
            unset($rules['position_id']);
        }
        if (!$id) {
            $password_rules = [
                function ($attribute, $value, $fail) {
                    $str = ':attribute harus: ';
                    $msg = [];
                    if (strlen($value) < 8) {
                        $msg []= 'min 8 karakter';
                    }
                    if (!preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
                        $msg []= 'berisi setidaknya satu huruf besar dan satu huruf kecil';
                    }
                    if (!preg_match('/\pL/u', $value)) {
                        // $fail('harus berisi setidaknya satu huruf.');
                    }
                    // if (!preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)) {
                    //     $fail('harus mengandung setidaknya satu simbol.');
                    // }
                    if (!preg_match('/\pN/u', $value)) {
                        $msg []= 'mengandung setidaknya satu angka';
                    }
                    if (count($msg)) {
                        $fail($str.''.implode(', ', $msg));
                    }
                },
            ];
            $rules += [
                'password'              => [
                    'required',
                    'confirmed',
                    // ...$password_rules,
                ],
                'password_confirmation' => [
                    'required',
                    // ...$password_rules,
                ],
            ];
        }
        return $rules;
    }
}
