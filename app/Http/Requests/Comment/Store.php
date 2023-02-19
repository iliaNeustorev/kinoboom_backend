<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }
    
    public function rules() : array
    {
        return [
            'for' =>  'in:film,serial|nullable',
            'slug' => 'required',
            'text' => 'required|max:256'
        ];
    }
}
