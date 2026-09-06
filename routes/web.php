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
use App\Http\Controllers\Owner\OwnerReportController;


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

// ==========================================
// 2.1 ROUTE VERIFIKASI EMAIL (MustVerifyEmail)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->route('owner.dashboard');
        }
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('owner.dashboard')->with('success', 'Email Anda berhasil diverifikasi!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

use App\Http\Controllers\Owner\AIController;

// ==========================================
// 3. ROUTE OWNER (Pemilik Usaha)
// ==========================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    // Halaman khusus status verifikasi akun (dapat diakses saat pending/rejected)
    Route::get('/pending-verification', [OwnerDashboardController::class, 'pendingVerification'])->name('pending-verification');
    Route::get('/rejected-verification', [OwnerDashboardController::class, 'rejectedVerification'])->name('rejected-verification');

    // ➕ TAMBAHKAN ROUTE INI (Proses submit ulang data KTP/identitas setelah ditolak)
    Route::post('/re-apply', [OwnerDashboardController::class, 'reApply'])->name('re-apply');

    // Route operasional yang diproteksi penuh (Wajib verified email & akun disetujui Admin)
    Route::middleware(['verified', 'EnsureOwnerApproved'])->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('lodgings', OwnerLodgingController::class);
        

        Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports.index'); 
       
        // Feature AI Description Generator
        Route::post('/ai/generate-description', [AIController::class, 'generateDescription'])->name('ai.generate-description');

        Route::get('/profile', [OwnerProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [OwnerProfileController::class, 'update'])->name('profile.update');
    });
});

// ==========================================
// 4. ROUTE ADMIN (Administrator)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // ── Verifikasi Listing Tempat Usaha (Tingkat 2) ──
    // Mendukung semua tipe: ?type=penginapan|wisata|nongkrong
    Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{id}', [AdminVerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{id}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

    // ── Verifikasi Akun Owner (Tingkat 1) ──
    Route::get('/owner-verifications', [AdminVerificationController::class, 'ownerIndex'])->name('owner-verifications.index');
    Route::get('/owner-verifications/{id}', [AdminVerificationController::class, 'ownerShow'])->name('owner-verifications.show');
    Route::get('/owner-verifications/{id}/ktp', [AdminVerificationController::class, 'viewKtp'])->name('owner-verifications.ktp');
    Route::post('/owner-verifications/{id}/approve', [AdminVerificationController::class, 'ownerApprove'])->name('owner-verifications.approve');
    Route::post('/owner-verifications/{id}/reject', [AdminVerificationController::class, 'ownerReject'])->name('owner-verifications.reject');

    // Master & Data Management
    Route::resource('tourist-places', AdminTouristPlaceController::class);
    Route::resource('hangout-places', AdminHangoutPlaceController::class);
    Route::resource('lodgings', AdminLodgingController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('facilities', AdminFacilityController::class);
    Route::resource('users', AdminUserController::class);
});

// forgot password
use App\Http\Controllers\Auth\ForgotPasswordController;

// Halaman Minta Link Reset Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Halaman Form Reset Password Baru
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');


