<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
{
   
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'current' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function attributes() : array
    {
        return [
            'current' => 'текущий пароль',
            'password' => 'новый пароль',
        ];
    }
}
