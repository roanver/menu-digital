<?php

use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super-admin routes
Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::patch('/restaurants/{restaurant}/plan', [SuperAdminController::class, 'updatePlan'])->name('plan.update');
});

// Admin routes
Route::middleware(['auth', 'has.restaurant', 'billing.check'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::patch('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::patch('/items/reorder', [MenuItemController::class, 'reorder'])->name('items.reorder');
        Route::resource('items', MenuItemController::class)->except(['show']);

        // Owner-only routes
        Route::middleware('is.owner')->group(function () {
            Route::get('/restaurant', [RestaurantController::class, 'edit'])->name('restaurant.edit');
            Route::patch('/restaurant', [RestaurantController::class, 'update'])->name('restaurant.update');

            Route::get('/appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
            Route::patch('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

            Route::get('/qr', [QrController::class, 'show'])->name('qr.show');
            Route::get('/qr/download', [QrController::class, 'download'])->name('qr.download');

            Route::get('/billing', [BillingController::class, 'show'])->name('billing.show');

            Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
            Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
            Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
        });
    });

require __DIR__.'/auth.php';

Route::get('/{slug}', [MenuController::class, 'show'])->name('menu.show');
