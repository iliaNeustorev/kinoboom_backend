<?php

namespace App\Http\Requests\Comment;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment as ModelsComment;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    
    /*
        Проверка прав доступа пользователя на редактирования коммента
    */
    public function authorize() : Response
    {
        return Gate::allows('comment-update', ModelsComment::findOrfail(request()->id)) 
        ? Response::allow()
        : Response::deny('Нет прав');
    }

    public function rules() : array
    {
        return [
            'text' => 'required|max:256'
        ];
    }
}
