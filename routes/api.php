<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Building search API
Route::get('/buildings/search', [BuildingController::class, 'search'])->name('api.buildings.search');
