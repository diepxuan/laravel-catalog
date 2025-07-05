<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-03 19:22:04
 */

use Diepxuan\Catalog\Http\Api\SoPh3Controller;
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

// Route::domain('portal.diepxuan.io.vn')->middleware(['auth:sanctum'])->prefix('v1')->group(static function (): void {
Route::middleware(['auth:sanctum'])->prefix('v1')->group(static function (): void {
    Route::apiResource('products', CatalogController::class)->names('api.product');
    Route::apiResource('categories', CategoryController::class)->names('api.category');

    Route::apiResource('SoPh3', SoPh3Controller::class);
});
