<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Site
Livewire::setScriptRoute(function ($handle) {
    return Route::get(basename(base_path()) . '/vendor/livewire/livewire/dist/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(basename(base_path()) . '/livewire/update', $handle);
});

// Admin
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [Admin\HomeController::class, 'dashboard'])->name('admin.dashboard');

    // Auth
    Route::get('/login', [Admin\AuthController::class, 'login'])->name('admin.auth.login');
    Route::post('/login', [Admin\AuthController::class, 'handleLogin'])->name('admin.auth.login');
    Route::get('/logout', [Admin\AuthController::class, 'logout'])->name('admin.auth.logout');
    Route::get('/personal', [Admin\AuthController::class, 'personal'])->name('admin.auth.personal');
    Route::put('/personal/update', [Admin\AuthController::class, 'personalUpdate'])->name('admin.auth.personal.update');
    Route::post('/change-password', [Admin\AuthController::class, 'changePassword'])->name('admin.auth.changePassword');

    // Banner
    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', [Admin\BannerController::class, 'index'])->name('admin.banners.index');
        Route::get('/create', [Admin\BannerController::class, 'create'])->name('admin.banners.create');
        Route::post('/', [Admin\BannerController::class, 'store'])->name('admin.banners.store');
        Route::get('/create-by-xml', [Admin\BannerController::class, 'createByXml'])->name('admin.banners.createByXml');
        Route::post('/create-by-xml', [Admin\BannerController::class, 'storeXml'])->name('admin.banners.storeXml');
        Route::get('/{banner}/edit', [Admin\BannerController::class, 'edit'])->name('admin.banners.edit');
        Route::put('/{banner}', [Admin\BannerController::class, 'update'])->name('admin.banners.update');
        Route::get('/export-xml', [Admin\BannerController::class, 'exportXml'])->name('admin.banners.exportXml');
    });

    // Category
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [Admin\CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('/create', [Admin\CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/', [Admin\CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/create-by-xml', [Admin\CategoryController::class, 'createByXml'])->name('admin.categories.createByXml');
        Route::post('/create-by-xml', [Admin\CategoryController::class, 'storeXml'])->name('admin.categories.storeXml');
        Route::get('/{category}/edit', [Admin\CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/{category}', [Admin\CategoryController::class, 'update'])->name('admin.categories.update');
        Route::get('/export-xml', [Admin\CategoryController::class, 'exportXml'])->name('admin.categories.exportXml');
    });

    // Brand
    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', [Admin\BrandController::class, 'index'])->name('admin.brands.index');
        Route::get('/create', [Admin\BrandController::class, 'create'])->name('admin.brands.create');
        Route::post('/', [Admin\BrandController::class, 'store'])->name('admin.brands.store');
        Route::get('/create-by-xml', [Admin\BrandController::class, 'createByXml'])->name('admin.brands.createByXml');
        Route::post('/create-by-xml', [Admin\BrandController::class, 'storeXml'])->name('admin.brands.storeXml');
        Route::get('/{brand}/edit', [Admin\BrandController::class, 'edit'])->name('admin.brands.edit');
        Route::put('/{brand}', [Admin\BrandController::class, 'update'])->name('admin.brands.update');
        Route::get('/export-xml', [Admin\BrandController::class, 'exportXml'])->name('admin.brands.exportXml');
    });

    // Product
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [Admin\ProductController::class, 'index'])->name('admin.products.index');
        Route::get('/create', [Admin\ProductController::class, 'create'])->name('admin.products.create');
        Route::post('/', [Admin\ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/create-by-xml', [Admin\ProductController::class, 'createByXml'])->name('admin.products.createByXml');
        Route::post('/create-by-xml', [Admin\ProductController::class, 'storeXml'])->name('admin.products.storeXml');
        Route::get('/{product}/edit', [Admin\ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/{product}', [Admin\ProductController::class, 'update'])->name('admin.products.update');
        Route::get('/export-xml', [Admin\ProductController::class, 'exportXml'])->name('admin.products.exportXml');
    });

    // Customer
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [Admin\CustomerController::class, 'index'])->name('admin.customers.index');
        Route::get('/{customer}/look', [Admin\CustomerController::class, 'look']);
        Route::get('/{customer}/un-look', [Admin\CustomerController::class, 'unLook']);
        Route::get('/export-xml', [Admin\CustomerController::class, 'exportXml'])->name('admin.customers.exportXml');
    });

    // Employee
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', [Admin\EmployeeController::class, 'index'])->name('admin.employees.index');
        Route::get('/create', [Admin\EmployeeController::class, 'create'])->name('admin.employees.create');
        Route::post('/', [Admin\EmployeeController::class, 'store'])->name('admin.employees.store');
        Route::get('/{employee}/look', [Admin\EmployeeController::class, 'look']);
        Route::get('/{employee}/un-look', [Admin\EmployeeController::class, 'unLook']);
        Route::get('/{employee}/reset-password', [Admin\EmployeeController::class, 'resetPassword'])->name('admin.employees.resetPassword');
        Route::get('/export-xml', [Admin\EmployeeController::class, 'exportXml'])->name('admin.employees.exportXml');
    });

    // Order
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{order}/show', [Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/{order}/change-status/{status}', [Admin\OrderController::class, 'changeStatus'])->name('admin.orders.changeStatus');
        Route::get('/export-xml', [Admin\OrderController::class, 'exportXml'])->name('admin.orders.exportXml');
    });
});
