<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('project', ProyectoController::class);
