<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CountiesController;

Route::post('/users/login', [UsersController::class, 'login']);

Route::get('/users', [UsersController::class, 'index'])->middleware('auth:sanctum');
Route::get('/counties', [CountiesController::class, 'index']);