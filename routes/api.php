<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\ActivityController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('instruments', [InstrumentController::class, 'index']);
Route::get('activities', [ActivityController::class, 'index']);
Route::get('instruments/usage', [InstrumentController::class, 'usage']);
Route::post('instruments/usage', [InstrumentController::class, 'usageForInstrument']);
