<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\AccountingController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::get('/buses/{bus}/register', [BusController::class, 'printRegister'])->name('buses.register');
    Route::resource('buses', BusController::class);
    
    Route::get('/passengers/register', [PassengerController::class, 'printRegister'])->name('passengers.register');
    Route::post('/passengers/bulk-delete', [PassengerController::class, 'bulkDestroy'])->name('passengers.bulk_destroy');
    Route::resource('passengers', PassengerController::class);
    
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting.index');
    
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
