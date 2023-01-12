<?php

namespace App\Http\Controllers;

use App\Models\Film as ModelsFilm;
use App\Models\Serial as ModelsSerial;
use App\Models\Rating as ModelsRating;
use App\Models\Comment as ModelsComment;
use App\Enums\Comment\Status as CommentStatus;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Home extends Controller
{
    const FOR_MODELS = [
        'film' => ModelsFilm::class,
        'serial' => ModelsSerial::class
    ];

     /**
     * Получить один фильм или сериал c одобреными коментариями по slug
     */
    public function getOneElement($slug)
    {
        $film = ModelsFilm::where('slug', $slug)
            ->with(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->withCount(['ratings' => ModelsRating::userAppreciated()])
            ->first();
        $serial = ModelsSerial::where('slug', $slug)
            ->with(['comments' => ModelsComment::getWithStatus(CommentStatus::ACCEPT)])
            ->withCount(['ratings' => ModelsRating::userAppreciated()])
            ->first();
        if($film != null){
            return $film;
        } elseif ($serial != null){
            return $serial;
        } else {
            return response()->json(['error'=>'элемент не найден'], 400);
        }
    }

     /**
     * Сформировать таблицу сериалов и фильмов по рейтингу и вывести первые 5
     */
    public function ratingOnHomePage()
    {
        $serial = ModelsSerial::get()->mapWithKeys(function ($item, $key) {
            return [ $key => ['name' => $item->name, 'rating' => $item->rating,'slug' => $item->slug ]];
        });
        $film = ModelsFilm::get()->mapWithKeys(function ($item, $key) {
            return [ $key => ['name' => $item->name, 'rating' => $item->rating,'slug' => $item->slug ]];
        });
        return $film->merge($serial)->sortbyDesc('rating')->values()->take(5);
    }
    
    /**
     * Сформировать таблицу сериалов и фильмов по рейтингу и вывести по 10
     */
    public function rating()
    {

        $sort = $this->validFieldSort(request(), ['rating','year_release','name']);
        return ModelsFilm::unionSerials()->orderBy($sort['column'], $sort['direction'])->paginate(10);
    }

     /**
     * Увеличить рейтинг фильма или сериала на 0,01
     */
    public function increaseRating($slug)
    {
        return $this->changeRating($slug, 'increase'); 
    }

    /**
     * Уменишить рейтинг фильма или сериала на 0,01
     */
    public function decreaseRating($slug)
    {
        return $this->changeRating($slug, 'decrease');
    }

     /**
     *Вспомогательная функция. Поиск элемента по slug и изменение его рейтинга в зависимости от метода
     */
    protected function changeRating($slug, $method)
    {
       $film = ModelsFilm::where('slug', $slug)
        ->withCount(['ratings' => ModelsRating::userAppreciated()])
        ->first();
       $serial = ModelsSerial::where('slug', $slug)
        ->withCount(['ratings' => ModelsRating::userAppreciated()])
        ->first();
       if($film != null && $film->ratings_count == 0){
           $this->changeIncreaseOrdecrease($film, $method);
        } elseif ($serial != null && $serial->ratings_count == 0){
           $this->changeIncreaseOrdecrease($serial, $method);
        } else {
            return response()->json(['error' => 'Рейтинг поставлен'], 400);
        }
        return response()->json(['OK'], 200);
    }

    /**
     *Вспомогательная функция. Увеличить или уменьшить рейтинг
     */
    protected function changeIncreaseOrdecrease($model, $method)
    {
        $model->ratings()->create(['user_id' => auth()->id(), 'appreciated' => true]);
        switch ($method) {
            case 'increase':
                $model->rating = $model->rating >= 100 ? 100 : $model->rating += 0.01;
                break;
            case 'decrease':
                $model->rating = $model->rating == 0 ? 0 : $model->rating -= 0.01;
                break;
        }
       return $model->save();
    }

     /**
     *Функция для поиска фильма иили сериала используя Scout/Algolia
     */
    public function search()
    {
        $search = request()->search;
        if($search == null){
            return response('Пустая строка', 422);
        }
        $films = ModelsFilm::search($search)->get();
        $serials = ModelsSerial::search($search)->get();
        $collection = $films->concat($serials)->toArray();
        $total = count($collection);
        $per_page = 10;
        $current_page = request()->page ?? 1;

        $starting_point = ($current_page * $per_page) - $per_page;

        $array = array_slice($collection, $starting_point, $per_page, true);
        $result= new Paginator($array, $total, $per_page, $current_page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
        return $result;
    }
}
