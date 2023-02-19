<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Comment\Status;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Comment as ModelsComment;
use Illuminate\Pagination\LengthAwarePaginator;

class Comment extends Controller
{

    /**
     * Получить новые комментарии
     */
    public function getNew() : LengthAwarePaginator
    {
        $sort = parent::validFieldSort(request(), ['created_at','commentable_type']);
        return ModelsComment::GetWithStatusForAdmin(Status::NEW)
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    /**
     * Получить измененые комментарии
     */
    public function getChanged() : LengthAwarePaginator
    {
        $sort = parent::validFieldSort(request(), ['created_at','commentable_type']);
        return ModelsComment::GetWithStatusForAdmin(Status::CHANGED)
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    /**
     * Получить отклоненые комментарии
     */
    public function getDeclined() : LengthAwarePaginator
    {
        $sort = parent::validFieldSort(request(), ['created_at','commentable_type']);
        return ModelsComment::GetWithStatusForAdmin(Status::DECLINE)
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    public function getCounts() : array
    {
        $newCount = ModelsComment::where('status', Status::NEW)->count();
        $changedCount = ModelsComment::where('status', Status::CHANGED)->count();
        $declinedCount = ModelsComment::where('status', Status::DECLINE)->count();
        return compact('newCount', 'changedCount', 'declinedCount' );
    }
    /**
     * Принять комментарий
     */
    public function accept(int $id) : JsonResponse
    {
        $comment = ModelsComment::findOrFail($id);
        $comment->status = Status::ACCEPT;
        $comment->save();
        return response()->json(['OK'],200);
    }

    /**
     * Отклонить комментарий
     */
    public function decline(int $id) : JsonResponse
    {
        $comment = ModelsComment::findOrFail($id);
        $comment->status = Status::DECLINE;
        $comment->save();
        return response()->json(['OK'],200);
    }

     /**
     * Удалить комментарий
     */
    public function destroy(int $id) : JsonResponse
    {
        ModelsComment::findOrFail($id)->delete();
        return response()->json(['OK'],200);
    }
}
