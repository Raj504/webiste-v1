<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\Admin\SettlementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/gym-details', 'gymDetails')->name('gym-details');
    Route::get('/signup', 'signup')->name('signup');
    Route::get('/login', 'login')->name('login');
});

Route::controller(GymController::class)->group(function () {
    Route::get('/bookings', 'bookings')->name('bookings');
    Route::get('/qr-scanner', 'QrScanner')->name('qr-scanner');
    Route::get('/payouts', 'payouts')->name('payouts');
    Route::get('/members', 'members')->name('members');
    Route::get('/gym-settings', 'gymSettings')->name('gym-settings');
    Route::get('/reviews', 'reviews')->name('reviews');
    Route::get('/analytics', 'analytics')->name('analytics');
});

// Internal — Raj only. Gated by shared basic-auth creds (ADMIN_USERNAME/ADMIN_PASSWORD in .env),
// not a real user role, since the app has no admin role yet. raj
Route::middleware('admin.auth')->prefix('admin')->group(function () {
    Route::get('/settlements', [SettlementController::class, 'index'])->name('admin.settlements');
    Route::post('/settlements/{settlement}/toggle-paid', [SettlementController::class, 'togglePaid'])->name('admin.settlements.toggle-paid');
});