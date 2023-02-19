<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAvatar extends FormRequest
{
    
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
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
