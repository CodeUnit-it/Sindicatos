<?php
use Illuminate\Support\Facades\Route;

// Rutas web bienvenida.
Route::get('/', function () {
    return view('welcome');
});