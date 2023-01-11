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
    public function index() : mixed
    {
        $serials = ModelsSerial::orderByDesc('created_at')->paginate(4);
        $this->getUrlPicture($serials,"storage/img/serials");
        return $serials;
    }

     /**
     * Получить один сериал с одобреными комменатриями
     */
    public function show($slug) : mixed
    {
        return ModelsSerial::where('slug', $slug)
            ->with(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->withCount(['ratings' => ModelsRating::userAppreciated()])
            ->firstOrFail();
    }
}
