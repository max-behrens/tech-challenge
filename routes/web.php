<?php

use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('admin/dashboard', AdminDashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::get('admin/items', [ItemsController::class, 'index'])
    ->name('admin.items.index')
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Item']);

Route::get('admin/items/create', [ItemsController::class, 'create'])
    ->name('admin.items.create')
    ->middleware(['auth', 'verified', 'can:create,App\Models\Item']);

Route::post('admin/items', [ItemsController::class, 'store'])
    ->middleware(['auth', 'verified', 'can:create,App\Models\Item']);

Route::get('admin/items/{item}/edit', [ItemsController::class, 'edit'])
    ->name('admin.items.edit')
    ->middleware(['auth', 'verified', 'can:update,item']);

Route::patch('admin/items/{item}', [ItemsController::class, 'update'])
    ->middleware(['auth', 'verified', 'can:update,item']);

Route::delete('admin/items/{item}', [ItemsController::class, 'delete'])
    ->name('admin.items.delete')
    ->middleware(['auth', 'verified', 'can:delete,item']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
