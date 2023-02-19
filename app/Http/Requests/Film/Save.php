<?php

namespace App\Http\Requests\Film;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class Save extends FormRequest
{
   
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'name' => ['required', 'min:3', 'max:30', $this->uniqueRule()],
            'slug' => ['required', 'min:3', 'max:64', $this->uniqueRule()],
            'description' => ['required', 'min:10','max:1000'],
            'video' => ['nullable', 'max:16'],
            'picture' => ['nullable','image'],
            'rating' => ['required','numeric'],
            'year_release' => ['required','digits:4']
        ];
    }

    protected function uniqueRule() : Unique
    {
        return Rule::unique('films');
    }
}
