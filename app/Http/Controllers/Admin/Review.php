<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Review\Status;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Review as ModelsReview;
use Illuminate\Pagination\LengthAwarePaginator;

class Review extends Controller
{
    /**
     * Получить новые отзывы
     */
    public function index() : LengthAwarePaginator
    {
        return ModelsReview::getNew()->orderBy('created_at','desc')->paginate(5);
    }

    /**
     * Одобрить отзыв
     */
    public function accept(int $id) : JsonResponse
    {
        $review = ModelsReview::findOrFail($id);
        $review->status = Status::ACCEPT;
        $review->save();
        return response()->json(['OK'], 200);
    }

    /**
     * Отклонить отзыв
     */
    public function decline(int $id) : JsonResponse
    {
        $review = ModelsReview::findOrFail($id);
        $review->status = Status::DECLINE;
        $review->save();
        return response()->json(['OK'], 200);
    }

    public function delete(int $id) : JsonResponse
    {
        ModelsReview::findOrFail($id)->delete();
        return response()->json(['OK'], 200);
    }
}
