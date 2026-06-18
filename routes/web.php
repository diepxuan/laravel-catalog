<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-18 15:46:26
 */

use Diepxuan\Catalog\Http\Controllers\SellController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
use Diepxuan\Catalog\Http\Controllers\SystemUserController;
use Diepxuan\Catalog\Http\Controllers\SystemWebsiteController;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\Nhomkhachhang;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\NhomkhachhangEdit;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\Phanloaikhachhang;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\PhanloaikhachhangEdit;
use Diepxuan\Catalog\Http\Livewire\Banhang\Hoadonbanhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\Khachhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\KhachhangForm;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Chi;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Nganhang;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Thu;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Tienmat;
use Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc\Nhanvien;
use Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc\NhanvienForm;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baoco;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuchi;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuthu;
use Diepxuan\Catalog\Http\Livewire\Co\Danhmuc\Dinhmucchitiet;
use Diepxuan\Catalog\Http\Livewire\Co\Danhmuc\Dmbom;
use Diepxuan\Catalog\Http\Livewire\Gl\Taikhoan;
use Diepxuan\Catalog\Http\Livewire\Home\Dashboard as DashboardLivewire;
use Diepxuan\Catalog\Http\Livewire\In\Baocao\Tonkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmnhvt;
use Diepxuan\Catalog\Http\Livewire\In\Dmvt;
use Diepxuan\Catalog\Http\Livewire\Muahang\Cungcap;
use Diepxuan\Catalog\Http\Livewire\Muahang\CungcapForm;
use Diepxuan\Catalog\Http\Livewire\Muahang\Hoadonmua;
use Diepxuan\Catalog\Http\Livewire\Muahang\HoadonmuaEdit;
use Diepxuan\Catalog\Http\Livewire\Muahang\PoDmCpIndex;
use Diepxuan\Catalog\Http\Livewire\Muahang\PoVoucherIndex;
use Diepxuan\Catalog\Http\Livewire\System\Balance\AccountOpening;
use Diepxuan\Catalog\Http\Livewire\System\Balance\AccountsPayable;
use Diepxuan\Catalog\Http\Livewire\System\Balance\AccountsReceivable;
use Diepxuan\Catalog\Http\Livewire\System\Balance\CumulativeTransactions;
use Diepxuan\Catalog\Http\Livewire\System\Balance\Index as BalanceIndex;
use Diepxuan\Catalog\Http\Livewire\System\Balance\InventoryOpening;
use Diepxuan\Catalog\Http\Livewire\System\Balance\InventoryOpeningNtxt;
use Diepxuan\Catalog\Http\Livewire\System\Balance\InventoryTransfer;
use Diepxuan\Catalog\Http\Livewire\System\Balance\Transfer;
use Diepxuan\Catalog\Http\Livewire\System\Balance\WorkInProgress;
use Diepxuan\Catalog\Http\Livewire\System\CompanySelector;
use Diepxuan\Catalog\Http\Livewire\System\Dashboard;
use Diepxuan\Catalog\Http\Livewire\System\SimbaPage;
use Diepxuan\Catalog\Http\Livewire\System\YearSelector;
use Diepxuan\Support\Http\Middleware\CorpAutoLogin;
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
Route::middleware([CorpAutoLogin::class])->group(static function (): void {
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
    Route::get('/cash/nhanvien', Nhanvien::class)->name('ca.nhanvien');
    Route::get('/cash/nhanvien/create', NhanvienForm::class)->name('ca.nhanvien.create');
    Route::get('/cash/nhanvien/edit/{id}', NhanvienForm::class)->name('ca.nhanvien.edit');

    Route::get('banhang/hoadonbanhang', Hoadonbanhang::class)->name('ar.ph.hdbh');
    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');
    Route::get('/co/danhmuc/dinh-muc-bom', Dmbom::class)->name('co.dmbom');
    Route::get('/co/danhmuc/dinh-muc-chi-tiet', Dinhmucchitiet::class)->name('co.dinhmucchitiet');

    Route::get('/muahang/cungcap', Cungcap::class)->name('ar.cungcap');
    Route::get('/muahang/nhacungcap', Cungcap::class)->name('po.cungcap');
    Route::get('/muahang/nhacungcap/create', CungcapForm::class)->name('po.cungcap.create');
    Route::get('/muahang/nhacungcap/edit/{id}', CungcapForm::class)->name('po.cungcap.edit');
    Route::get('/muahang/chiphimuahang/danhmuc', PoDmCpIndex::class)->name('po.dmcp');

    // Route::prefix('muahang')->name('muahang.')->group(static function (): void {
    //     Route::get('/hoadonmua', Hoadonmua::class)->name('hoadonmua');
    //     Route::get('/hoadonmua/create', HoadonmuaEdit::class)->name('hoadonmua.create');
    //     Route::get('/hoadonmua/edit/{stt_rec}', HoadonmuaEdit::class)->name('hoadonmua.edit');
    //     Route::get('/donhangmua', PoVoucherIndex::class)->defaults('voucherCode', 'PO1')->name('po1');
    //     Route::get('/chiphimuahang', PoVoucherIndex::class)->defaults('voucherCode', 'PO4')->name('po4');
    //     Route::get('/xuat-tralai-nhacungcap', PoVoucherIndex::class)->defaults('voucherCode', 'PO5')->name('po5');
    //     Route::get('/hoadonmuadichvu', PoVoucherIndex::class)->defaults('voucherCode', 'PO6')->name('po6');
    //     Route::get('/hoadonmuanhapkhau', PoVoucherIndex::class)->defaults('voucherCode', 'PO7')->name('po7');
    // });

    // Source routes for SimbaERP screens. Canonical /simba/... aliases render
    // through SimbaPage; these internal named routes preserve component/defaults.
    Route::prefix('_simba-source')->group(static function (): void {
        // SO — Bán hàng (sysMenu 06.*)
        Route::prefix('so')->group(static function (): void {
            Route::prefix('dict')->group(static function (): void {
                Route::get('/khachhang', Khachhang::class)->name('so.dict.ardmkh');
                Route::get('/khachhang/create', KhachhangForm::class)->name('so.dict.ardmkh.create');
                Route::get('/khachhang/edit/{id}', KhachhangForm::class)->name('so.dict.ardmkh.edit');
                Route::get('/phanloai-khachhang', Phanloaikhachhang::class)->name('so.dict.ardmplkh');
                Route::get('/phanloai-khachhang/create', PhanloaikhachhangEdit::class)->name('so.dict.ardmplkh.create');
                Route::get('/phanloai-khachhang/edit/{id}', PhanloaikhachhangEdit::class)->name('so.dict.ardmplkh.edit');
                Route::get('/nhom-khachhang', Nhomkhachhang::class)->name('so.dict.ardmnhkh');
                Route::get('/nhom-khachhang/create', NhomkhachhangEdit::class)->name('so.dict.ardmnhkh.create');
                Route::get('/nhom-khachhang/edit/{id}', NhomkhachhangEdit::class)->name('so.dict.ardmnhkh.edit');
            });
        });

        // IN — Hàng tồn kho (sysMenu 14.*)
        Route::prefix('in')->group(static function (): void {
            Route::prefix('rpt')->group(static function (): void {
                Route::get('/tonkho', Tonkho::class)->name('in.tonkho');
            });

            Route::prefix('dict')->group(static function (): void {
                Route::get('/dmvt', Dmvt::class)->name('in.dict.dmvt');
                Route::get('/dmnhvt', Dmnhvt::class)->name('in.dict.dmnhvt');
                Route::get('/dmkho', Dmkho::class)->name('in.dict.dmkho');
            });
        });
    });

    // Canonical Simba shell entrypoint. Source routes above keep component/defaults;
    // public /simba/... URLs always render through SimbaPage.
    Route::prefix('simba')->name('simba.')->group(static function (): void {
        Route::get('/', SimbaPage::class)->name('index');
        Route::get('/{module}/{kind}', SimbaPage::class)
            ->where([
                'module' => '[a-z0-9-]+',
                'kind'   => '[a-z0-9-]+',
            ])
            ->name('show-short')
        ;
        Route::get('/{module}/{kind}/{slug}', SimbaPage::class)
            ->where([
                'module' => '[a-z0-9-]+',
                'kind'   => '[a-z0-9-]+',
                'slug'   => '[a-z0-9_.-]+',
            ])
            ->name('show')
        ;
    });

    Route::prefix('hethong')->name('system.')->group(static function (): void {
        Route::get('system', Dashboard::class)->name('system');
        Route::get('company', CompanySelector::class)->name('company');
        Route::resource('user', SystemUserController::class)->names('user');
        Route::resource('website', SystemWebsiteController::class)->names('website');
        Route::get('year', YearSelector::class)->name('year');

        // Balance Management Routes - Livewire Components
        Route::prefix('balance')->name('balance.')->group(static function (): void {
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
    });

    // Route::get('/', [SystemController::class, 'index']);
    Route::get('/', static fn () => view('catalog::dashboard'))->name('home');
    Route::get('/dashboard', DashboardLivewire::class)->name('dashboard');
});

Route::get('/check-proxy', static fn () => [
    'app_url'           => config('app.url'),
    'url()'             => url('/'),
    'secure_url()'      => secure_url('/'),
    'request_scheme'    => request()->getScheme(),
    'request_secure'    => request()->isSecure(),
    'x_forwarded_proto' => request()->header('x-forwarded-proto'),
    'server_https'      => $_SERVER['HTTPS'] ?? null,
]);

/*
|--------------------------------------------------------------------------
| Test Routes - UTF-8 Blade Test
|--------------------------------------------------------------------------
*/

Route::get('test/utf8', static fn () => view('catalog::test.utf8-test'))->name('test.utf8')->middleware(['web']);
