<?php

use App\Http\Controllers\Api;
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

        // PRIVATE
        Route::name('private.')
            ->prefix('private')
            ->group(function () {
                // NOTES CRUD
                Route::get('notes', [Api\Notes\Private\CrudController::class, 'index'])
                    ->name('note.index');
                Route::get('notes/shortlist', [Api\Notes\Private\CrudController::class, 'shortlist'])
                    ->name('note.shortlist');
                Route::get('notes/{id}', [Api\Notes\Private\CrudController::class, 'show'])
                    ->name('note.show');
                Route::post('notes', [Api\Notes\Private\CrudController::class, 'create'])
                    ->name('note.create');
                Route::put('notes/{id}', [Api\Notes\Private\CrudController::class, 'update'])
                    ->name('note.update');
                Route::delete('notes/{id}', [Api\Notes\Private\CrudController::class, 'delete'])
                    ->name('note.delete');
                // NOTES ACTION
                Route::post('notes/{id}/set-status', [Api\Notes\Private\ActionController::class, 'setStatus'])
                    ->name('note.set-status');

                // TAGS
                Route::get('tags', [Api\Tags\Private\CrudController::class, 'index'])
                    ->name('tag.index');
                Route::post('tags', [Api\Tags\Private\CrudController::class, 'create'])
                    ->name('tag.create');
                Route::put('tags/{id}', [Api\Tags\Private\CrudController::class, 'update'])
                    ->name('tag.update');
                Route::delete('tags/{id}', [Api\Tags\Private\CrudController::class, 'delete'])
                    ->name('tag.delete');

                // USER
                Route::get('profile', [Api\Users\Private\ProfileController::class, 'user'])
                    ->name('profile');

                // SETTINGS
                Route::get('settings/notes', [Api\Settings\Private\Controller::class, 'notes'])
                    ->name('settings.notes');
            });
    });
