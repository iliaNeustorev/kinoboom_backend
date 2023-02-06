<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Review\Status;
use App\Http\Controllers\Controller;
use App\Models\Review as ModelsReview;

class Review extends Controller
{
    /**
     * Получить новые отзывы
     */
    public function index() : object
    {
        return ModelsReview::getNew()->orderBy('created_at','desc')->paginate(5);
    }

    /**
     * Одобрить отзыв
     */
    public function accept(int $id)
    {
        $review = ModelsReview::findOrFail($id);
        $review->status = Status::ACCEPT;
        $review->save();
        return response()->json(['OK'],200);
    }

    /**
     * Отклонить отзыв
     */
    public function decline(int $id)
    {
        $review = ModelsReview::findOrFail($id);
        $review->status = Status::DECLINE;
        $review->save();
        return response()->json(['OK'],200);
    }

    public function delete(int $id)
    {
        ModelsReview::findOrFail($id)->delete();
        return response()->json(['OK'],200);
    }
}
