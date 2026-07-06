<?php

use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\ActivityFeedController;
use App\Http\Controllers\SuperAdmin\ClientMonitoringController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
 * PENTING: route ini TIDAK memakai middleware ResolveTenant sama sekali.
 * Guard-nya 'super_admin' (terpisah dari guard tenant), dan semua query
 * di controller di atas sudah eksplisit ke schema public.
 */
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::middleware('guest:super_admin')->group(function () {
        Route::get('login', [SuperAdminLoginController::class, 'create'])->name('login');
        Route::post('login', [SuperAdminLoginController::class, 'store']);
    });

    Route::middleware('auth:super_admin')->group(function () {
        Route::post('logout', [SuperAdminLoginController::class, 'destroy'])->name('logout');

        // Placeholder view -- ganti ke halaman dashboard sungguhan begitu Fase 6 UI jalan (Hari 7)
        Route::view('dashboard', 'superadmin.dashboard')->name('dashboard');

        Route::get('dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

        Route::get('clients', [ClientMonitoringController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}/activity', [ClientMonitoringController::class, 'activity'])->name('clients.activity');

        Route::get('activity', [ActivityFeedController::class, 'index'])->name('activity.index');
    });
});
