<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [UserController::class,'login']);
Route::get('signup', [UserController::class,'register']);
Route::resource('user', UserController::class);