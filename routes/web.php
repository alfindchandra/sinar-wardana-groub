<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortalController;
use App\Livewire\MasterData\CategoryManager;
use App\Livewire\MasterData\ProductForm;
use App\Livewire\MasterData\ProductManager;
use App\Livewire\MasterData\SupplierManager;
use App\Livewire\MasterData\WarehouseManager;
use App\Livewire\Admin\SalesOrderManager;
use App\Livewire\Admin\SalesTargetManager;
use App\Livewire\Admin\StoreManager;
use App\Livewire\Admin\StoreMap;
use App\Livewire\Sales\CreateOrder;
use App\Livewire\Sales\Dashboard as SalesDashboard;
use App\Livewire\Sales\MyTargets;
use App\Livewire\Sales\OrderDetail;
use App\Livewire\Sales\OrderList;
use App\Livewire\Sales\Profile as SalesProfile;
use App\Livewire\Sales\StoreDetail;
use App\Livewire\Sales\StoreList;
use App\Livewire\Sales\StoreRegister;
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

    // ==== Sales App (Mobile) ====
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', SalesDashboard::class)->name('dashboard');

        // Toko
        Route::get('/toko', StoreList::class)->name('stores.index');
        Route::get('/toko/daftar', StoreRegister::class)->name('stores.register');
        Route::get('/toko/{customer}', StoreDetail::class)->name('stores.show');

        // Order
        Route::get('/order', OrderList::class)->name('orders.index');
        Route::get('/order/baru/{customer?}', CreateOrder::class)->name('orders.create');
        Route::get('/order/{salesOrder}', OrderDetail::class)->name('orders.show');

        // Target & Profil
        Route::get('/target', MyTargets::class)->name('targets');
        Route::get('/profil', SalesProfile::class)->name('profile');
    });

    // ==== Admin Panel ====
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/toko', StoreManager::class)->name('stores.index');
        Route::get('/toko/peta', StoreMap::class)->name('stores.map');
        Route::get('/orderan-sales', SalesOrderManager::class)->name('sales-orders.index');
        Route::get('/target-sales', SalesTargetManager::class)->name('sales-targets.index');
    });

    // ==== Master Data ====
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
