<?php

namespace App\Http\Requests\Comment;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment as ModelsComment;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('comment-update', ModelsComment::findOrfail(request()->id)) 
        ? Response::allow()
        : Response::deny('Нет прав');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => 'required|max:256'
        ];
    }
}
