<?php

namespace App\Http\Requests\Serial;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

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
        return Rule::unique('serials');
    }
}
