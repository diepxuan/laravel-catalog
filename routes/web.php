<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-22 12:43:21
 */

use Diepxuan\Catalog\Http\Controllers\CatalogController;
use Diepxuan\Catalog\Http\Controllers\CategoryController;
use Diepxuan\Catalog\Http\Controllers\InventoryController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
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
Route::middleware('clearcache')->group(static function (): void {
    Route::resource('khohang/tonkho', InventoryController::class)->names('inventory');
    Route::resource('khohang/sanpham', CatalogController::class)->names('catalog');
    Route::resource('khohang/nhomsanpham', CategoryController::class)->names('category');

    Route::resource('hethong', SystemController::class)->names('system');

    Route::get('/', [SystemController::class, 'index']);
});
