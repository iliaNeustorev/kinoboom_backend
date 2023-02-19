<?php

namespace App\Http\Controllers;

use App\Models\Rating as ModelsRating;
use App\Models\Serial as ModelsSerial;
use App\Models\Comment as ModelsComment;
use App\Enums\Comment\Status as CommentStatus;

class Serial extends Controller
{

     /**
     * Получить все сериалы с url адресом картинки
     */
    public function index() : object
    {
        $serials = ModelsSerial::orderByDesc('created_at')
            ->withCount(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->paginate(4);
        parent::setUrlPicture($serials,"storage/img/serials");
        return $serials;
    }

     /**
     * Получить один сериал с одобреными комменатриями
     */
    public function show(string $slug) : ModelsSerial
    {
        return ModelsSerial::where('slug', $slug)
            ->with(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->withCount(['ratings' => ModelsRating::userAppreciated()])
            ->firstOrFail();
    }
}
