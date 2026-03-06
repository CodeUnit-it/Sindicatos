<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\LeadController;

// Rutas API para el sitio de noticias
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::get('/documents', [DocumentController::class, 'index']);
Route::get('/salaries', [SalaryController::class, 'index']);
Route::post('/leads', [LeadController::class, 'store']);
