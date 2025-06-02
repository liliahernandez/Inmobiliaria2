<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MultaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/multas', [MultaController::class, 'index']);
Route::post('/multas', [MultaController::class, 'store']);
Route::get('/ultima-multa', [MultaController::class, 'ultimaMulta']);
