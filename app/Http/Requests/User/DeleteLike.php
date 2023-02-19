<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DeleteLike extends FormRequest
{
    
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'for' => 'in:comment,review',
        ];
    }
}
