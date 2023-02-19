<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Serial as ModelsSerial;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Serial\Save as SaveRequest;
use App\Http\Requests\Serial\Update as UpdateRequest;
use App\Http\Requests\Admin\MassDelete as MassDeleteRequest;
use Illuminate\Http\JsonResponse;

class Serial extends Controller
{
   /**
     * Вернуть коллекцию моделей Serial и количество удаленных
     */
    public function index() : array
    {
        $sort = parent::validFieldSort(request(), ['rating','year_release','name','slug','created_at']);
        $serials = ModelsSerial::orderBy($sort['column'], $sort['direction'])->paginate(5);
        parent::getUrlPicture($serials,"storage/img/serials");
        $count = ModelsSerial::onlyTrashed()->count();
        return compact('serials','count');
    }

    /**
     * Создать одну модель Serial
     */
    public function store(SaveRequest $request) : JsonResponse
    {
        $data = $request->validated();
        
        if($request->picture != null)
            {
                $file = $request->picture;
                $ext = $file->extension();
                $fileName = time(). mt_rand(1000, 9999) . '.' . $ext;
                Storage::putFileAs('public/img/serials/', $file, $fileName);
                $data['picture'] = $fileName;
            } else {
                $data['picture'] = 'nopicture.png';
            }

        ModelsSerial::create($data);
        return response()->json(['ОК'], 200);
    }

    /**
     * Вернуть одну модель Serial по id
     */
    public function show(int $id) : object
    {
        $serial = ModelsSerial::where('id', $id)->get();
        if($serial->isEmpty())
        {
           return response()->json(['error'=>'элемент не найден'],404);
        } 
        else 
        {
         parent::getUrlPicture($serial,"storage/img/serials");
         return $serial->shift();
        }
    }

    /**
     * Обновить одну модель Serial
     */
    public function update(UpdateRequest $request,int $id) : JsonResponse
    {
        $data = $request->validated();
        $serial = ModelsSerial::findOrFail($id);
        if($request->picture != null)
            {
                if($serial->picture != 'nopicture.png')
                {
                    Storage::delete("public/img/serials/$serial->picture");
                }
                $file = $request->picture;
                $ext = $file->extension();
                $fileName = time(). mt_rand(1000, 9999) . '.' . $ext;
                Storage::putFileAs('public/img/serials/', $file, $fileName);
                $data['picture'] = $fileName;
            }
            else
            { 
                $data['picture'] = $serial->picture; 
            }
        $serial->update($data);
        return response()->json(['ОК'], 200);
    }

    /**
     * Удалить одну модель Serial
     */
    public function destroy(int $id) : JsonResponse
    {
        ModelsSerial::findOrFail($id)->delete();
        return response()->json(['ОК'], 200);
    }

    /**
     * Удалить несколько моделей Serial
     */
    public function massDestroy(MassDeleteRequest $request) : JsonResponse
    {
        $data = $request->validated();
        ModelsSerial::destroy($data['idForDelete']);
        return response()->json(['ОК'], 200);
    }
}
