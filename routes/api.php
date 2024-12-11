<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-11 11:52:52
 */

use Diepxuan\Catalog\Http\Controllers\CatalogController;
use Diepxuan\Catalog\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(static function (): void {
    // Route::apiResource('catalog', CatalogController::class)->names('catalog');
    // Route::get('/api/products', [ProductController::class, 'getProducts']);
    Route::apiResource('khohang/nhomsanpham', CategoryController::class)->names('api.category');
});
