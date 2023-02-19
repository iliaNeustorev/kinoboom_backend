<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\User\Profile as UserNameChangeRequest;
use App\Http\Requests\User\ChangeAvatar as ChangeAvatarRequest;
use App\Http\Requests\User\ChangePassword as ChangePasswordRequest;

class Profile extends Controller
{

    /**
     * Изменить имя пользователя
     */
    public function changeName(UserNameChangeRequest $request) : JsonResponse
    {
        $user = request()->user();
        $newName = $request->validated();
        $user->update($newName);
        return response()->json(['OK'], 200);
    }

    /**
     * Изменить пароль
     */
    public function changePassword(ChangePasswordRequest $request) : JsonResponse
    {
        $request->user()->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60)
        ])->save();

        Auth::logout();
        return response()->json(['OK'], 200);
    }

    /**
     * Изменить аватар пользователя
     */
    public function changeAvatar(ChangeAvatarRequest $request) : JsonResponse
    {
        $user = request()->user();
        $file = $request->picture;
        if($user->picture != 'nopicture.png')
            {
                Storage::delete("public/img/profile/$user->picture");
            }
        $fileName = $request->setPictureName($file);
        $user->update(['picture' => $fileName]);
        Storage::putFileAs('public/img/profile/', $file, $fileName);
        return response()->json(['OK'], 200);
    }

    /**
     * Удалить аватар пользователя
     */
    public function deleteAvatar(Request $request) : JsonResponse
    {
        $user = $request->user();
        if($user->picture != 'nopicture.png')
        {
            Storage::delete("public/img/profile/$user->picture");
        }
        $user->forceFill([
            'picture' => 'nopicture.png',
        ])->save();
        return response()->json(['OK'], 200);
    }
}
