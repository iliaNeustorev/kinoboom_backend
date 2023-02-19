<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Controller;
use App\Enums\User\Status as UserStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class Verification extends Controller
{
    /**
     * Сделать адрес юзера верефицированым
     */
    public function verify(EmailVerificationRequest $request) : JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => trans('verification.already_verified'),
            ], 400);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            $request->user()->status = UserStatus::VERIFIED;
            $request->user()->save();
        }

        return response()->json([
            'status' => trans('verification.verified'),
        ]);
    }

    /**
     * Повторно отправить сообщение на email.
     */
    public function sendVerificationEmail(Request $request) : JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [ trans('verification.already_verified') ],
            ]);
        }
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => trans('verification.sent')]);
    }
}
