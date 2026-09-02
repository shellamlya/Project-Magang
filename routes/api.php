<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlaceApiController;
use App\Http\Controllers\Api\VinoAIController;

/*
|--------------------------------------------------------------------------
| REST API Routes - Lokavino
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

// ==========================================
// VinoAI — Context Data API
// Diamankan dengan header: X-VinoAI-Key: {VINO_AI_SECRET_KEY dari .env}
// ==========================================
Route::prefix('vino')->group(function () {
    // GET /api/vino/context?type=all&limit=100
    Route::get('/context', [VinoAIController::class, 'index'])->name('api.vino.context');

    // GET /api/vino/context/{type}/{id}   type: penginapan | wisata | kafe
    Route::get('/context/{type}/{id}', [VinoAIController::class, 'show'])->name('api.vino.context.show');

    // GET /api/vino/search?q=kata+kunci&type=kafe&district=Kecamatan+X
    Route::get('/search', [VinoAIController::class, 'search'])->name('api.vino.search');
});
