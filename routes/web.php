<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ChatController::class, 'index'])->name('dashboard');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat');
    Route::get('/global-chat', [ChatController::class, 'globalChat'])->name('global-chat');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
