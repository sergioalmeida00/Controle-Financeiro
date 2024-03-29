<?php

use App\Http\Controllers\Api\BankAccount\BankAccountController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Transaction\TransactionController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/create', [UserController::class, 'store']);


// Rotas protegidas por JWT
Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    // Usuario
    // Route::prefix('user')->group(function () {
    //     Route::post('/create', [UserController::class, 'store']);
    // });

    //BANK-ACCOUNT
    Route::prefix('bank-account')->group(function () {
        Route::get('/list', [BankAccountController::class, 'getAll']);
        Route::get('/{id}/extract', [BankAccountController::class, 'show']);
        Route::post('/register', [BankAccountController::class, 'store']);
        Route::put('/update/{id}', [BankAccountController::class, 'update']);
        Route::delete('/delete/{id}', [BankAccountController::class, 'destroy']);
    });

    //TRANSACTION
    Route::prefix('transaction')->group(function () {
        Route::get('/', [TransactionController::class, 'show']);
        Route::post('/register', [TransactionController::class, 'store']);
        Route::put('/update/{id}', [TransactionController::class, 'update']);
        Route::delete('/delete/{id}', [TransactionController::class, 'destroy']);
    });

    //CATEGORY
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'show']);
        Route::post('/register', [CategoryController::class, 'store']);
    });
});
