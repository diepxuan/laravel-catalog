<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-27 19:24:59
 */

use Diepxuan\Catalog\Http\Controllers\CatalogController;
use Diepxuan\Catalog\Http\Controllers\CategoryController;
use Diepxuan\Catalog\Http\Controllers\InventoryController;
use Diepxuan\Catalog\Http\Controllers\SellController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
use Diepxuan\Catalog\Http\Controllers\SystemUserController;
use Diepxuan\Catalog\Http\Controllers\SystemWebsiteController;
use Diepxuan\Catalog\Http\Livewire\Gl\Taikhoan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::domain('portal.diepxuan.io.vn')->middleware(['clearcache', 'auth'])->group(static function (): void {
    Route::get('/gl/taikhoan', Taikhoan::class)->name('gl.taikhoan');

    Route::get('/cash/tienmat/thu', static fn () => view('catalog::cash.tienmat.phieuthu'))->name('ca.tienmat.thu');
    Route::get('/cash/tienmat/chi', static fn () => view('catalog::cash.tienmat.phieuchi'))->name('ca.tienmat.chi');
    Route::get('/cash/tienmat/quy', static fn () => view('catalog::dashboard'))->name('ca.tienmat.quy');
    Route::get('/cash/nganhang/baoco', static fn () => view('catalog::dashboard'))->name('ca.nganhang.baoco');
    Route::get('/cash/nganhang/baono', static fn () => view('catalog::dashboard'))->name('ca.nganhang.baono');
    Route::get('/cash/nganhang/quy', static fn () => view('catalog::dashboard'))->name('ca.nganhang.quy');
    Route::get('/cash/thu', static fn () => view('catalog::dashboard'))->name('ca.thu');
    Route::get('/cash/chi', static fn () => view('catalog::dashboard'))->name('ca.chi');
    Route::get('/cash/quy', static fn () => view('catalog::dashboard'))->name('ca.quy');

    Route::resource('banhang/hoadonbanhang', SellController::class)->names('sell');
    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');

    Route::resource('khohang/sanpham', CatalogController::class)->names('catalog');
    Route::resource('khohang/nhomsanpham', CategoryController::class)->names('catalog.category');
    Route::resource('khohang/tonkho', InventoryController::class)->names('catalog.inventory');

    Route::resource('hethong/dashboard', SystemController::class)->names('system');
    Route::resource('hethong/user', SystemUserController::class)->names('system.user');
    Route::resource('hethong/website', SystemWebsiteController::class)->names('system.website');

    // Route::get('/', [SystemController::class, 'index']);
    Route::get('/', static fn () => view('catalog::dashboard'))->name('home');
    Route::get('/dashboard', static fn () => view('catalog::dashboard'))->name('dashboard');
});
