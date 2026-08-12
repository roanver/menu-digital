<?php

use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ScreenController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\Admin\MenuAdvisorController;
use App\Http\Controllers\Admin\RestaurantSwitchController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuImportController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\NfcController;
use App\Http\Controllers\Admin\PostGeneratorController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\BusinessHoursController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\KitActivationController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuJsonController;
use App\Http\Controllers\NfcRedirectController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WhatsappClickController;
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
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::patch('/restaurants/{restaurant}/plan', [SuperAdminController::class, 'updatePlan'])->name('plan.update');
    Route::post('/restaurants/{restaurant}/enter', [SuperAdminController::class, 'enterAsRestaurant'])->name('enter');
    Route::post('/restaurants/{restaurant}/assign-user', [SuperAdminController::class, 'assignUser'])->name('assign-user');
    Route::get('/create', [SuperAdminController::class, 'createRestaurant'])->name('create');
    Route::post('/create', [SuperAdminController::class, 'storeRestaurant'])->name('store');
    Route::get('/credentials/{restaurant}', [SuperAdminController::class, 'credentials'])->name('credentials');
    Route::get('/restaurants/{restaurant}', [SuperAdminController::class, 'show'])->name('show');
    Route::post('/restaurants/{restaurant}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // NFC management
    Route::prefix('nfc')->name('nfc.')->group(function () {
        Route::get('/', [NfcController::class, 'index'])->name('index');
        Route::get('/create', [NfcController::class, 'create'])->name('create');
        Route::post('/', [NfcController::class, 'store'])->name('store');
        Route::get('/bulk', fn() => view('superadmin.nfc.bulk-create', ['restaurants' => \App\Models\Restaurant::orderBy('name')->get()]))->name('bulk-create');
        Route::post('/bulk-create', [NfcController::class, 'bulkCreate'])->name('bulk-create.store');
        Route::get('/print', [NfcController::class, 'printLabels'])->name('print');
        Route::get('/{tag}/edit', [NfcController::class, 'edit'])->name('edit');
        Route::patch('/{tag}', [NfcController::class, 'update'])->name('update');
        Route::delete('/{tag}', [NfcController::class, 'destroy'])->name('destroy');
    });
});

// Admin routes
Route::middleware(['auth', 'has.restaurant', 'set.restaurant', 'billing.check'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Selección y cambio de negocio activo
        Route::get('/select-restaurant', [RestaurantSwitchController::class, 'select'])->name('restaurants.select');
        Route::post('/switch-restaurant', [RestaurantSwitchController::class, 'switch'])->name('restaurants.switch');
        Route::post('/exit-impersonation', [RestaurantSwitchController::class, 'exitImpersonation'])->name('restaurants.exit-impersonation');
        Route::get('/restaurants/create', [RestaurantSwitchController::class, 'create'])->name('restaurants.create');
        Route::post('/restaurants', [RestaurantSwitchController::class, 'store'])->name('restaurants.store');

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
            Route::get('/qr/print', [QrController::class, 'print'])->name('qr.print');

            Route::get('/billing', [BillingController::class, 'show'])->name('billing.show');

            Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
            Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
            Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');

            // Menu import with AI
            Route::get('/import-menu', [MenuImportController::class, 'showUpload'])->name('import.upload');
            Route::post('/import-menu', [MenuImportController::class, 'upload'])->name('import.process');
            Route::get('/import-menu/review', [MenuImportController::class, 'showReview'])->name('import.review');
            Route::post('/import-menu/confirm', [MenuImportController::class, 'confirm'])->name('import.confirm');

            // Post generator
            Route::get('/posts', [PostGeneratorController::class, 'index'])->name('posts.index');
            Route::post('/posts/generate', [PostGeneratorController::class, 'generate'])->name('posts.generate');
            Route::post('/posts/download', [PostGeneratorController::class, 'downloadImage'])->name('posts.download');

            // Horarios de atención
            Route::get('/hours', [BusinessHoursController::class, 'index'])->name('hours.index');
            Route::post('/hours', [BusinessHoursController::class, 'update'])->name('hours.update');

            // Asesor de carta (AI)
            Route::get('/advisor', [MenuAdvisorController::class, 'show'])->name('advisor.show');
            Route::post('/advisor/generate', [MenuAdvisorController::class, 'generate'])->name('advisor.generate');

            // Pantallas (cartelería digital TV) — solo plan Pro
            Route::resource('screens', ScreenController::class)->except(['show']);

            // Mesas
            Route::get('/tables/print', [TableController::class, 'printAll'])->name('tables.print');
            Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
            Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
            Route::post('/tables/bulk', [TableController::class, 'storeBulk'])->name('tables.bulk');
            Route::patch('/tables/reorder', [TableController::class, 'reorder'])->name('tables.reorder');
            Route::patch('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
            Route::post('/tables/{table}/toggle', [TableController::class, 'toggleActive'])->name('tables.toggle');
            Route::post('/tables/{table}/chip-written', [TableController::class, 'toggleChipWritten'])->name('tables.chip-written');
            Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
            Route::get('/tables/{table}/qr', [TableController::class, 'downloadQr'])->name('tables.qr');
        });
    });

require __DIR__.'/auth.php';

// WhatsApp click tracking (fire and forget)
Route::post('/wa-click', [WhatsappClickController::class, 'track'])->middleware('throttle:120,1')->name('wa.click');

// Kit activation flow
Route::get('/activar/{token}', [KitActivationController::class, 'show'])->middleware('throttle:30,1')->name('kit.show');
Route::middleware('guest')->group(function () {
    Route::get('/activar/{token}/register',  [KitActivationController::class, 'showRegister'])->name('kit.register.form');
    Route::post('/activar/{token}/register', [KitActivationController::class, 'processRegister'])->middleware('throttle:5,1')->name('kit.register');
    Route::get('/activar/{token}/login',     [KitActivationController::class, 'showLogin'])->name('kit.login.form');
    Route::post('/activar/{token}/login',    [KitActivationController::class, 'processLogin'])->middleware('throttle:5,1')->name('kit.login');
});
Route::post('/activar/{token}/activate', [KitActivationController::class, 'activate'])->middleware(['auth', 'throttle:10,1'])->name('kit.activate');

// NFC public redirects (before the wildcard /{slug})
Route::get('/r/{code}', [NfcRedirectController::class, 'review'])->middleware('throttle:120,1')->name('nfc.review');
Route::get('/m/{code}', [NfcRedirectController::class, 'menu'])->middleware('throttle:120,1')->name('nfc.menu');

// Board público (cartelería digital) — antes del wildcard /{slug}
Route::get('/board/{token}',         [BoardController::class, 'show'])->middleware('throttle:30,1')->name('board.show');
Route::get('/board/{token}/version', [BoardController::class, 'version'])->middleware('throttle:120,1')->name('board.version');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/{slug}/menu.json', [MenuJsonController::class, 'show'])->middleware('throttle:60,1')->where('slug', '[a-z0-9-]+')->name('menu.json');
Route::get('/{slug}', [MenuController::class, 'show'])->middleware('throttle:60,1')->where('slug', '[a-z0-9-]+')->name('menu.show');
