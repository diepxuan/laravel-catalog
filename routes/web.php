<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-20 11:17:03
 */

use Diepxuan\Catalog\Http\Controllers\SellController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
use Diepxuan\Catalog\Http\Controllers\SystemUserController;
use Diepxuan\Catalog\Http\Controllers\SystemWebsiteController;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\Phanloaikhachhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\Hoadonbanhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\Khachhang;
use Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc\Nhanvien;
use Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc\NhanvienForm;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baoco;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuchi;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuthu;
use Diepxuan\Catalog\Http\Livewire\Gl\Taikhoan;
use Diepxuan\Catalog\Http\Livewire\Home\Dashboard as DashboardLivewire;
use Diepxuan\Catalog\Http\Livewire\In\Dict\IndmvtPage;
use Diepxuan\Catalog\Http\Livewire\In\Dmkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmnhvt;
use Diepxuan\Catalog\Http\Livewire\In\Rpt\Inrptcd02;
use Diepxuan\Catalog\Http\Livewire\Muahang\PoDmCpIndex;
use Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh;
use Diepxuan\Catalog\Http\Livewire\Po\Dict\ArdmkhForm;
use Diepxuan\Catalog\Http\Livewire\Si\Vch\Smks;
use Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01;
use Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01Sl as SoArrptbccn01Sl;
use Diepxuan\Catalog\Http\Livewire\Po\Rpt\Arrptbccn01Sl as PoArrptbccn01Sl;
use Diepxuan\Catalog\Http\Livewire\Po\Vch\Povchpo3;
use Diepxuan\Catalog\Http\Livewire\Po\Vch\Povchpo3Edit;
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
    Route::get('/cash/nhanvien/create', NhanvienForm::class)->name('ca.nhanvien.create');
    Route::get('/cash/nhanvien/edit/{id}', NhanvienForm::class)->name('ca.nhanvien.edit');

    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');

    // Source routes for SimbaERP screens generated from simba-docs/data/sysMenu.md + zsysmenu.md.
    // Menus with concrete Livewire screens point to the component; the rest render the metadata shell.
    Route::prefix('_simba-source')->group(static function (): void {
        Route::redirect('{path}', '/simba/{path}')
            ->where('path', '.*')
        ;

        $sourceRoutes = [
            ['uri' => 'ca/dict/ardmkh', 'name' => 'ca.dict.ardmkh', 'module' => 'ca', 'kind' => 'dict', 'slug' => 'ardmkh', 'component' => Nhanvien::class],
            // ['uri' => 'ca/dict/sidmngh', 'name' => 'ca.dict.sidmngh', 'module' => 'ca', 'kind' => 'dict', 'slug' => 'sidmngh', 'component' => SimbaPage::class],
            // ['uri' => 'ca/dict/sidmnt', 'name' => 'ca.dict.sidmnt', 'module' => 'ca', 'kind' => 'dict', 'slug' => 'sidmnt', 'component' => SimbaPage::class],
            // ['uri' => 'ca/dict/sidmtgnt', 'name' => 'ca.dict.sidmtgnt', 'module' => 'ca', 'kind' => 'dict', 'slug' => 'sidmtgnt', 'component' => SimbaPage::class],
            // ['uri' => 'ca/rpt/carpttmnh01', 'name' => 'ca.rpt.carpttmnh01', 'module' => 'ca', 'kind' => 'rpt', 'slug' => 'carpttmnh01', 'component' => SimbaPage::class],
            // ['uri' => 'ca/rpt/carpttmnh02', 'name' => 'ca.rpt.carpttmnh02', 'module' => 'ca', 'kind' => 'rpt', 'slug' => 'carpttmnh02', 'component' => SimbaPage::class],
            // ['uri' => 'ca/rpt/carpttmnh06', 'name' => 'ca.rpt.carpttmnh06', 'module' => 'ca', 'kind' => 'rpt', 'slug' => 'carpttmnh06', 'component' => SimbaPage::class],
            // ['uri' => 'ca/rpt/glrptbk01', 'name' => 'ca.rpt.glrptbk01', 'module' => 'ca', 'kind' => 'rpt', 'slug' => 'glrptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'ca/rpt/glrptnkc03', 'name' => 'ca.rpt.glrptnkc03', 'module' => 'ca', 'kind' => 'rpt', 'slug' => 'glrptnkc03', 'component' => SimbaPage::class],
            ['uri' => 'ca/vch/cavchca1', 'name' => 'ca.vch.cavchca1', 'module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca1', 'component' => Phieuthu::class],
            ['uri' => 'ca/vch/cavchca2', 'name' => 'ca.vch.cavchca2', 'module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca2', 'component' => Phieuchi::class],
            // ['uri' => 'ca/vch/cavchca3', 'name' => 'ca.vch.cavchca3', 'module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca3', 'component' => SimbaPage::class],
            ['uri' => 'ca/vch/cavchca4', 'name' => 'ca.vch.cavchca4', 'module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca4', 'component' => Baoco::class],
            // ['uri' => 'co/dict/codmbom', 'name' => 'co.dict.codmbom', 'module' => 'co', 'kind' => 'dict', 'slug' => 'codmbom', 'component' => SimbaPage::class],
            // ['uri' => 'co/dict/codmnhspct', 'name' => 'co.dict.codmnhspct', 'module' => 'co', 'kind' => 'dict', 'slug' => 'codmnhspct', 'component' => SimbaPage::class],
            // ['uri' => 'co/dict/codmpb', 'name' => 'co.dict.codmpb', 'module' => 'co', 'kind' => 'dict', 'slug' => 'codmpb', 'component' => SimbaPage::class],
            // ['uri' => 'co/dict/codmspct', 'name' => 'co.dict.codmspct', 'module' => 'co', 'kind' => 'dict', 'slug' => 'codmspct', 'component' => SimbaPage::class],
            // ['uri' => 'co/dict/gldmkc', 'name' => 'co.dict.gldmkc', 'module' => 'co', 'kind' => 'dict', 'slug' => 'gldmkc', 'component' => SimbaPage::class],
            // ['uri' => 'co/proc/codd1', 'name' => 'co.proc.codd1', 'module' => 'co', 'kind' => 'proc', 'slug' => 'codd1', 'component' => SimbaPage::class],
            // ['uri' => 'co/proc/colk', 'name' => 'co.proc.colk', 'module' => 'co', 'kind' => 'proc', 'slug' => 'colk', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/arrptbccn04', 'name' => 'co.rpt.arrptbccn04', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'arrptbccn04', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/corptctpbcptt', 'name' => 'co.rpt.corptctpbcptt', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'corptctpbcptt', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/corptgt01', 'name' => 'co.rpt.corptgt01', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'corptgt01', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/corptgt02', 'name' => 'co.rpt.corptgt02', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'corptgt02', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/corptgt03', 'name' => 'co.rpt.corptgt03', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'corptgt03', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/glrptbk01', 'name' => 'co.rpt.glrptbk01', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'glrptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/glrptth01', 'name' => 'co.rpt.glrptth01', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'glrptth01', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/inrptbkn04', 'name' => 'co.rpt.inrptbkn04', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'inrptbkn04', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/inrptbkx04', 'name' => 'co.rpt.inrptbkx04', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'inrptbkx04', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/inrptthn01', 'name' => 'co.rpt.inrptthn01', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'inrptthn01', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/inrptthn02', 'name' => 'co.rpt.inrptthn02', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'inrptthn02', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/inrptthx01', 'name' => 'co.rpt.inrptthx01', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'inrptthx01', 'component' => SimbaPage::class],
            // ['uri' => 'co/rpt/inrptthx02', 'name' => 'co.rpt.inrptthx02', 'module' => 'co', 'kind' => 'rpt', 'slug' => 'inrptthx02', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/cocalccost', 'name' => 'co.vch.cocalccost', 'module' => 'co', 'kind' => 'vch', 'slug' => 'cocalccost', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/codelpostcp2gl', 'name' => 'co.vch.codelpostcp2gl', 'module' => 'co', 'kind' => 'vch', 'slug' => 'codelpostcp2gl', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/codmcptt', 'name' => 'co.vch.codmcptt', 'module' => 'co', 'kind' => 'vch', 'slug' => 'codmcptt', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/congungkhcptt', 'name' => 'co.vch.congungkhcptt', 'module' => 'co', 'kind' => 'vch', 'slug' => 'congungkhcptt', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/copb', 'name' => 'co.vch.copb', 'module' => 'co', 'kind' => 'vch', 'slug' => 'copb', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/copostcptt2gl', 'name' => 'co.vch.copostcptt2gl', 'module' => 'co', 'kind' => 'vch', 'slug' => 'copostcptt2gl', 'component' => SimbaPage::class],
            // ['uri' => 'co/vch/glpost2glkc', 'name' => 'co.vch.glpost2glkc', 'module' => 'co', 'kind' => 'vch', 'slug' => 'glpost2glkc', 'component' => SimbaPage::class],
            // ['uri' => 'fa/dict/fadmbpsd', 'name' => 'fa.dict.fadmbpsd', 'module' => 'fa', 'kind' => 'dict', 'slug' => 'fadmbpsd', 'component' => SimbaPage::class],
            // ['uri' => 'fa/dict/fadmldts', 'name' => 'fa.dict.fadmldts', 'module' => 'fa', 'kind' => 'dict', 'slug' => 'fadmldts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/dict/fadmnhts', 'name' => 'fa.dict.fadmnhts', 'module' => 'fa', 'kind' => 'dict', 'slug' => 'fadmnhts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/dict/fadmnv', 'name' => 'fa.dict.fadmnv', 'module' => 'fa', 'kind' => 'dict', 'slug' => 'fadmnv', 'component' => SimbaPage::class],
            // ['uri' => 'fa/dict/sisysvars', 'name' => 'fa.dict.sisysvars', 'module' => 'fa', 'kind' => 'dict', 'slug' => 'sisysvars', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcpt10', 'name' => 'fa.rpt.farptbcpt10', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcpt10', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts01', 'name' => 'fa.rpt.farptbcts01', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts01', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts04', 'name' => 'fa.rpt.farptbcts04', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts04', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts05', 'name' => 'fa.rpt.farptbcts05', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts05', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts06', 'name' => 'fa.rpt.farptbcts06', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts06', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts07', 'name' => 'fa.rpt.farptbcts07', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts07', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts08', 'name' => 'fa.rpt.farptbcts08', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts08', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/farptbcts09', 'name' => 'fa.rpt.farptbcts09', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'farptbcts09', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/inrptbkn04', 'name' => 'fa.rpt.inrptbkn04', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'inrptbkn04', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/inrptbkx04', 'name' => 'fa.rpt.inrptbkx04', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'inrptbkx04', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/inrptthn01', 'name' => 'fa.rpt.inrptthn01', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'inrptthn01', 'component' => SimbaPage::class],
            // ['uri' => 'fa/rpt/inrptthx01', 'name' => 'fa.rpt.inrptthx01', 'module' => 'fa', 'kind' => 'rpt', 'slug' => 'inrptthx01', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/faadjustkhts', 'name' => 'fa.vch.faadjustkhts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'faadjustkhts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fabhcc', 'name' => 'fa.vch.fabhcc', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fabhcc', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/facalkhts', 'name' => 'fa.vch.facalkhts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'facalkhts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fachuyents', 'name' => 'fa.vch.fachuyents', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fachuyents', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fadelcc2gl', 'name' => 'fa.vch.fadelcc2gl', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fadelcc2gl', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fadelkhts', 'name' => 'fa.vch.fadelkhts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fadelkhts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fadelts2gl', 'name' => 'fa.vch.fadelts2gl', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fadelts2gl', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fadgts', 'name' => 'fa.vch.fadgts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fadgts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fadmcc', 'name' => 'fa.vch.fadmcc', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fadmcc', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fadmts', 'name' => 'fa.vch.fadmts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fadmts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fagiamts', 'name' => 'fa.vch.fagiamts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fagiamts', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fapostcc2gl', 'name' => 'fa.vch.fapostcc2gl', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fapostcc2gl', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fapostts2gl', 'name' => 'fa.vch.fapostts2gl', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fapostts2gl', 'component' => SimbaPage::class],
            // ['uri' => 'fa/vch/fathoikhts', 'name' => 'fa.vch.fathoikhts', 'module' => 'fa', 'kind' => 'vch', 'slug' => 'fathoikhts', 'component' => SimbaPage::class],
            // ['uri' => 'gl/dict/gldmkc', 'name' => 'gl.dict.gldmkc', 'module' => 'gl', 'kind' => 'dict', 'slug' => 'gldmkc', 'component' => SimbaPage::class],
            ['uri' => 'gl/dict/gldmtk', 'name' => 'gl.dict.gldmtk', 'module' => 'gl', 'kind' => 'dict', 'slug' => 'gldmtk', 'component' => Taikhoan::class],
            // ['uri' => 'gl/dict/sidmbp', 'name' => 'gl.dict.sidmbp', 'module' => 'gl', 'kind' => 'dict', 'slug' => 'sidmbp', 'component' => SimbaPage::class],
            // ['uri' => 'gl/dict/sidmphi', 'name' => 'gl.dict.sidmphi', 'module' => 'gl', 'kind' => 'dict', 'slug' => 'sidmphi', 'component' => SimbaPage::class],
            // ['uri' => 'gl/proc/glcdtk', 'name' => 'gl.proc.glcdtk', 'module' => 'gl', 'kind' => 'proc', 'slug' => 'glcdtk', 'component' => SimbaPage::class],
            // ['uri' => 'gl/proc/gltransferglcdtk', 'name' => 'gl.proc.gltransferglcdtk', 'module' => 'gl', 'kind' => 'proc', 'slug' => 'gltransferglcdtk', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/gldkctgs', 'name' => 'gl.rpt.gldkctgs', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'gldkctgs', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/gldmctgs', 'name' => 'gl.rpt.gldmctgs', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'gldmctgs', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbcpt02', 'name' => 'gl.rpt.glrptbcpt02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbcpt02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbcpt03', 'name' => 'gl.rpt.glrptbcpt03', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbcpt03', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbcpt04', 'name' => 'gl.rpt.glrptbcpt04', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbcpt04', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbcpt05', 'name' => 'gl.rpt.glrptbcpt05', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbcpt05', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbcpt08', 'name' => 'gl.rpt.glrptbcpt08', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbcpt08', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbct01', 'name' => 'gl.rpt.glrptbct01', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbct01', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbct02', 'name' => 'gl.rpt.glrptbct02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbct02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbct03', 'name' => 'gl.rpt.glrptbct03', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbct03', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbct04', 'name' => 'gl.rpt.glrptbct04', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbct04', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbct05', 'name' => 'gl.rpt.glrptbct05', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbct05', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbct06', 'name' => 'gl.rpt.glrptbct06', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbct06', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctc01', 'name' => 'gl.rpt.glrptbctc01', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctc01', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctc02', 'name' => 'gl.rpt.glrptbctc02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctc02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctc03', 'name' => 'gl.rpt.glrptbctc03', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctc03', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctc04', 'name' => 'gl.rpt.glrptbctc04', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctc04', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtb02', 'name' => 'gl.rpt.glrptbctccdtb02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtb02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtb03', 'name' => 'gl.rpt.glrptbctccdtb03', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtb03', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtf02', 'name' => 'gl.rpt.glrptbctccdtf02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtf02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtf03a', 'name' => 'gl.rpt.glrptbctccdtf03a', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtf03a', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtf03b', 'name' => 'gl.rpt.glrptbctccdtf03b', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtf03b', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtf03c', 'name' => 'gl.rpt.glrptbctccdtf03c', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtf03c', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbctccdtf03d', 'name' => 'gl.rpt.glrptbctccdtf03d', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbctccdtf03d', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptbk01', 'name' => 'gl.rpt.glrptbk01', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptctgs01', 'name' => 'gl.rpt.glrptctgs01', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptctgs01', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptctgs02', 'name' => 'gl.rpt.glrptctgs02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptctgs02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptctgs03', 'name' => 'gl.rpt.glrptctgs03', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptctgs03', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptctgs05', 'name' => 'gl.rpt.glrptctgs05', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptctgs05', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc01all', 'name' => 'gl.rpt.glrptnkc01all', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc01all', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc02', 'name' => 'gl.rpt.glrptnkc02', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc02', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc03', 'name' => 'gl.rpt.glrptnkc03', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc03', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc04', 'name' => 'gl.rpt.glrptnkc04', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc04', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc05', 'name' => 'gl.rpt.glrptnkc05', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc05', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc06', 'name' => 'gl.rpt.glrptnkc06', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc06', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptnkc07', 'name' => 'gl.rpt.glrptnkc07', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptnkc07', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrptth01', 'name' => 'gl.rpt.glrptth01', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrptth01', 'component' => SimbaPage::class],
            // ['uri' => 'gl/rpt/glrpttmbctc', 'name' => 'gl.rpt.glrpttmbctc', 'module' => 'gl', 'kind' => 'rpt', 'slug' => 'glrpttmbctc', 'component' => SimbaPage::class],
            // ['uri' => 'gl/vch/glbudget', 'name' => 'gl.vch.glbudget', 'module' => 'gl', 'kind' => 'vch', 'slug' => 'glbudget', 'component' => SimbaPage::class],
            // ['uri' => 'gl/vch/glnb', 'name' => 'gl.vch.glnb', 'module' => 'gl', 'kind' => 'vch', 'slug' => 'glnb', 'component' => SimbaPage::class],
            // ['uri' => 'gl/vch/glpost2glkc', 'name' => 'gl.vch.glpost2glkc', 'module' => 'gl', 'kind' => 'vch', 'slug' => 'glpost2glkc', 'component' => SimbaPage::class],
            // ['uri' => 'gl/vch/glvchgl1', 'name' => 'gl.vch.glvchgl1', 'module' => 'gl', 'kind' => 'vch', 'slug' => 'glvchgl1', 'component' => SimbaPage::class],
            // ['uri' => 'in/dict/codmspct', 'name' => 'in.dict.codmspct', 'module' => 'in', 'kind' => 'dict', 'slug' => 'codmspct', 'component' => SimbaPage::class],
            // ['uri' => 'in/dict/fadmbpsd', 'name' => 'in.dict.fadmbpsd', 'module' => 'in', 'kind' => 'dict', 'slug' => 'fadmbpsd', 'component' => SimbaPage::class],
            ['uri' => 'in/dict/indmkho', 'name' => 'in.dict.indmkho', 'module' => 'in', 'kind' => 'dict', 'slug' => 'indmkho', 'component' => Dmkho::class],
            // ['uri' => 'in/dict/indmlo', 'name' => 'in.dict.indmlo', 'module' => 'in', 'kind' => 'dict', 'slug' => 'indmlo', 'component' => SimbaPage::class],
            ['uri' => 'in/dict/indmnhvt', 'name' => 'in.dict.indmnhvt', 'module' => 'in', 'kind' => 'dict', 'slug' => 'indmnhvt', 'component' => Dmnhvt::class],
            ['uri' => 'in/dict/indmvt', 'name' => 'in.dict.indmvt', 'module' => 'in', 'kind' => 'dict', 'slug' => 'indmvt', 'component' => IndmvtPage::class],
            // ['uri' => 'in/dict/sidmhd', 'name' => 'in.dict.sidmhd', 'module' => 'in', 'kind' => 'dict', 'slug' => 'sidmhd', 'component' => SimbaPage::class],
            // ['uri' => 'in/dict/sidmnhhd', 'name' => 'in.dict.sidmnhhd', 'module' => 'in', 'kind' => 'dict', 'slug' => 'sidmnhhd', 'component' => SimbaPage::class],
            // ['uri' => 'in/dict/sisysvars', 'name' => 'in.dict.sisysvars', 'module' => 'in', 'kind' => 'dict', 'slug' => 'sisysvars', 'component' => SimbaPage::class],
            // ['uri' => 'in/proc/incdfifo', 'name' => 'in.proc.incdfifo', 'module' => 'in', 'kind' => 'proc', 'slug' => 'incdfifo', 'component' => SimbaPage::class],
            // ['uri' => 'in/proc/incdvt', 'name' => 'in.proc.incdvt', 'module' => 'in', 'kind' => 'proc', 'slug' => 'incdvt', 'component' => SimbaPage::class],
            // ['uri' => 'in/proc/intransferincdvt', 'name' => 'in.proc.intransferincdvt', 'module' => 'in', 'kind' => 'proc', 'slug' => 'intransferincdvt', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/dynamicreport', 'name' => 'in.rpt.dynamicreport', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'dynamicreport', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptbk01', 'name' => 'in.rpt.inrptbk01', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptbkn04', 'name' => 'in.rpt.inrptbkn04', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptbkn04', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptbkx04', 'name' => 'in.rpt.inrptbkx04', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptbkx04', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptcd01', 'name' => 'in.rpt.inrptcd01', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptcd01', 'component' => SimbaPage::class],
            ['uri' => 'in/rpt/inrptcd02', 'name' => 'in.rpt.inrptcd02', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptcd02', 'component' => Inrptcd02::class],
            // ['uri' => 'in/rpt/inrptctvt01', 'name' => 'in.rpt.inrptctvt01', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptctvt01', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptctvt02', 'name' => 'in.rpt.inrptctvt02', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptctvt02', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptthn01', 'name' => 'in.rpt.inrptthn01', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptthn01', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptthn02', 'name' => 'in.rpt.inrptthn02', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptthn02', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptthx01', 'name' => 'in.rpt.inrptthx01', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptthx01', 'component' => SimbaPage::class],
            // ['uri' => 'in/rpt/inrptthx02', 'name' => 'in.rpt.inrptthx02', 'module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptthx02', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/incalgiabqdd', 'name' => 'in.vch.incalgiabqdd', 'module' => 'in', 'kind' => 'vch', 'slug' => 'incalgiabqdd', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/incalgiantxt', 'name' => 'in.vch.incalgiantxt', 'module' => 'in', 'kind' => 'vch', 'slug' => 'incalgiantxt', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/incalgiatb', 'name' => 'in.vch.incalgiatb', 'module' => 'in', 'kind' => 'vch', 'slug' => 'incalgiatb', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/invchin1', 'name' => 'in.vch.invchin1', 'module' => 'in', 'kind' => 'vch', 'slug' => 'invchin1', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/invchin2', 'name' => 'in.vch.invchin2', 'module' => 'in', 'kind' => 'vch', 'slug' => 'invchin2', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/invchin3', 'name' => 'in.vch.invchin3', 'module' => 'in', 'kind' => 'vch', 'slug' => 'invchin3', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/invchin5', 'name' => 'in.vch.invchin5', 'module' => 'in', 'kind' => 'vch', 'slug' => 'invchin5', 'component' => SimbaPage::class],
            // ['uri' => 'in/vch/invchin6', 'name' => 'in.vch.invchin6', 'module' => 'in', 'kind' => 'vch', 'slug' => 'invchin6', 'component' => SimbaPage::class],
            ['uri' => 'po/dict/ardmkh', 'name' => 'po.dict.ardmkh', 'module' => 'po', 'kind' => 'dict', 'slug' => 'ardmkh', 'component' => Ardmkh::class],
            ['uri' => 'po/dict/ardmkh/create', 'name' => 'po.dict.ardmkh.create', 'module' => 'po', 'kind' => 'dict', 'slug' => 'ardmkh', 'component' => ArdmkhForm::class],
            ['uri' => 'po/dict/ardmkh/{id}/edit', 'name' => 'po.dict.ardmkh.edit', 'module' => 'po', 'kind' => 'dict', 'slug' => 'ardmkh', 'component' => ArdmkhForm::class],
            // ['uri' => 'po/dict/ardmplkh', 'name' => 'po.dict.ardmplkh', 'module' => 'po', 'kind' => 'dict', 'slug' => 'ardmplkh', 'component' => SimbaPage::class],
            ['uri' => 'po/dict/podmcp', 'name' => 'po.dict.podmcp', 'module' => 'po', 'kind' => 'dict', 'slug' => 'podmcp', 'component' => PoDmCpIndex::class],
            // ['uri' => 'po/dict/podmgiamua', 'name' => 'po.dict.podmgiamua', 'module' => 'po', 'kind' => 'dict', 'slug' => 'podmgiamua', 'component' => SimbaPage::class],
            // ['uri' => 'po/dict/podmhttt', 'name' => 'po.dict.podmhttt', 'module' => 'po', 'kind' => 'dict', 'slug' => 'podmhttt', 'component' => SimbaPage::class],
            // ['uri' => 'po/dict/sidmbp', 'name' => 'po.dict.sidmbp', 'module' => 'po', 'kind' => 'dict', 'slug' => 'sidmbp', 'component' => SimbaPage::class],
            // ['uri' => 'po/dict/sidmhd', 'name' => 'po.dict.sidmhd', 'module' => 'po', 'kind' => 'dict', 'slug' => 'sidmhd', 'component' => SimbaPage::class],
            // ['uri' => 'po/dict/sidmnhhd', 'name' => 'po.dict.sidmnhhd', 'module' => 'po', 'kind' => 'dict', 'slug' => 'sidmnhhd', 'component' => SimbaPage::class],
            // ['uri' => 'po/dict/sisysvars', 'name' => 'po.dict.sisysvars', 'module' => 'po', 'kind' => 'dict', 'slug' => 'sisysvars', 'component' => SimbaPage::class],
            // ['uri' => 'po/proc/arcdkh', 'name' => 'po.proc.arcdkh', 'module' => 'po', 'kind' => 'proc', 'slug' => 'arcdkh', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/aprptbk01', 'name' => 'po.rpt.aprptbk01', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'aprptbk01', 'component' => SimbaPage::class],
            ['uri' => 'po/rpt/arrptbccn01103023', 'name' => 'po.rpt.arrptbccn01103023', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn01103023', 'component' => PoArrptbccn01Sl::class],
            // ['uri' => 'po/rpt/arrptbccn01', 'name' => 'po.rpt.arrptbccn01', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn01', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/arrptbccn01a', 'name' => 'po.rpt.arrptbccn01a', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn01a', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/arrptbccn02', 'name' => 'po.rpt.arrptbccn02', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn02', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/arrptbccn03', 'name' => 'po.rpt.arrptbccn03', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn03', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/arrptbccn04', 'name' => 'po.rpt.arrptbccn04', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn04', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/arrptbccn05', 'name' => 'po.rpt.arrptbccn05', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn05', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/glrptnkc05', 'name' => 'po.rpt.glrptnkc05', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'glrptnkc05', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptbcpt03', 'name' => 'po.rpt.porptbcpt03', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptbcpt03', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptbk01', 'name' => 'po.rpt.porptbk01', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptbk02', 'name' => 'po.rpt.porptbk02', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptbk02', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptbk03', 'name' => 'po.rpt.porptbk03', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptbk03', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptth01', 'name' => 'po.rpt.porptth01', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptth01', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptth02', 'name' => 'po.rpt.porptth02', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptth02', 'component' => SimbaPage::class],
            // ['uri' => 'po/rpt/porptth03', 'name' => 'po.rpt.porptth03', 'module' => 'po', 'kind' => 'rpt', 'slug' => 'porptth03', 'component' => SimbaPage::class],
            // ['uri' => 'po/vch/apvchap4', 'name' => 'po.vch.apvchap4', 'module' => 'po', 'kind' => 'vch', 'slug' => 'apvchap4', 'component' => SimbaPage::class],
            // ['uri' => 'po/vch/potain', 'name' => 'po.vch.potain', 'module' => 'po', 'kind' => 'vch', 'slug' => 'potain', 'component' => SimbaPage::class],
            // ['uri' => 'po/vch/povchpo1', 'name' => 'po.vch.povchpo1', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo1', 'component' => SimbaPage::class],
            ['uri' => 'po/vch/povchpo3', 'name' => 'po.vch.povchpo3', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo3', 'component' => Povchpo3::class],
            ['uri' => 'po/vch/povchpo3/create', 'name' => 'po.vch.povchpo3.create', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo3', 'component' => Povchpo3Edit::class],
            ['uri' => 'po/vch/povchpo3/{id}/edit', 'name' => 'po.vch.povchpo3.edit', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo3', 'component' => Povchpo3Edit::class],
            // ['uri' => 'po/vch/povchpo4', 'name' => 'po.vch.povchpo4', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo4', 'component' => SimbaPage::class],
            // ['uri' => 'po/vch/povchpo5', 'name' => 'po.vch.povchpo5', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo5', 'component' => SimbaPage::class],
            // ['uri' => 'po/vch/povchpo6', 'name' => 'po.vch.povchpo6', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo6', 'component' => SimbaPage::class],
            // ['uri' => 'po/vch/povchpo7', 'name' => 'po.vch.povchpo7', 'module' => 'po', 'kind' => 'vch', 'slug' => 'povchpo7', 'component' => SimbaPage::class],
            ['uri' => 'si/vch/smks', 'name' => 'si.vch.smks', 'module' => 'si', 'kind' => 'vch', 'slug' => 'smks', 'component' => Smks::class],
            ['uri' => 'so/dict/ardmkh', 'name' => 'so.dict.ardmkh', 'module' => 'so', 'kind' => 'dict', 'slug' => 'ardmkh', 'component' => Khachhang::class],
            ['uri' => 'so/dict/ardmplkh', 'name' => 'so.dict.ardmplkh', 'module' => 'so', 'kind' => 'dict', 'slug' => 'ardmplkh', 'component' => Phanloaikhachhang::class],
            // ['uri' => 'so/dict/sidmbp', 'name' => 'so.dict.sidmbp', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sidmbp', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sidmhd', 'name' => 'so.dict.sidmhd', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sidmhd', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sidmnhhd', 'name' => 'so.dict.sidmnhhd', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sidmnhhd', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sisysvars', 'name' => 'so.dict.sisysvars', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sisysvars', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sodmgiaban', 'name' => 'so.dict.sodmgiaban', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sodmgiaban', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sodmhttt', 'name' => 'so.dict.sodmhttt', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sodmhttt', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sodmnvkd', 'name' => 'so.dict.sodmnvkd', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sodmnvkd', 'component' => SimbaPage::class],
            // ['uri' => 'so/dict/sodmts', 'name' => 'so.dict.sodmts', 'module' => 'so', 'kind' => 'dict', 'slug' => 'sodmts', 'component' => SimbaPage::class],
            // ['uri' => 'so/proc/arcdkh', 'name' => 'so.proc.arcdkh', 'module' => 'so', 'kind' => 'proc', 'slug' => 'arcdkh', 'component' => SimbaPage::class],
            ['uri' => 'so/rpt/arrptbccn01', 'name' => 'so.rpt.arrptbccn01', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn01', 'component' => Arrptbccn01::class],
            ['uri' => 'so/rpt/arrptbccn01063038', 'name' => 'so.rpt.arrptbccn01063038', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn01063038', 'component' => SoArrptbccn01Sl::class],
            // ['uri' => 'so/rpt/arrptbccn01a', 'name' => 'so.rpt.arrptbccn01a', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn01a', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/arrptbccn02', 'name' => 'so.rpt.arrptbccn02', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn02', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/arrptbccn03', 'name' => 'so.rpt.arrptbccn03', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn03', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/arrptbccn04', 'name' => 'so.rpt.arrptbccn04', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn04', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/arrptbccn05', 'name' => 'so.rpt.arrptbccn05', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn05', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/arrptbk01', 'name' => 'so.rpt.arrptbk01', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/glrptnkc04', 'name' => 'so.rpt.glrptnkc04', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'glrptnkc04', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptbcpt03', 'name' => 'so.rpt.sorptbcpt03', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbcpt03', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptbcpt04', 'name' => 'so.rpt.sorptbcpt04', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbcpt04', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptbcpt06', 'name' => 'so.rpt.sorptbcpt06', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbcpt06', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptbk01', 'name' => 'so.rpt.sorptbk01', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbk01', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptbk02', 'name' => 'so.rpt.sorptbk02', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbk02', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptlailo', 'name' => 'so.rpt.sorptlailo', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptlailo', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptth01', 'name' => 'so.rpt.sorptth01', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptth01', 'component' => SimbaPage::class],
            // ['uri' => 'so/rpt/sorptth02', 'name' => 'so.rpt.sorptth02', 'module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptth02', 'component' => SimbaPage::class],
            // ['uri' => 'so/vch/arvchar4', 'name' => 'so.vch.arvchar4', 'module' => 'so', 'kind' => 'vch', 'slug' => 'arvchar4', 'component' => SimbaPage::class],
            // ['uri' => 'so/vch/sotaout', 'name' => 'so.vch.sotaout', 'module' => 'so', 'kind' => 'vch', 'slug' => 'sotaout', 'component' => SimbaPage::class],
            ['uri' => 'so/vch/sovchso1', 'name' => 'so.vch.sovchso1', 'module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso1', 'component' => Hoadonbanhang::class],
            // ['uri' => 'so/vch/sovchso3', 'name' => 'so.vch.sovchso3', 'module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso3', 'component' => SimbaPage::class],
            // ['uri' => 'so/vch/sovchso4', 'name' => 'so.vch.sovchso4', 'module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso4', 'component' => SimbaPage::class],
            // ['uri' => 'so/vch/sovchso5', 'name' => 'so.vch.sovchso5', 'module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso5', 'component' => SimbaPage::class],
        ];

        foreach ($sourceRoutes as $sourceRoute) {
            Route::get($sourceRoute['uri'], $sourceRoute['component'])
                ->defaults('module', $sourceRoute['module'])
                ->defaults('kind', $sourceRoute['kind'])
                ->defaults('slug', $sourceRoute['slug'])
                ->name($sourceRoute['name'])
            ;
        }
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
        Route::get('/{module}/{kind}/{slug}/create', SimbaPage::class)
            ->defaults('action', 'create')
            ->where([
                'module' => '[a-z0-9-]+',
                'kind'   => '[a-z0-9-]+',
                'slug'   => '[a-z0-9_.-]+',
            ])
            ->name('create')
        ;
        Route::get('/{module}/{kind}/{slug}/{id}/edit', SimbaPage::class)
            ->defaults('action', 'edit')
            ->where([
                'module' => '[a-z0-9-]+',
                'kind'   => '[a-z0-9-]+',
                'slug'   => '[a-z0-9_.-]+',
                'id'     => '[^/]+',
            ])
            ->name('edit')
        ;
    });

    Route::prefix('hethong')->name('system.')->group(static function (): void {
        Route::redirect('system', '/simba/si/vch/smks')->name('system');
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
