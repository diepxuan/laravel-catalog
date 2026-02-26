<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-02-25 17:33:38
 */

use Diepxuan\Catalog\Http\Controllers\SellController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
use Diepxuan\Catalog\Http\Controllers\SystemUserController;
use Diepxuan\Catalog\Http\Controllers\SystemWebsiteController;
use Diepxuan\Catalog\Http\Livewire\System\Balance\Index as BalanceIndex;
use Diepxuan\Catalog\Http\Livewire\System\Balance\AccountOpening;
use Diepxuan\Catalog\Http\Livewire\System\Balance\Transfer;
use Diepxuan\Catalog\Http\Livewire\System\Balance\AccountsReceivable;
use Diepxuan\Catalog\Http\Livewire\System\Balance\AccountsPayable;
use Diepxuan\Catalog\Http\Livewire\System\Balance\InventoryOpening;
use Diepxuan\Catalog\Http\Livewire\System\Balance\InventoryOpeningNtxt;
use Diepxuan\Catalog\Http\Livewire\System\Balance\InventoryTransfer;
use Diepxuan\Catalog\Http\Livewire\System\Balance\WorkInProgress;
use Diepxuan\Catalog\Http\Livewire\System\Balance\CumulativeTransactions;
use Diepxuan\Catalog\Http\Livewire\Banhang\Hoadonbanhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\Khachhang;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Chi;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Nganhang;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Thu;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Tienmat;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baoco;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuchi;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuthu;
use Diepxuan\Catalog\Http\Livewire\Gl\Taikhoan;
use Diepxuan\Catalog\Http\Livewire\In\Baocao\Tonkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmnhvt;
use Diepxuan\Catalog\Http\Livewire\In\Dmvt;
use Diepxuan\Catalog\Http\Livewire\Muahang\Cungcap;
use Diepxuan\Catalog\Http\Livewire\System\Dashboard;
use Diepxuan\Catalog\Http\Livewire\System\Menu;
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
// Route::domain(env('APP_URL','portal.diepxuan.io.vn'))->middleware(['clearcache', 'auth'])->group(static function (): void {
Route::middleware(['clearcache', 'auth'])->group(static function (): void {
    Route::get('/gl/taikhoan', Taikhoan::class)->name('gl.taikhoan');

    Route::get('/cash/tienmat/thu', Phieuthu::class)->name('ca.tienmat.thu');
    Route::get('/cash/tienmat/chi', Phieuchi::class)->name('ca.tienmat.chi');
    Route::get('/cash/tienmat/quy', Tienmat::class)->name('ca.tienmat.quy');
    Route::get('/cash/nganhang/baoco', Baoco::class)->name('ca.nganhang.baoco');
    Route::get('/cash/nganhang/baono', Baono::class)->name('ca.nganhang.baono');
    Route::get('/cash/nganhang/quy', Nganhang::class)->name('ca.nganhang.quy');
    Route::get('/cash/thu', Thu::class)->name('ca.thu');
    Route::get('/cash/chi', Chi::class)->name('ca.chi');
    Route::get('/cash/quy', static fn () => view('catalog::dashboard'))->name('ca.quy');

    Route::get('banhang/hoadonbanhang', Hoadonbanhang::class)->name('ar.ph.hdbh');
    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');
    Route::get('/banhang/khachhang', Khachhang::class)->name('ar.khachhang');

    Route::get('/muahang/cungcap', Cungcap::class)->name('ar.cungcap');

    Route::get('khohang/sanpham', Dmvt::class)->name('in.dmvt');
    Route::get('khohang/nhomsanpham', Dmnhvt::class)->name('in.dmnhvt');
    Route::get('khohang/khohang', Dmkho::class)->name('in.khohang');
    Route::get('khohang/tonkho', Tonkho::class)->name('in.tonkho');

    Route::get('hethong/dashboard', Dashboard::class)->name('system.dashboard');
    // Route::resource('hethong/dashboard', SystemController::class)->names('system.dashboard');
    Route::resource('hethong/user', SystemUserController::class)->names('system.user');
    Route::resource('hethong/website', SystemWebsiteController::class)->names('system.website');
    Route::get('hethong/menu', Menu::class)->name('system.menu');

    // Balance Management Routes - Livewire Components
    Route::prefix('hethong/balance')->name('system.balance.')->group(function () {
        Route::get('/', BalanceIndex::class)->name('index');
        Route::get('/account-opening', AccountOpening::class)->name('account-opening');
        Route::get('/transfer', Transfer::class)->name('transfer');
        Route::get('/accounts-receivable', AccountsReceivable::class)->name('accounts-receivable');
        Route::get('/accounts-payable', AccountsPayable::class)->name('accounts-payable');
        Route::get('/inventory-opening', InventoryOpening::class)->name('inventory-opening');
        Route::get('/inventory-opening-ntxt', InventoryOpeningNtxt::class)->name('inventory-opening-ntxt');
        Route::get('/inventory-transfer', InventoryTransfer::class)->name('inventory-transfer');
        Route::get('/work-in-progress', WorkInProgress::class)->name('work-in-progress');
        Route::get('/cumulative-transactions', CumulativeTransactions::class)->name('cumulative-transactions');
    });

    // Route::get('/', [SystemController::class, 'index']);
    Route::get('/', static fn () => view('catalog::dashboard'))->name('home');
    Route::get('/dashboard', static fn () => view('catalog::dashboard'))->name('dashboard');
});
