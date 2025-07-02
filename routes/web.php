<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\InventoryController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset Routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Supplies
    Route::resource('supplies', SupplyController::class);
    Route::post('supplies/{supply}/restock', [SupplyController::class, 'restock'])->name('supplies.restock');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Transactions
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

    // Admin only routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Users Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
});
