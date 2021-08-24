<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Models\User;


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return redirect()->route('users.index');
})->name('dashboard');

// Route::middleware(['web', 'auth'])
//     ->any('closeBrowser', [DashboardController::class, 'closeBrowser'])
//     ->name('closeBrowser');


Route::middleware(['web'])
    ->any('login', [LoginController::class, 'authenticate'])
    ->name('login');

Route::middleware(['web'])
    ->any('register', [LoginController::class, 'register'])
    ->name('register');

Route::middleware(['web', 'auth'])
    ->any('profile', [LoginController::class, 'profile'])
    ->name('profile');

Route::any('logout', function(){
    $user = User::find(authUserId());
    $user->online_status = 'offline';
    $user->save();
    event(new \App\Events\OnlineUser( authUserId(), false ));
    \Auth::logout();
    return redirect()->route('login');
});
