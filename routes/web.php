<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {

        // ── Routes: Administrator only ──────────────────────────────────────
        Route::middleware('role:Administrator')->group(function () {
            Route::resource('room-types', \App\Http\Controllers\Admin\RoomTypeController::class);
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });

        // ── Routes: Administrator + Front Office ────────────────────────────
        Route::middleware('role:Administrator|Front Office')->group(function () {
            Route::resource('guests', \App\Http\Controllers\Admin\GuestController::class);
            Route::resource('reservations', \App\Http\Controllers\Admin\ReservationController::class)->except(['edit']);
            Route::get('reservations/{reservation}/print', [\App\Http\Controllers\Admin\ReservationController::class, 'print'])
                ->name('reservations.print');
            Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
            Route::get('calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])
                ->name('calendar.index');
        });

        // ── Routes: Administrator + Front Office + Food & Beverage ──────────
        Route::middleware('role:Administrator|Front Office|Food & Beverage')->group(function () {
            Route::resource('fnb-orders', \App\Http\Controllers\Admin\FnbOrderController::class);
        });

        // ── Routes: Administrator + Front Office + Housekeeping ─────────────
        Route::middleware('role:Administrator|Front Office|Housekeeping')->group(function () {
            Route::resource('laundry-requests', \App\Http\Controllers\Admin\LaundryRequestController::class);
            Route::resource('room-inspections', \App\Http\Controllers\Admin\RoomInspectionController::class);
        });

        // ── Routes: Administrator + Housekeeping ────────────────────────────
        Route::middleware('role:Administrator|Housekeeping')->group(function () {
            Route::get('housekeeping', [\App\Http\Controllers\Admin\HousekeepingController::class, 'index'])
                ->name('housekeeping.index');
            Route::patch('housekeeping/{room}/clean', [\App\Http\Controllers\Admin\HousekeepingController::class, 'markClean'])
                ->name('housekeeping.markClean');
        });

        // ── Routes: Administrator only (master data) ─────────────────────────
        Route::middleware('role:Administrator')->group(function () {
            Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
            Route::patch('rooms/{room}/status', [\App\Http\Controllers\Admin\RoomController::class, 'updateStatus'])
                ->name('rooms.updateStatus');
            Route::resource('fnb-menus', \App\Http\Controllers\Admin\FnbMenuController::class);
            Route::resource('laundry-services', \App\Http\Controllers\Admin\LaundryServiceController::class);
        });

        // ── Reports: All roles ───────────────────────────────────────────────
        Route::get('reports/advanced', [\App\Http\Controllers\Admin\ReportController::class, 'advancedReport'])
            ->name('reports.advanced');
        Route::get('reports/daily', [\App\Http\Controllers\Admin\ReportController::class, 'dailyReport'])
            ->name('reports.daily');
        Route::get('reports/export-csv', [\App\Http\Controllers\Admin\ReportController::class, 'exportCsv'])
            ->name('reports.export-csv')->middleware('role:Administrator');
    });
});

require __DIR__.'/auth.php';
