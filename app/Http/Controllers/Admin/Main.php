<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Role as ModelsRole;
use App\Models\User as ModelsUser;
use Illuminate\Foundation\Auth\User;
use App\Http\Controllers\Controller;
use App\Enums\User\Block as StatusBlock;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Admin\Role as RoleRequest;

class Main extends Controller
{
    /**
     * Получить всех юзеров
     */
    public function users() : LengthAwarePaginator
    {
        $sort = parent::validFieldSort(request(), ['created_at','name','email','blocked']);
        return ModelsUser::with('roles:id,description')->orderBy($sort['column'], $sort['direction'])->paginate(10);
    }

    /**
     * Получить получить роли юзеров и все роли
     */
    public function getRoles(int $id) : array
    {
        $AllRoles = ModelsRole::get()->toArray();
        $user = ModelsUser::with('roles:id')->where('id',$id)->get()->pluck('roles')->flatten();
        $userRoles = $user->map(function ($item) {
            return $item->id;
        });
        return compact('userRoles','AllRoles');
    }

    /**
     * Обновить роль пользователя
     */
    public function updateRole(RoleRequest $request,int $id) : JsonResponse
    {
        $user = ModelsUser::findOrFail($id);
        $user->roles()->sync($request['roles']);
        return response()->json(['OK'], 200);
    }

     /**
     * Заблокировать пользователя
     */
    public function blockedUser(Request $request,int $id) : JsonResponse
    {
       $data = $request->validate([
            'check' => 'required|boolean',
        ]);
        $user = User::findOrFail($id);
        $user->blocked = $data['check'] ? StatusBlock::BLOCK : StatusBlock::UNBLOCK;
        $user->save();
        return response()->json(['OK'], 200);
    }   
}
