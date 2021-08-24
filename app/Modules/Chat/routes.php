
<?php

use App\Modules\Chat\Controllers\ChatController;


Route::middleware(['web', 'auth'])->group(function () {
    Route::any('chat/user/message', [ChatController::class, 'ajaxChat'])->name('chat.user.message');
    Route::post('chat/user/message/store', [ChatController::class, 'store'])->name('chat.user.message.store');   
    Route::get('message', [ChatController::class, 'index'])->name('chat.index');
    Route::any('readMessage', [ChatController::class, 'readMessage'])->name('readMessage');
});

