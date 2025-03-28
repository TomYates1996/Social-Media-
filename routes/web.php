<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController; 
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('create', [PostController::class, 'store']);
    Route::get('explore', [PostController::class, 'getAllPosts']);
    Route::get('/profile/{username}', [ProfileController::class, 'show']);
    Route::post('/profile/{username}/follow', [ProfileController::class, 'follow']);
    Route::post('/profile/{username}/unfollow', [ProfileController::class, 'unfollow']);
    // Route::get('/posts/followed', [PostController::class, 'getFollowedPosts']);
});

Route::get('check-username/{username}', [RegisteredUserController::class, 'checkUsername']);




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
