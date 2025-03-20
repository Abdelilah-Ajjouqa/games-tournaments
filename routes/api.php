<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// index route
Route::get('/', function(){
    return response()->json(["message"=>"welcome page"], 200);
});

// Auth routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// public routes
Route::get('/tournaments', [PostController::class, 'index'])->name('index');
Route::get('/tournament/{id}', [PostController::class, 'show'])->name('show');


Route::middleware('auth:sanctum')->group(function(){
    Route::post('/tournament', [PostController::class, 'store'])->name('store');
    Route::put('tournament/{id}', [PostController::class, 'update'])->name('update');
    Route::delete('/tounrament/{id}', [PostController::class, 'destroy'])->name('destroy');
});