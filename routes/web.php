<?php

use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\ChequeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Cheque Management
    Route::resource('cheques', ChequeController::class);

    //Bank Accounts
    Route::resource('bank-accounts', BankAccountsController::class);
});
