<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-11 18:43:50
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

    Route::resource('khohang/tonkho', InventoryController::class)->names('inventory');
    Route::resource('khohang/sanpham', CatalogController::class)->names('catalog');
    Route::resource('khohang/nhomsanpham', CategoryController::class)->names('diepxuan.category');

    Route::resource('hethong/dashboard', SystemController::class)->names('system');
    Route::resource('hethong/website', SystemWebsiteController::class)->names('systemwebsite');

    Route::get('/', [SystemController::class, 'index']);
});
