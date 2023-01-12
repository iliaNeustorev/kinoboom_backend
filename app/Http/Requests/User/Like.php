<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class Like extends FormRequest
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
            'for' => 'in:comment,review',
            'id' => ['required', 'integer',Rule::unique('likes','likable_id')->where(function ($query) {
                return $query->where('user_id', auth()->id());
            })],
        ];
    }
}
