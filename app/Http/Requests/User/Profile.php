<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Profile extends FormRequest
{
   
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'name' =>  ['required', 'string','min:5', 'max:255'],
        ];
    }
}
