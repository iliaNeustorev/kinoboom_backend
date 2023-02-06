<?php

namespace App\Http\Controllers;

use App\Models\Review as ModelsReview;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\User\Review as UserReview;

class Review extends Controller
{
    public function index()
    {
       return ModelsReview::getAccept()->paginate(5);
    }

    public function store(UserReview $request)
    {
        $user = $request->user();
        if($user->review == null){
            $data = new ModelsReview($request->validated());
            $user->review()->save($data);
            return true;
        };
        return  throw ValidationException::withMessages([
            'review' => 'Вы уже оставляли отзыв',
        ]);
    }
}
