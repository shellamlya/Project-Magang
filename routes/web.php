<?php

use Illuminate\Support\Facades\Route;

// Controller User & Publik
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PlaceController;

// Controller Autentikasi
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterOwnerController;

// Controller Owner
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerLodgingController;
use App\Http\Controllers\Owner\OwnerProfileController;

// Controller Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\AdminLodgingController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminFacilityController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTouristPlaceController;
use App\Http\Controllers\Admin\AdminHangoutPlaceController;

/*
|--------------------------------------------------------------------------
| Web Routes - Lokavino
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIK (User / Visitor)
// ==========================================
// Landing Page (Pusat Pencarian Utama)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Kategori Utama
Route::get('/penginapan', [PlaceController::class, 'penginapan'])->name('penginapan');
Route::get('/wisata', [PlaceController::class, 'wisata'])->name('wisata');
Route::get('/nongkrong', [PlaceController::class, 'nongkrong'])->name('nongkrong');

// Halaman Detail Tempat
Route::get('/place/{slug}', [PlaceController::class, 'show'])->name('place.detail');
Route::get('/penginapan/{id}', [PlaceController::class, 'showLodging'])->name('lodging.detail');
Route::get('/wisata/{id}', [PlaceController::class, 'showWisata'])->name('wisata.detail');
Route::get('/nongkrong/{id}', [PlaceController::class, 'showHangout'])->name('nongkrong.detail');

// API Endpoint Kelurahan
Route::get('/api/districts/{id}/villages', [HomeController::class, 'getVillages'])->name('api.villages');

// ==========================================
// 2. ROUTE AUTENTIKASI (Login & Register Owner)
// ==========================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register-owner', [RegisterOwnerController::class, 'showRegisterForm'])->name('register.owner');
Route::post('/register-owner', [RegisterOwnerController::class, 'register']);

use App\Http\Controllers\Owner\AIController;

// ==========================================
// 3. ROUTE OWNER (Pemilik Usaha)
// ==========================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('lodgings', OwnerLodgingController::class);
    
    // Feature AI Description Generator
    Route::post('/ai/generate-description', [AIController::class, 'generateDescription'])->name('ai.generate-description');
    
    // Feature Klaim Tempat Usaha
    Route::get('/claim-places', [OwnerDashboardController::class, 'claimIndex'])->name('claim.index');
    Route::post('/claim-places/{category}/{id}', [OwnerDashboardController::class, 'claimSubmit'])->name('claim.submit');

    Route::get('/profile', [OwnerProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [OwnerProfileController::class, 'update'])->name('profile.update');
});

// ==========================================
// 4. ROUTE ADMIN (Administrator)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Verifikasi Pengajuan Tempat
    Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{id}', [AdminVerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{id}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

    // Master & Data Management
    Route::resource('tourist-places', AdminTouristPlaceController::class);
    Route::resource('hangout-places', AdminHangoutPlaceController::class);
    Route::resource('lodgings', AdminLodgingController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('facilities', AdminFacilityController::class);
    Route::resource('users', AdminUserController::class);
});
