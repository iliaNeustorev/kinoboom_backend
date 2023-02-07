<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password as FacadePassword;
use App\Http\Requests\Auth\ResetPassword as ResetPasswordRequest;

class Password extends Controller
{
    /**
     * Восстановить пароль
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = FacadePassword::sendResetLink(
            $request->only('email')
        );
    
        return $status === FacadePassword::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 400);
    }

    /**
     * Восстановить пароль
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $status = FacadePassword::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );
    
        return $status === FacadePassword::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 400);
    }
}
