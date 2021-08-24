
<?php

use App\Modules\UserGroups\Controllers\UserGroupsController;


Route::middleware(['web', 'auth'])->group(function () {
    Route::any('users/groups', [UserGroupsController::class, 'index'])->name('users.groups.index');
    Route::any('users/groups/store', [UserGroupsController::class, 'store'])->name('users.groups.store');
    Route::get('users/groups/edit/{group}', [UserGroupsController::class, 'edit'])->name('users.groups.edit');
    Route::post('users/groups/update/{group}', [UserGroupsController::class, 'update'])->name('users.groups.update');
    Route::post('users/groups/delete/{group}', [UserGroupsController::class, 'delete'])->name('users.groups.delete');
    Route::post('users/groups/leave/{group}', [UserGroupsController::class, 'leave'])->name('users.groups.leave');
});

