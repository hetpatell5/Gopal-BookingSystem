<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\FormController;

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
    Route::post('/passengers/{passenger}/toggle-hisab', [PassengerController::class, 'toggleHisab'])->name('passengers.toggle_hisab');
    Route::resource('passengers', PassengerController::class);
    
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting.index');
    Route::get('/accounting/{bus}', [AccountingController::class, 'show'])->name('accounting.show');
    
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/pdf', [App\Http\Controllers\TicketController::class, 'downloadPdf'])->name('tickets.pdf');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/personal-accounts', function () { return view('personal-accounts.index'); })->name('personal-accounts.index');

    // Forms: admin CRUD
    Route::get('/forms',                 [FormController::class, 'index'])  ->name('forms.index');
    Route::get('/forms/create',          [FormController::class, 'create']) ->name('forms.create');
    Route::get('/forms/contract',        function() { return view('forms.contract'); })->name('forms.contract');
    Route::post('/forms',                [FormController::class, 'store'])  ->name('forms.store');
    Route::get('/forms/{form}/edit',     [FormController::class, 'edit'])   ->name('forms.edit');
    Route::put('/forms/{form}',          [FormController::class, 'update']) ->name('forms.update');
    Route::delete('/forms/{form}',       [FormController::class, 'destroy'])->name('forms.destroy');
    Route::delete('/form-responses/{response}', [FormController::class, 'destroyResponse'])->name('forms.response.destroy');
});

// Public form pages (no auth required)
Route::get('/f/{form}',        [FormController::class, 'publicShow'])  ->name('forms.public.show');
Route::post('/f/{form}/submit',[FormController::class, 'publicSubmit'])->name('forms.public.submit');
