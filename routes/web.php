<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
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

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

// Auth Route
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Stok
    Route::get('/stok', [StockController::class, 'index'])->name('stok.index');
    Route::get('/stok/create', [StockController::class, 'create'])->name('stok.create');
    Route::get('/stok/{id}/edit', [StockController::class, 'edit'])->name('stok.edit');
    Route::post('/stok', [StockController::class, 'store'])->name('stok.store');
    Route::get('/stok/{id}', [StockController::class, 'show'])->name('stok.show');
    Route::put('/stok/{id}', [StockController::class, 'update'])->name('stok.update');
    Route::delete('/stok/{id}', [StockController::class, 'destroy'])->name('stok.destroy');

    // Transaksi
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/add-to-cart', [TransactionController::class, 'addToCart'])->name('transaksi.addToCart');
    Route::post('/transaksi/remove-from-cart', [TransactionController::class, 'removeFromCart'])->name('transaksi.removeFromCart');
    Route::post('/transaksi/checkout', [TransactionController::class, 'checkout'])->name('transaksi.checkout');

    // Laporan
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::delete('/laporan/{id}', [ReportController::class, 'destroy'])->name('laporan.destroy');
    Route::post('/laporan/generate-monthly', [ReportController::class, 'generateMonthlyReport'])->name('laporan.generate-monthly');
});

