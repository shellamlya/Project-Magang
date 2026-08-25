<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlaceApiController;

/*
|--------------------------------------------------------------------------
| REST API Routes - GREX
|--------------------------------------------------------------------------
*/

Route::prefix('places')->group(function () {
    Route::get('/', [PlaceApiController::class, 'index']);
    Route::post('/', [PlaceApiController::class, 'store']);
    Route::get('/{id}', [PlaceApiController::class, 'show']);
    Route::put('/{id}', [PlaceApiController::class, 'update']);
    Route::delete('/{id}', [PlaceApiController::class, 'destroy']);

    // Endpoint Tracking Analitik
    Route::post('/{id}/track-view', [PlaceApiController::class, 'trackView']);
    Route::post('/{id}/track-map-click', [PlaceApiController::class, 'trackMapClick']);
});
