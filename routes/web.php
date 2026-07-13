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
    Route::post('/passengers/{passenger}/update-payment', [PassengerController::class, 'updatePayment'])->name('passengers.update_payment');
    Route::resource('passengers', PassengerController::class);
    
    Route::get('/broadcast', [App\Http\Controllers\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast/send', [App\Http\Controllers\BroadcastController::class, 'send'])->name('broadcast.send');
    
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting.index');
    Route::post('/accounting/{bus}/toggle-daily-hisab', [AccountingController::class, 'toggleDailyHisab'])->name('accounting.toggle_daily_hisab');
    Route::get('/accounting/{bus}', [AccountingController::class, 'show'])->name('accounting.show');
    
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/pdf', [App\Http\Controllers\TicketController::class, 'downloadPdf'])->name('tickets.pdf');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/personal-accounts', [App\Http\Controllers\PersonalAccountController::class, 'index'])->name('personal-accounts.index');
    Route::get('/personal-accounts/create', [App\Http\Controllers\PersonalAccountController::class, 'create'])->name('personal-accounts.create');
    Route::post('/personal-accounts', [App\Http\Controllers\PersonalAccountController::class, 'store'])->name('personal-accounts.store');
    Route::get('/personal-accounts/agent-tickets', [App\Http\Controllers\AgentTicketController::class, 'index'])->name('agent-tickets.index');
    Route::get('/personal-accounts/agent-tickets/create', [App\Http\Controllers\AgentTicketController::class, 'create'])->name('agent-tickets.create');
    Route::post('/personal-accounts/agent-tickets', [App\Http\Controllers\AgentTicketController::class, 'store'])->name('agent-tickets.store');
    Route::get('/personal-accounts/agent-tickets/{ticket}/edit', [App\Http\Controllers\AgentTicketController::class, 'edit'])->name('agent-tickets.edit');
    Route::put('/personal-accounts/agent-tickets/{ticket}', [App\Http\Controllers\AgentTicketController::class, 'update'])->name('agent-tickets.update');
    Route::delete('/personal-accounts/agent-tickets/{ticket}', [App\Http\Controllers\AgentTicketController::class, 'destroy'])->name('agent-tickets.destroy');
    Route::get('/personal-accounts/filter', [App\Http\Controllers\PersonalAccountController::class, 'filter'])->name('personal-accounts.filter');
    Route::get('/personal-accounts/month/{year}/{month}', [App\Http\Controllers\PersonalAccountController::class, 'showMonth'])->name('personal-accounts.month');
    Route::get('/personal-accounts/date/{date}', [App\Http\Controllers\PersonalAccountController::class, 'showDate'])->name('personal-accounts.date');
    Route::get('/personal-accounts/{id}/edit', [App\Http\Controllers\PersonalAccountController::class, 'edit'])->name('personal-accounts.edit');
    Route::put('/personal-accounts/{id}', [App\Http\Controllers\PersonalAccountController::class, 'update'])->name('personal-accounts.update');
    Route::delete('/personal-accounts/{id}', [App\Http\Controllers\PersonalAccountController::class, 'destroy'])->name('personal-accounts.destroy');

    // Forms: admin CRUD
    Route::get('/forms',                 [FormController::class, 'index'])  ->name('forms.index');
    Route::get('/forms/create',          [FormController::class, 'create']) ->name('forms.create');
    Route::redirect('/forms/contract', '/contracts');
    Route::resource('contracts', App\Http\Controllers\ContractController::class);
    Route::post('/forms',                [FormController::class, 'store'])  ->name('forms.store');
    Route::get('/forms/{form}/edit',     [FormController::class, 'edit'])   ->name('forms.edit');
    Route::put('/forms/{form}',          [FormController::class, 'update']) ->name('forms.update');
    Route::delete('/forms/{form}',       [FormController::class, 'destroy'])->name('forms.destroy');
    Route::delete('/form-responses/{response}', [FormController::class, 'destroyResponse'])->name('forms.response.destroy');
});

// Public form pages (no auth required)
Route::get('/f/{form}',        [FormController::class, 'publicShow'])  ->name('forms.public.show');
Route::post('/f/{form}/submit',[FormController::class, 'publicSubmit'])->name('forms.public.submit');
