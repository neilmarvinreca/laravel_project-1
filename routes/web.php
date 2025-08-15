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

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeployedItemController;
use App\Http\Controllers\DeploymentRequestController;


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

    // Deployment Requests
    Route::resource('deployment-requests', DeploymentRequestController::class)->only(['index', 'show', 'store', 'update']);
    Route::get('deployment-requests/approvers', [DeploymentRequestController::class, 'getApprovers'])->name('deployment-requests.approvers');
    
    // Deployed Items - Archive routes must be defined before the resource route
    Route::prefix('deployed-items')->name('deployed-items.')->group(function () {
        Route::get('archived', [DeployedItemController::class, 'archived'])->name('archived');
        Route::put('{id}/archive', [DeployedItemController::class, 'archive'])->name('archive');
        Route::post('{id}/restore', [DeployedItemController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [DeployedItemController::class, 'forceDelete'])->name('force-delete');
    });
    
    // Deployed Items Resource Route
    Route::resource('deployed-items', DeployedItemController::class);
    
    // Test route for debugging QR code
    Route::get('test-qr/{deployedItem}', function (\App\Models\DeployedItem $deployedItem) {
        return response()->json([
            'deployedID' => $deployedItem->deployedID,
            'qrCode' => $deployedItem->qrCode,
            'qr_code' => $deployedItem->qr_code,
            'raw_qr_code' => $deployedItem->getRawOriginal('qr_code'),
            'attributes' => $deployedItem->getAttributes(),
            'table_columns' => \Schema::getColumnListing('deployed_items')
        ]);
    })->name('test-qr');
    
    // Archive routes for supplies - Placing these before the resource route to avoid conflicts
    Route::get('supplies/archived', [SupplyController::class, 'archived'])->name('supplies.archived');
    Route::put('supplies/{supply}/archive', [SupplyController::class, 'archive'])->name('supplies.archive');
    Route::put('supplies/{supply}/restore', [SupplyController::class, 'restore'])->name('supplies.restore');
    Route::delete('supplies/{supply}/force-delete', [SupplyController::class, 'forceDelete'])->name('supplies.force-delete');
    
    // Archive routes for departments - Placing these before the resource route to avoid conflicts
    Route::get('departments/archived', [DepartmentController::class, 'archived'])->name('departments.archived');
    Route::put('departments/{department}/archive', [DepartmentController::class, 'archive'])->name('departments.archive');
    Route::put('departments/{department}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('departments/{department}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.force-delete');
    
    // Supplies
    Route::resource('supplies', SupplyController::class);
    
    // Deployment form route with simpler path
    Route::get('deploy-supplies', [SupplyController::class, 'deployForm'])->name('supplies.deploy');
    
    Route::post('supplies/{supply}/restock', [SupplyController::class, 'restock'])->name('supplies.restock');

    // Archive routes - Placing these before the resource route to avoid conflicts
    Route::get('categories/archived', [CategoryController::class, 'archived'])->name('categories.archived');
    Route::put('categories/{category}/archive', [CategoryController::class, 'archive'])->name('categories.archive');
    Route::put('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    
    // Categories - Using route model binding with explicit parameter name
    // This is placed after the archive routes to prevent route conflicts
    Route::resource('categories', CategoryController::class)->parameters([
        'categories' => 'category'
    ]);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/deployed-items', [ReportController::class, 'deployedItems'])->name('reports.deployed-items');
    Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])
        ->whereIn('type', ['inventory', 'low-stock', 'deployed-items'])
        ->name('reports.export');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Users Management Routes
    Route::prefix('users')->name('users.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Auth\UserController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Auth\UserController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Auth\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [\App\Http\Controllers\Auth\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [\App\Http\Controllers\Auth\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [\App\Http\Controllers\Auth\UserController::class, 'destroy'])->name('destroy');
    });

    // Departments
    Route::resource('departments', DepartmentController::class);
});

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
});
