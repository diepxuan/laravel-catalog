<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-13 16:34:16
 */

use Diepxuan\Catalog\Http\Controllers\CatalogController;
use Diepxuan\Catalog\Http\Controllers\CategoryController;
use Diepxuan\Catalog\Http\Controllers\InventoryController;
use Diepxuan\Catalog\Http\Controllers\SellController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
use Diepxuan\Catalog\Http\Controllers\SystemWebsiteController;
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
    Route::resource('banhang/hoadonbanhang', SellController::class)->names('sell');
    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');

    Route::resource('khohang/sanpham', CatalogController::class)->names('catalog');
    Route::resource('khohang/nhomsanpham', CategoryController::class)->names('catalog.category');
    Route::resource('khohang/tonkho', InventoryController::class)->names('catalog.inventory');

    Route::resource('hethong/dashboard', SystemController::class)->names('system');
    Route::resource('hethong/website', SystemWebsiteController::class)->names('system.website');

    // Route::get('/', [SystemController::class, 'index']);
    Route::get('/', static fn () => view('catalog::dashboard'))->name('home');
    Route::get('/dashboard', static fn () => view('catalog::dashboard'))->name('dashboard');
});
