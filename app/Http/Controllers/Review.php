<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Review as ModelsReview;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\User\Review as UserReview;
use Illuminate\Pagination\LengthAwarePaginator;

class Review extends Controller
{
    public function index() : LengthAwarePaginator
    {
       return ModelsReview::getAccept()->paginate(5);
    }

    public function store(UserReview $request) : JsonResponse
    {
        $user = $request->user();
        if($user->review == null){
            $data = new ModelsReview($request->validated());
            $user->review()->save($data);
            return response()->json(['OK'], 200);
        };
        return throw ValidationException::withMessages([
            'review' => 'Вы уже оставляли отзыв',
        ]);
    }
}
