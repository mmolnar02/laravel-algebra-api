<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NavigationController;


Route::post('/login', AuthController::class . '@login')
    ->name('login');


Route::post('/register', AuthController::class . '@register')
    ->name('register');

Route::post('/refresh', AuthController::class . '@refresh')
    ->name('refresh');


Route::middleware('auth:api')->group(function () {
    Route::get('/logout', AuthController::class . '@logout')
        ->name('logout');

    Route::resource('clients', ClientController::class);

});

