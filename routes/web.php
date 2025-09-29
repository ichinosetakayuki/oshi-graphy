<?php

use App\Http\Controllers\AiDiaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryPublicController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DiaryLikeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/me/profile/info', [ProfileController::class, 'updateProfileInfo'])->name('profile.update.info');

    Route::get('/users/{user}',[UserProfileController::class, 'show'])
        ->whereNumber('user')->name('user.profile.show');
    Route::get('/user_profile/edit',[UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::patch('/user_profile',[UserProfileController::class, 'update'])->name('user.profile.update');

    Route::resource('diaries', DiaryController::class);
    Route::get('/public_diaries', [DiaryPublicController::class, 'index'])->name('public.diaries.index');
    Route::get('/public_diaries/{diary}', [DiaryPublicController::class, 'show'])->name('public.diaries.show');
    Route::get('/public_diaries/users/{user}',[DiaryPublicController::class, 'user'])->name('public.diaries.user');
    Route::get('/artists/search',[ArtistController::class, 'search'])->name('artists.search');

    Route::post('/diaries/{diary}/comments', [CommentController::class, 'store'])->name('comments.store')
        ->middleware('throttle:20,1'); // 簡易スパム対策（1分20件）
    Route::delete('/comments/{comment}',[CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/ai/diary-suggest', [AiDiaryController::class, 'suggest'])->name('ai.diary.suggest');
    Route::post('/ai/diary-reset', [AiDiaryController::class, 'reset'])->name('ai.diary.reset');

    Route::post('/diaries/{diary}/like', [DiaryLikeController::class, 'store'])->name('diaries.like.store');
    Route::delete('/diaries/{diary}/like', [DiaryLikeController::class, 'destroy'])->name('diaries.like.destroy');

    Route::get('/diaries/{diary}/likes', [DiaryLikeController::class, 'index'])->name('diaries.likes.index');
});


require __DIR__.'/auth.php';
