<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'info'])
    ->name('api.info');
