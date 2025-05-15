<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authcontroller;
use App\Http\Controllers\Api\PostController;

Route::post('signup',[AuthController::class,'signup']);

Route::post('login',[AuthController::class,'login']);

Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::apiResource('posts',PostController::class)->middleware('auth:sanctum');