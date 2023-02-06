<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role as ModelsRole;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Enums\Roles\Status as RoleStatus;
use App\Http\Requests\Auth\Register as RegisterRequest;

class Register extends Controller
{
    /**
     * Зарегистировать пользователя
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['picture'] = 'nopicture.png';
        $user = ModelsUser::create($data);
        event(new Registered($user));
        $roleIdUser = ModelsRole::where('name',RoleStatus::USER)->firstOrfail()->id;
        $user->roles()->sync($roleIdUser);

        Auth::login($user);
        return response()->json(['status' => 200]);
    }
}
