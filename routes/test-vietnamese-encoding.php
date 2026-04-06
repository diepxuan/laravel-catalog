<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-04-06 19:13:35
 */

use Diepxuan\Catalog\Http\Controllers\Test\VietnameseEncodingTestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test Routes - Vietnamese Encoding
|--------------------------------------------------------------------------
|
| Routes for testing Vietnamese encoding with SQL Server.
| Only available in development environment.
|
*/

Route::prefix('test/vietnamese-encoding')
    ->middleware(['web', 'auth'])
    ->group(static function (): void {
        // Main page
        Route::get('/', [VietnameseEncodingTestController::class, 'index'])
            ->name('test.vietnamese-encoding.index')
        ;

        // Create test table
        Route::post('/create-table', [VietnameseEncodingTestController::class, 'createTestTable'])
            ->name('test.vietnamese-encoding.create-table')
        ;

        // Run test
        Route::post('/run', [VietnameseEncodingTestController::class, 'testInsert'])
            ->name('test.vietnamese-encoding.run')
        ;

        // Cleanup
        Route::post('/cleanup', [VietnameseEncodingTestController::class, 'cleanup'])
            ->name('test.vietnamese-encoding.cleanup');
    });
