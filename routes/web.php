<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryPublicController;
use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('diaries', DiaryController::class);
    Route::get('/public_diaries', [DiaryPublicController::class, 'index'])->name('public.diaries.index');
    Route::get('/public_diaries/{diary}', [DiaryPublicController::class, 'show'])->name('public.diaries.show');
    Route::get('/public_diaries/users/{user}',[DiaryPublicController::class, 'user'])->name('public.diaries.user');
    Route::get('/artists/search',[ArtistController::class, 'search'])->name('artists.search');
});

require __DIR__.'/auth.php';
