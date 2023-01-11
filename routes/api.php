<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Film as FilmController;
use App\Http\Controllers\Home as HomeController;
use App\Http\Controllers\Like as LikeController;
use App\Http\Controllers\Serial as SerialController;
use App\Http\Controllers\Review as ReviewController;
use App\Http\Controllers\Profile as ProfileContorller;
use App\Http\Controllers\Comment as CommentController;
use App\Http\Controllers\Auth\Login as LoginController;
use App\Http\Controllers\Admin\Main as AdminMainController;
use App\Http\Controllers\Admin\Film as AdminFilmController;
use App\Http\Controllers\Auth\Password as PasswordContoller;
use App\Http\Controllers\Auth\Register as RegisterController;
use App\Http\Controllers\Admin\Trash as AdminTrashController;
use App\Http\Controllers\Admin\Review as AdminReviewController;
use App\Http\Controllers\Admin\Serial as AdminSerialController;
use App\Http\Controllers\Admin\Comment as AdminCommentController;
use App\Http\Controllers\Auth\Verification as VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', [ LoginController::class, 'getUser' ]);

Route::middleware('auth:sanctum')->group( function() {
    Route::get('verify-email/{id}/{hash}', [ VerificationController::class, 'verify' ])->name('verification.verify');
    Route::post('email/verification-notification', [ VerificationController::class, 'sendVerificationEmail' ])->middleware('throttle:6,1');
});

Route::group(['middleware' => ['web']], function () {
    Route::middleware('guest')->group(function(){
        Route::post('/login',[ LoginController::class, 'login' ]);
        Route::post('/register',[ RegisterController::class, 'register' ]);
        Route::post('/forgot-password', [ PasswordContoller::class, 'forgotPassword' ]);
        Route::post('/reset-password', [ PasswordContoller::class, 'resetPassword']);
    });
    Route::post('/logout',[ LoginController::class, 'logout' ])->middleware('auth');   
});

Route::prefix('/profile')->middleware('auth')->group( function () {
    Route::put('/changeName', [ ProfileContorller::class, 'changeName' ]);
    Route::put('/changePassword', [ ProfileContorller::class, 'changePassword']);
    Route::put('/changeAvatar', [ ProfileContorller::class, 'changeAvatar']);
    Route::put('/deleteAvatar', [ ProfileContorller::class, 'deleteAvatar']);
});

Route::prefix('/admin')->middleware(['auth','verified','can:admin'])->group(function(){
    Route::middleware('can:admin-main')->group(function(){
        Route::post('/serials/massDestroy', [ AdminSerialController::class, 'massDestroy' ]);
        Route::post('/films/massDestroy', [ AdminFilmController::class, 'massDestroy' ]);
        Route::apiResource('/films', AdminFilmController::class)->parameters([ 'films' => 'id' ]);
        Route::apiResource('/serials', AdminSerialController::class)->parameters([ 'serials' => 'id' ]);
            Route::prefix('/review')->group( function () {
                Route::get('/getNew', [ AdminReviewController::class, 'index' ]);
                Route::put('/accept/{id}', [ AdminReviewController::class, 'accept' ]);
                Route::put('/decline/{id}', [ AdminReviewController::class, 'decline' ]);
            });
    
        Route::prefix('/users')->group( function () {
            Route::get('/', [ AdminMainController::class, 'users' ]);
            Route::get('/role/{id}/get', [ AdminMainController::class, 'getRoles' ]);
            Route::put('/role/{id}/update', [ AdminMainController::class, 'updateRole']);
            Route::put('/user/{id}/blocked', [ AdminMainController::class, 'blockedUser']);
        });

        Route::prefix('/trash')->group( function () {

            Route::prefix('/film')->group( function () {
                Route::get('/', [ AdminTrashController::class, 'indexFilms' ]);
                Route::delete('/{id}', [ AdminTrashController::class, 'deleteForeverFilm' ]);
                Route::put('/restoreOne', [ AdminTrashController::class, 'restoreOneFilm']);
                Route::put('/restoreAll', [ AdminTrashController::class, 'restoreAllFilms']);
                Route::post('/deleteAll', [ AdminTrashController::class, 'deleteAllFilms']);
            });

            Route::prefix('/serial')->group( function () {
                Route::get('/', [ AdminTrashController::class, 'indexSerials' ]);
                Route::delete('/{id}', [ AdminTrashController::class, 'deleteForeverSerial' ]);
                Route::put('/restoreOne', [ AdminTrashController::class, 'restoreOneSerial']);
                Route::put('/restoreAll', [ AdminTrashController::class, 'restoreAllSerials']);
                Route::post('/deleteAll', [ AdminTrashController::class, 'deleteAllSerials']);
            });
           
        });
    });
    Route::middleware('can:admin-moderator')->group( function () {

        Route::prefix('/comment')->group( function () {
            Route::get('/getNew', [ AdminCommentController::class, 'getNew' ]);
            Route::get('/getChanged', [ AdminCommentController::class, 'getChanged' ]);
            Route::get('/getDeclined', [ AdminCommentController::class, 'getDeclined' ]);
            Route::get('/getCounts', [ AdminCommentController::class, 'getCounts' ]);
            Route::put('/accept/{id}', [ AdminCommentController::class, 'accept' ]);
            Route::put('/decline/{id}', [ AdminCommentController::class, 'decline' ]);
            Route::delete('/destroy/{id}', [ AdminCommentController::class, 'destroy' ]);
        });

    });
});


Route::resource('/comments', CommentController::class)->only(['store', 'update', 'destroy'])->parameters([ 'comments' => 'id' ]);

Route::prefix('/home')->group( function () {
    Route::get('/rating', [ HomeController::class, 'ratingOnHomePage' ]);
    Route::get('/ratingPage', [ HomeController::class, 'rating' ]);
    Route::get('/oneElement/{slug}',[ HomeController::class, 'getOneElement']);
    Route::put('/rating/increase/{slug}',[ HomeController::class, 'increaseRating'])->middleware('auth');
    Route::put('/rating/decrease/{slug}',[ HomeController::class, 'decreaseRating'])->middleware('auth');
});

Route::prefix('/film')->group( function () {
    Route::get('/all', [ FilmController::class, 'index' ]);
    Route::get('/{slug}/show', [ FilmController::class, 'show' ]);
});
Route::prefix('/serial')->group( function () {
    Route::get('/all', [ SerialController::class, 'index' ]);
    Route::get('/{slug}/show', [ SerialController::class, 'show' ]);
});
Route::prefix('/review')->group( function () {
    Route::get('/all', [ ReviewController::class, 'index' ]);
    Route::post('/store', [ ReviewController::class, 'store' ])->middleware('auth', 'verified');
});

Route::middleware('auth')->group( function () {
Route::post('/like', [ LikeController::class, 'like' ]);
Route::post('/dislike', [ LikeController::class, 'dislike' ]);
Route::put('/rate/update/{id}', [ LikeController::class, 'update' ]);
Route::delete('/cancel/rate/{id}', [ LikeController::class, 'cancelRate' ]);
});

