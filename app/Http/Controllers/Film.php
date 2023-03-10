<?php

namespace App\Http\Controllers;

use App\Models\Film as ModelsFilm;
use App\Models\Rating as ModelsRating;
use App\Models\Comment as ModelsComment;
use App\Enums\Comment\Status as CommentStatus;


class Film extends Controller
{

     /**
     * Получить все фильмы с url адресом картинки
     */
    public function index() : object
    {
        $films = ModelsFilm::orderByDesc('created_at')
            ->withCount(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->paginate(4);
        parent::setUrlPicture($films,"storage/img/films");
        return $films;
    }
   
     /**
     * Получить один фильм c одобреными комменатриями
     */
    public function show(string $slug) : ModelsFilm
    {
        return ModelsFilm::where('slug', $slug)
                ->with(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
                ->withCount(['ratings' => ModelsRating::userAppreciated()])
                ->firstOrFail();
    }

}
