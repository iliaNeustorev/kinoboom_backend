<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Review extends FormRequest
{
    
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'review' => 'required|min:5'
        ];
    }
}
