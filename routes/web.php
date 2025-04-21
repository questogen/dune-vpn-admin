<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\VpnNodeController; // Renamed: ServerController → VpnNodeController
use App\Http\Controllers\Admin\SubscriptionPlanController; // Renamed: PackagePricingController → SubscriptionPlanController
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\Settings\NotificationController as NotificationSettingsController;
use App\Http\Controllers\Admin\Settings\AdvertisementController as AdvertisementSettingsController;
use App\Http\Controllers\Admin\Settings\AppSettingController;

Route::redirect('/', 'admin/login');

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function() {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // Admin Management
    Route::prefix('admins')->group(function() {
        Route::get('/', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/add', [AdminController::class, 'add'])->name('admins.add');
        Route::post('/add', [AdminController::class, 'insert'])->name('admins.insert');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
        Route::post('/edit', [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/{id}', [AdminController::class, 'delete'])->name('admins.delete');
    });

    // Roles & Permissions
    Route::prefix('roles')->group(function() {
        Route::get('/', [RolePermissionController::class, 'index'])->name('roles.index');
        Route::get('/add', [RolePermissionController::class, 'add'])->name('roles.add');
        Route::post('/add', [RolePermissionController::class, 'insert'])->name('roles.insert');
        Route::get('/{id}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
        Route::post('/edit', [RolePermissionController::class, 'update'])->name('roles.update');
        Route::delete('/{id}', [RolePermissionController::class, 'delete'])->name('roles.delete');
    });

    // Countries
    Route::prefix('countries')->group(function() {
        Route::get('/', [CountryController::class, 'index'])->name('countries.index');
        Route::post('/add', [CountryController::class, 'add'])->name('countries.add');
        Route::get('/{id}', [CountryController::class, 'getCountryById']);
        Route::post('/edit', [CountryController::class, 'update'])->name('countries.update');
        Route::delete('/{id}', [CountryController::class, 'delete'])->name('countries.delete');
    });
    
    // VPN Nodes (Previously Servers)
    Route::prefix('nodes')->group(function() {
        Route::get('/', [VpnNodeController::class, 'index'])->name('nodes.index');
        Route::get('/add', [VpnNodeController::class, 'add'])->name('nodes.add');
        Route::post('/add', [VpnNodeController::class, 'insert'])->name('nodes.insert');
        Route::get('/{id}/edit', [VpnNodeController::class, 'edit'])->name('nodes.edit');
        Route::post('/edit', [VpnNodeController::class, 'update'])->name('nodes.update');
        Route::delete('/{id}', [VpnNodeController::class, 'delete'])->name('nodes.delete');
    });

    // Subscription Plans (Previously Package Pricing)
    Route::prefix('subscriptions')->group(function() {
        Route::get('/', [SubscriptionPlanController::class, 'index'])->name('subscriptions.index');
        Route::get('/add', [SubscriptionPlanController::class, 'add'])->name('subscriptions.add');
        Route::post('/add', [SubscriptionPlanController::class, 'insert'])->name('subscriptions.insert');
        Route::get('/{id}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscriptions.edit');
        Route::post('/edit', [SubscriptionPlanController::class, 'update'])->name('subscriptions.update');
        Route::delete('/{id}', [SubscriptionPlanController::class, 'delete'])->name('subscriptions.delete');
    });

    // Users
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{id}', [UserController::class, 'getUserById']);
        Route::post('/edit', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('users.delete');
    });

    // Notifications
    Route::prefix('notifications')->group(function() {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');
    });

    // Pages
    Route::prefix('pages')->group(function() {
        Route::get('/', [PageController::class, 'index'])->name('pages.index');
        Route::get('/add', [PageController::class, 'add'])->name('pages.add');
        Route::post('/add', [PageController::class, 'insert'])->name('pages.insert');
        Route::get('/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::post('/edit', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/{id}', [PageController::class, 'delete'])->name('pages.delete');
    });

    // Email Settings
    Route::prefix('email')->group(function() {
        Route::get('/configuration', [EmailSettingController::class, 'editEmailConfiguration'])->name('email.configuration'); 
        Route::post('/configuration/edit', [EmailSettingController::class, 'updateEmailConfiguration'])->name('email.configuration.update'); 
        Route::get('/templates', [EmailSettingController::class, 'index'])->name('email.templates.index');
        Route::get('/templates/{id}/edit', [EmailSettingController::class, 'edit'])->name('email.templates.edit'); 
        Route::post('/templates/edit', [EmailSettingController::class, 'update'])->name('email.templates.update'); 
        Route::get('/global-template', [EmailSettingController::class, 'editEmailGlobalTemplate'])->name('email.global-template'); 
        Route::post('/global-template/edit', [EmailSettingController::class, 'updateEmailGlobalTemplate'])->name('email.global-template.update'); 
    });

    // Settings
    Route::prefix('settings')->group(function() {
        Route::controller(NotificationSettingsController::class)->group(function () {
            Route::get('/notification', 'notificationSettingsView')->name('settings.notification');
            Route::post('/upload/firebase-credentials', 'uploadFirebaseCredentials')->name('settings.upload.firebase.credentials');
        });
        Route::controller(AdvertisementSettingsController::class)->group(function () {
            Route::get('/advertisement', 'advertisementSettingsView')->name('settings.advertisement');
            Route::post('/advertisement/edit', 'updateAdvertisement')->name('settings.advertisement.update');
        });
        Route::get('/app', [AppSettingController::class, 'appSettings'])->name('settings.app'); 
        Route::post('/app', [AppSettingController::class, 'updateAppSettings'])->name('settings.app.update'); 
    });
});