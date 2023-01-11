<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAvatar extends FormRequest
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
            'picture' => ['required', 'image']
        ];
    }

     /**
     * Установить имя картинки
     */
    public function setPictureName(object $file) : string
    {
        $ext = $file->extension();
        return mb_strtolower(auth()->user()->name . mt_rand(10, 999) . '.' . $ext);
    }
}
