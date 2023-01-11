<?php

namespace App\Http\Requests\Film;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class Save extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
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

    protected function uniqueRule()
    {
        return Rule::unique('films');
    }
}
