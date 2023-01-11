<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Comment\Status;
use App\Http\Controllers\Controller;
use App\Models\Comment as ModelsComment;

class Comment extends Controller
{

    /**
     * Получить новые комментарии
     */
    public function getNew()
    {
        $sort = $this->validFieldSort(request(), ['created_at','commentable_type']);
        return ModelsComment::GetWithStatusForAdmin(Status::NEW)
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    /**
     * Получить измененые комментарии
     */
    public function getChanged()
    {
        $sort = $this->validFieldSort(request(), ['created_at','commentable_type']);
        return ModelsComment::GetWithStatusForAdmin(Status::CHANGED)
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    /**
     * Получить отклоненые комментарии
     */
    public function getDeclined()
    {
        $sort = $this->validFieldSort(request(), ['created_at','commentable_type']);
        return ModelsComment::GetWithStatusForAdmin(Status::DECLINE)
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    public function getCounts()
    {
        $newCount = ModelsComment::where('status', Status::NEW)->count();
        $changedCount = ModelsComment::where('status', Status::CHANGED)->count();
        $declinedCount = ModelsComment::where('status', Status::DECLINE)->count();
        return compact('newCount', 'changedCount', 'declinedCount' );
    }
    /**
     * Принять комментарий
     */
    public function accept($id)
    {
        $comment = ModelsComment::findOrFail($id);
        $comment->status = Status::ACCEPT;
        $comment->save();
        return response()->json(['OK'],200);
    }

    /**
     * Отклонить комментарий
     */
    public function decline($id)
    {
        $comment = ModelsComment::findOrFail($id);
        $comment->status = Status::DECLINE;
        $comment->save();
        return response()->json(['OK'],200);
    }

     /**
     * Удалить комментарий
     */
    public function destroy($id)
    {
        ModelsComment::findOrFail($id)->delete();
        return response()->json(['OK'],200);
    }
}
