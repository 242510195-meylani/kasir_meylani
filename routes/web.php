
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

// Login dan logout
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    return auth()->check()
        ? redirect('/home')
        : redirect()->route('login');
});

// Route setelah login
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

    // Keranjang belanja
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');

    Route::patch('/cart/{hash}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{hash}', [CartController::class, 'destroy'])
        ->name('cart.destroy');

    Route::delete('/cart', [CartController::class, 'clear'])
        ->name('cart.clear');

    // Profil
    Route::get('/profile', function () {
        return redirect('/home');
    })->name('profile.show');

    // Pencarian produk dan pelanggan
    Route::get('/transaksi/produk', [TransaksiController::class, 'produk'])
        ->name('transaksi.produk');

    Route::get('/transaksi/pelanggan', [TransaksiController::class, 'pelanggan'])
        ->name('transaksi.pelanggan');

    Route::post('/transaksi/pelanggan', [TransaksiController::class, 'addPelanggan'])
        ->name('transaksi.pelanggan.pilih');

    // Cetak transaksi
    Route::get('/transaksi/{transaksi}/cetak', [TransaksiController::class, 'cetak'])
        ->name('transaksi.cetak');

    // CRUD transaksi
    Route::resource('transaksi', TransaksiController::class);

    // CRUD produk
    Route::resource('produk', ProdukController::class);

    // CRUD kategori
    Route::resource('kategori', KategoriController::class);

    // CRUD pelanggan
    Route::resource('pelanggan', PelangganController::class);

    // Pencarian produk untuk stok
    Route::get('/stok/produk', [StokController::class, 'produk'])
        ->name('stok.produk');

    // CRUD stok
    Route::resource('stok', StokController::class);

    // CRUD user
    Route::resource('user', UserController::class);
});
