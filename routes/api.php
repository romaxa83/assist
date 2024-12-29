<?php

use App\Http\Controllers\Api;
use App\Http\Controllers\Api\Tags\CrudController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'info'])
    ->name('api.info');

//Route::get('/tags', [CrudController::class, 'index'])
//    ->name('api.tags.index');


Route::middleware(['auth:sanctum'])
    ->name('api.')
    ->group(function () {
        Route::post('login', [Api\Auth\Controller::class, 'login'])
            ->withoutMiddleware(['auth:sanctum'])
            ->name('login');
        Route::post('logout', [Api\Auth\Controller::class, 'logout'])
            ->name('logout');


        Route::get('profile', [Api\Users\ProfileController::class, 'user'])
            ->name('profile');

        // TAGS
        Route::get('tags', [Api\Tags\CrudController::class, 'index'])
            ->name('tag.index');
        Route::post('tags', [Api\Tags\CrudController::class, 'create'])
            ->name('tag.create');
        Route::put('tags/{id}', [Api\Tags\CrudController::class, 'update'])
            ->name('tag.update');
        Route::delete('tags/{id}', [Api\Tags\CrudController::class, 'delete'])
            ->name('tag.delete');
    });
