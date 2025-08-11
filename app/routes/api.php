<?php

declare(strict_types=1);

use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', static fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::controller(TodoController::class)
    ->prefix('tasks')
    ->name('tasks.')
    ->group(static function (): void {
        Route::get('/{id}', 'show')->name('show');
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    })
;
