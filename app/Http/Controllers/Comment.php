<?php

namespace App\Http\Controllers;

use App\Enums\Comment\Status;
use App\Models\Film as ModelsFilm;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Gate;
use App\Models\Serial as ModelsSerial;
use App\Models\Comment as ModelsComment;
use App\Http\Requests\Comment\Store as StoreRequest;
use App\Http\Requests\Comment\Update as UpdateRequest;

class Comment extends Controller
{
    const FOR_MODELS = [
        'film' => ModelsFilm::class,
        'serial' => ModelsSerial::class
    ];

    /**
     * Сохранить сообщение
     */
    public function store(StoreRequest $request)
    {
        $user = auth()->id() ?? ModelsUser::where('name','Guest')->firstOrfail()->id;
        
        if($request->for != null){
            $modelName = self::FOR_MODELS[$request->for];
        }
        else
        {
            if(ModelsFilm::where('slug',$request->slug)->count() != 0)
            {
                $modelName = self::FOR_MODELS['film'];
            } 
            elseif(ModelsSerial::where('slug',$request->slug)->count() != 0)
            {
                $modelName = self::FOR_MODELS['serial'];
            }
        }
       
        $model = $modelName::where('slug',$request->slug)->firstOrfail();
        $model->comments()->create($request->only(['text']) + ['user_id' => $user]);
        
        return response()->json(['коментарий отправлен'], 200);
    }

    /**
     * Изменить сообщение
     */
    public function update(UpdateRequest $request, $id)
    {
        $comment = ModelsComment::findOrFail($id);

        if(!Gate::allows('comment-timeout', [ $comment, 4 ]))
        {
            return response()->json(['errors' => 'Время изменения истекло'], 403);
        }
        
        $comment->update($request->validated() + [ 'status' => Status::CHANGED ]);
        return response()->json(['OK'], 200);
    }

    /**
     * Удалить сообщение
     */
    public function destroy($id)
    {
        $comment =  ModelsComment::findOrfail($id);

        if (!Gate::allows('comment-update', $comment)) 
        {
            return response()->json(['errors' => 'Нет прав доступа'], 403);
        }
        elseif(!Gate::allows('comment-timeout', [ $comment, 24 ]))
        {
            return response()->json(['errors' => 'Время удаления истекло'], 403);
        }
        else{
            $comment->delete();
            return response()->json(['OK'], 200);
        }
    }
}
