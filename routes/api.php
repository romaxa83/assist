<?php

use App\Http\Controllers\Api\Tags\CrudController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'info'])
    ->name('api.info');

Route::get('/tags', [CrudController::class, 'index'])
    ->name('api.tags.index');


