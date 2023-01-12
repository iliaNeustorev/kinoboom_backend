<?php

namespace App\Http\Controllers;

use App\Enums\Like\Status;
use App\Models\Like as ModelsLike;
use App\Models\Review as ModelsReview;
use App\Models\Comment as ModelsComment;
use App\Http\Requests\User\Like as UserLikeRequest;
use App\Http\Requests\User\DeleteLike as UserDeleteLikeRequest;
use App\Http\Requests\User\UpdateLike as UserLikeUpdateRequest;

class Like extends Controller
{
    const FOR_MODELS = [
        'comment' => ModelsComment::class,
        'review' => ModelsReview::class,
    ];

      /**
     * Добавить оценку like к FOR_MODELS
     */
    public function like(UserLikeRequest $request)
    {
        $modelName = self::FOR_MODELS[$request->for];
        $model = $modelName::findOrFail($request->id);
        $model->likes()->create([ 'status' => Status::LIKE, 'user_id'=> auth()->id() ]);
        return response()->json(['OK'], 200);
    }

     /**
     * Добавить оценку dislike к FOR_MODELS
     */
    public function dislike(UserLikeRequest $request)
    {
        $modelName = self::FOR_MODELS[$request->for];
        $model = $modelName::findOrFail($request->id);
        $model->likes()->create([ 'status' => Status::DISLIKE, 'user_id'=> auth()->id() ]);
        return response()->json(['OK'], 200);
    }

     /**
     * Поменять оценку 
     */
    public function update(UserLikeUpdateRequest $request, $id)
    {
       $like = ModelsLike::where('user_id', auth()->id())->where('likable_type', $request->for)->where('likable_id',$id)->firstOrFail();
        if($request->method == 'dislike' && $like->status == Status::LIKE){
            $like->update(['status' => Status::DISLIKE]);
        } 
        elseif($request->method == 'like' && $like->status == Status::DISLIKE)
        {
            $like->update(['status' => Status::LIKE]);
        }
        else
        {
            return response()->json(['error'=>'Уже поставлен'], 404);
        }
        return response()->json(['OK'], 200);
    }

     /**
     * Удалить оценку
     */
    public function cancelRate(UserDeleteLikeRequest $request, $id)
    {
       $rate = ModelsLike::where('user_id', auth()->id())->where('likable_type', $request->for)->where('likable_id',$id)->firstOrFail();
       $rate->delete();
       return response()->json(['OK'], 200);
    }
}
