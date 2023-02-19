<?php

namespace App\Http\Requests\Admin;

use App\Rules\CheckArray;
use App\Models\Role as ModelsRole;
use Illuminate\Foundation\Http\FormRequest;

class Role extends FormRequest
{
   
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'roles' => ['required', 'array','min:1', new CheckArray(ModelsRole::class)]
        ];
    }

    public function messages() : array
    {
        return [
            'roles.required' => 'Выберите хотя бы одну роль'
        ];
    }
}
