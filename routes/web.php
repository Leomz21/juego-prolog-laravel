<?php

use App\Http\Controllers\JuegoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JuegoController::class, 'index'])->name('juego.index');
Route::post('/jugar', [JuegoController::class, 'jugar'])->name('juego.jugar');