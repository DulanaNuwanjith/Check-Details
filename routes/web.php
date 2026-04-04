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
    Route::middleware(['manager'])->group(function () {
        Route::patch('cheques/{cheque}', [ChequeController::class, 'update'])->name('cheques.update');
        Route::delete('cheques/{cheque}', [ChequeController::class, 'destroy'])->name('cheques.destroy');
    });
    Route::resource('cheques', ChequeController::class)->except(['update', 'destroy']);

    //Bank Accounts
    Route::middleware(['manager'])->group(function () {
        Route::patch('bank-accounts/{bank_account}', [BankAccountsController::class, 'update'])->name('bank-accounts.update');
        Route::delete('bank-accounts/{bank_account}', [BankAccountsController::class, 'destroy'])->name('bank-accounts.destroy');
        Route::patch('bank-accounts/{id}/toggle-status', [BankAccountsController::class, 'toggleStatus'])
            ->name('bank-accounts.toggle-status');
    });
    Route::resource('bank-accounts', BankAccountsController::class)->except(['update', 'destroy']);

    //User Management
    Route::middleware(['superadmin'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });
});
