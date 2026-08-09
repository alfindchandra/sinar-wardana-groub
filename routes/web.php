<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortalController;
use App\Livewire\MasterData\CategoryManager;
use App\Livewire\MasterData\ProductForm;
use App\Livewire\MasterData\ProductManager;
use App\Livewire\MasterData\SupplierManager;
use App\Livewire\MasterData\WarehouseManager;
use App\Livewire\Shop\CartPage;
use App\Livewire\Shop\Checkout;
use App\Livewire\Shop\Home as ShopHome;
use App\Livewire\Shop\OrderSuccess;
use App\Livewire\Shop\ProductCatalog;
use App\Livewire\Shop\ProductDetail;
use Illuminate\Support\Facades\Route;

// ==== Storefront Publik (bisa diakses siapa saja, tanpa login) ====
Route::get('/', ShopHome::class)->name('shop.home');
Route::get('/produk', ProductCatalog::class)->name('shop.products');
Route::get('/produk/{product}', ProductDetail::class)->name('shop.products.show');
Route::get('/keranjang', CartPage::class)->name('shop.cart');

// ==== Checkout & Order (butuh login sebagai pelanggan) ====
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', Checkout::class)->name('shop.checkout');
    Route::get('/pesanan/{order}/sukses', OrderSuccess::class)->name('shop.order.success');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/portal/dashboard', [PortalController::class, 'dashboard'])->name('portal.dashboard');

    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/kategori', CategoryManager::class)->name('categories.index');
        Route::get('/supplier', SupplierManager::class)->name('suppliers.index');
        Route::get('/gudang', WarehouseManager::class)->name('warehouses.index');

        Route::get('/produk', ProductManager::class)->name('products.index');
        Route::get('/produk/tambah', ProductForm::class)->name('products.create');
        Route::get('/produk/{product}/edit', ProductForm::class)->name('products.edit');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
