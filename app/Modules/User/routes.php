
<?php

use App\Modules\User\Controllers\UserController;


Route::middleware(['web', 'auth'])->group(function () {
    Route::any('users', [UserController::class, 'index'])->name('users.index');
});

