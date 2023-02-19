<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLike extends FormRequest
{
    
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'for' => 'in:comment,review',
            'method' =>'in:like,dislike'
        ];
    }
}
