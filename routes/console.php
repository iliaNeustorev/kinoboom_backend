<?php

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test', function(Request $request){
    $user = User::findOrFail(1);
    $token = $user->createToken($user->name)->plainTextToken;
    dd($token);
    //  Film::create([//создание модели
    //      'name' => 'Фильм4',
    //      'slug' => 'fimls',
    //      'description' => 'Описание фильма 4',
    //      'video' => '1312adasda',
    //      'picture' => '2.jpeg',
    //      'year_release' => 2008,
    //      'rating' => 7.1
    //  ]);
 
});
