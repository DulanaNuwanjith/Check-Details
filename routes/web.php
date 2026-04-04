<?php

use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Cheque Management
    Route::resource('cheques', ChequeController::class);

    //Bank Accounts
    Route::resource('bank-accounts', BankAccountsController::class);
    Route::patch('bank-accounts/{id}/toggle-status', [BankAccountsController::class, 'toggleStatus'])
        ->name('bank-accounts.toggle-status');

    //User Management
    Route::middleware(['superadmin'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });
});
