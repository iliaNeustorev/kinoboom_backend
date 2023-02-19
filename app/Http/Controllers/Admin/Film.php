<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Models\Film as ModelsFilm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Film\Save as SaveRequest;
use App\Http\Requests\Film\Update as UpdateRequest;
use App\Http\Requests\Admin\MassDelete as MassDeleteRequest;

class Film extends Controller
{
    /**
     * Вернуть коллекцию моделей Film и количество и удаленных
     */
    public function index() : array
    {
        $sort = parent::validFieldSort(request(), ['rating','year_release','name','slug','created_at']);
        $films = ModelsFilm::orderBy($sort['column'], $sort['direction'])->paginate(5);
        parent::getUrlPicture($films,"storage/img/films");
        $count = ModelsFilm::onlyTrashed()->count();
        return compact('films','count');
    }

    /**
     * Создать модель Film
     */
    public function store(SaveRequest $request) : JsonResponse
    {
        $data = $request->validated();
        
        if($request->picture != null)
            {
                $file = $request->picture;
                $ext = $file->extension();
                $fileName = time(). mt_rand(1000, 9999) . '.' . $ext;
                Storage::putFileAs('public/img/films/', $file, $fileName);
                $data['picture'] = $fileName;
            } else {
                $data['picture'] = 'nopicture.png';
            }

        ModelsFilm::create($data);
        return response()->json(['ОК'],200);
    }

    /**
     * Вернуть одну модель Film по id
     */
    public function show(int $id) : object
    {
        $film = ModelsFilm::where('id', $id)->get();
        if($film->isEmpty())
        {
           return response()->json(['error'=>'элемент не найден'],404);
        } 
        else 
        {
         parent::getUrlPicture($film,"storage/img/films");
         return $film->shift();
        }
    }

    /**
     * Обновить модель Film
     */
    public function update(UpdateRequest $request,int $id) : JsonResponse
    {
        $data = $request->validated();
        $film = ModelsFilm::findOrFail($id);
        if($request->picture != null)
            {
                if($film->picture != 'nopicture.png')
                {
                    Storage::delete("public/img/films/$film->picture");
                }
                $file = $request->picture;
                $ext = $file->extension();
                $fileName = time(). mt_rand(1000, 9999) . '.' . $ext;
                Storage::putFileAs('public/img/films/', $file, $fileName);
                $data['picture'] = $fileName;
            }
            else
            { 
                $data['picture'] = $film->picture;
            }
        $film->update($data);
        return response()->json(['ОК'], 200);
    }

    /**
     * Удалить одну модель Film
     */
    public function destroy(int $id) : JsonResponse
    {
        ModelsFilm::findOrFail($id)->delete();
        return response()->json(['ОК'], 200);
    }

    /**
     * Удалить несколько моделей Film
     */
    public function massDestroy(MassDeleteRequest $request) : JsonResponse
    {
        $data = $request->validated();
        ModelsFilm::destroy($data['idForDelete']);
        return response()->json(['ОК'], 200);
    }
}
