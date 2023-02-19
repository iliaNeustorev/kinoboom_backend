<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class Like extends FormRequest
{
   
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'for' => 'in:comment,review',
            'id' => ['required', 'integer',Rule::unique('likes','likable_id')->where(function ($query) {
                return $query->where('user_id', auth()->id());
            })],
        ];
    }
}
