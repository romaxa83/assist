<?php

use App\Http\Controllers\Api;
use App\Http\Controllers\Api\Tags\CrudController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'info'])
    ->name('api.info');

Route::get('/tags', [CrudController::class, 'index'])
    ->name('api.tags.index');


Route::post('login', [Api\Auth\Controller::class, 'login'])
    ->name('api.login');
