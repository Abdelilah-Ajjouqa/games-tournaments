<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\tournamentController;
use App\Http\Controllers\UserController;
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
Route::get('/tournaments', [tournamentController::class, 'index'])->name('index');
Route::get('/tournament/{id}', [tournamentController::class, 'show'])->name('show');


Route::middleware('auth:sanctum')->group(function(){
    Route::post('/tournament', [tournamentController::class, 'store'])->name('store');
    Route::put('tournament/{id}', [tournamentController::class, 'update'])->name('update');
    Route::delete('/tournament/{id}', [tournamentController::class, 'destroy'])->name('destroy');

    // user's routes
    Route::get('profile/{id}', [UserController::class, 'show'])->name('profile.show');
    Route::put('profile/{id}', [UserController::class, 'update'])->name('profile.update');
    Route::delete('profile/{id}', [UserController::class, 'destroy'])->name('profile.destroy');

    // players routes
    Route::get('/tournament/{id}/players', [PlayerController::class, 'index'])->name('players.index');
    Route::post('/tournament/{id}/players', [PlayerController::class, 'store'])->name('players.store');
    Route::delete('/tournament/{tournament_id}/players/{player_id}', [PlayerController::class, 'destroy'])->name('players.destroy');});
