<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Film as ModelsFilm;
use App\Http\Controllers\Controller;
use App\Models\Serial as ModelsSerial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class Trash extends Controller
{
    /**
     * Получить удаленные фильмы
     */
    public function indexFilms() : LengthAwarePaginator
    {
        $sort = parent::validFieldSort(request(), ['rating','year_release','name','slug','deleted_at']);
        return ModelsFilm::onlyTrashed()
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

    /**
     * Получить удаленные сериалы
     */
    public function indexSerials() : LengthAwarePaginator
    {
        $sort = parent::validFieldSort(request(), ['rating','year_release','name','slug','deleted_at']);
        return ModelsSerial::onlyTrashed()
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate(5);
    }

     /**
     * Удалить навсегда один фильм из БД по id
     */
    public function deleteForeverFilm(int $id) : JsonResponse
    {
        ModelsFilm::onlyTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['OK'], 200);
    }

     /**
     * Удалить навсегда один сериал из БД по id
     */
    public function deleteForeverSerial(int $id) : JsonResponse
    {
        ModelsSerial::onlyTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['OK'], 200);
    }

     /**
     * Восстановить один фильм из БД по id
     */
    public function restoreOneFilm(Request $request) : JsonResponse
    {
        ModelsFilm::onlyTrashed()->findOrFail($request->id)->restore();
        return response()->json(['OK'], 200);
    }

     /**
     * Восстановить один сериал из БД по id
     */
    public function restoreOneSerial(Request $request) : JsonResponse
    {
        ModelsSerial::onlyTrashed()->findOrFail($request->id)->restore();
        return response()->json(['OK'], 200);
    }

     /**
     * Восстановить все удаленные фильмы
     */
    public function restoreAllFilms() : JsonResponse
    {
        ModelsFilm::onlyTrashed()->restore();
        return response()->json(['OK'], 200);
    }

     /**
     * Восстановить все удаленные сериалы
     */
    public function restoreAllSerials() : JsonResponse
    {
        ModelsSerial::onlyTrashed()->restore();
        return response()->json(['OK'], 200);
    }

     /**
     * Удалить навсегда удаленные фильмы из БД
     */
    public function deleteAllFilms() : JsonResponse
    {
        ModelsFilm::onlyTrashed()->forceDelete();
        return response()->json(['OK'], 200);
    }

     /**
     * Удалить навсегда удаленные сериалы из БД
     */
    public function deleteAllSerials() : JsonResponse
    {
        ModelsSerial::onlyTrashed()->forceDelete();
        return response()->json(['OK'], 200);
    }
    
}
