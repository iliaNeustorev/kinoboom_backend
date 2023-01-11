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
    public function index() : mixed
    {
       $films = ModelsFilm::orderByDesc('created_at')->paginate(4);
       $this->getUrlPicture($films,"storage/img/films");
       return $films;
    }
    
     /**
     * Получить один фильм c одобреными комменатриями
     */
    public function show($slug) : mixed
    {
        return ModelsFilm::where('slug', $slug)
            ->with(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->withCount(['ratings' => ModelsRating::userAppreciated()])
            ->firstOrFail();
    }

}
