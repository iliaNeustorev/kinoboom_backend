<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Enums\Like\Status as LikeStatus;
use App\Http\Requests\Auth\Login as LoginRequest;

class Login extends Controller
{
    /**
     * залогинится
     */
    public function login(LoginRequest $request) : array
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user()->toArray();
        $user['status'] = $request->user()->status->text();
        $user['urlPicture'] = url('storage/img/profile/' . $user['picture']);
        $user['likes'] = $request->user()->likes()->where('status', LikeStatus::LIKE)->pluck('likable_type','likable_id');
        $user['dislikes'] = $request->user()->likes()->where('status', LikeStatus::DISLIKE)->pluck('likable_type','likable_id');
        return $this->checkAdmin($user);
       
    }

      /**
     * разлогинится
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return response()->json(['status' => 200]);
    }
    
      /**
     * Получить юзера
     */
    public function getUser(Request $request)
    {
        $user = request()->user()->toArray();
        $user['status'] = $request->user()->status->text();
        $user['urlPicture'] = url('storage/img/profile/' . $user['picture']);
        $user['likes'] = $request->user()->likes()->where('status', LikeStatus::LIKE)->pluck('likable_type','likable_id');
        $user['dislikes'] = $request->user()->likes()->where('status', LikeStatus::DISLIKE)->pluck('likable_type','likable_id');
        return $this->checkAdmin($user);
    }

     /**
     * Проверить пользователья на права админа
     */
    private function checkAdmin(array $user) : array
    {
        $user['admin'] = Gate::check('admin');
        $user['adminMain'] = Gate::check('admin-main');
        $user['moderator'] = Gate::check('admin-moderator');
        return $user;
    }
}
    

