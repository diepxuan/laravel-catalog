<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:00
 */

use Diepxuan\Catalog\Http\Controllers\SellController;
use Diepxuan\Catalog\Http\Controllers\SystemController;
use Diepxuan\Catalog\Http\Controllers\SystemUserController;
use Diepxuan\Catalog\Http\Controllers\SystemWebsiteController;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\Nhomkhachhang;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\NhomkhachhangEdit;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\Phanloaikhachhang;
use Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\PhanloaikhachhangEdit;
use Diepxuan\Catalog\Http\Livewire\Banhang\ArReportIndex;
use Diepxuan\Catalog\Http\Livewire\Banhang\Hoadonbanhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\Khachhang;
use Diepxuan\Catalog\Http\Livewire\Banhang\KhachhangForm;
use Diepxuan\Catalog\Http\Livewire\Banhang\SoReportIndex;
use Diepxuan\Catalog\Http\Livewire\Banhang\SoVoucherIndex;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Chi;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Nganhang;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Thu;
use Diepxuan\Catalog\Http\Livewire\Cash\Baocao\Tienmat;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baoco;
use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuchi;
use Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuthu;
use Diepxuan\Catalog\Http\Livewire\Co\CoDictionaryIndex;
use Diepxuan\Catalog\Http\Livewire\Co\CoReportIndex;
use Diepxuan\Catalog\Http\Livewire\Co\Danhmuc\Dinhmucchitiet;
use Diepxuan\Catalog\Http\Livewire\Co\Danhmuc\Dmbom;
use Diepxuan\Catalog\Http\Livewire\Fa\FaDictionaryIndex;
use Diepxuan\Catalog\Http\Livewire\Fa\FaReportIndex;
use Diepxuan\Catalog\Http\Livewire\FinanceVoucherIndex;
use Diepxuan\Catalog\Http\Livewire\Gl\GlReportIndex;
use Diepxuan\Catalog\Http\Livewire\Gl\Taikhoan;
use Diepxuan\Catalog\Http\Livewire\Home\Dashboard as DashboardLivewire;
use Diepxuan\Catalog\Http\Livewire\In\Baocao\InReportIndex;
use Diepxuan\Catalog\Http\Livewire\In\Baocao\Tonkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmkho;
use Diepxuan\Catalog\Http\Livewire\In\Dmnhvt;
use Diepxuan\Catalog\Http\Livewire\In\Dmvt;
use Diepxuan\Catalog\Http\Livewire\In\InVoucherIndex;
use Diepxuan\Catalog\Http\Livewire\Muahang\ApReportIndex;
use Diepxuan\Catalog\Http\Livewire\Muahang\Cungcap;
use Diepxuan\Catalog\Http\Livewire\Muahang\Hoadonmua;
use Diepxuan\Catalog\Http\Livewire\Muahang\HoadonmuaEdit;
use Diepxuan\Catalog\Http\Livewire\Muahang\PoDmCpIndex;
use Diepxuan\Catalog\Http\Livewire\Muahang\PoReportIndex;
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
use Diepxuan\Catalog\Http\Livewire\System\Menu;
use Diepxuan\Catalog\Http\Livewire\System\SiDictionaryIndex;
use Diepxuan\Catalog\Http\Livewire\System\SimbaDictionaryIndex;
use Diepxuan\Catalog\Http\Livewire\System\SimbaProcessIndex;
use Diepxuan\Catalog\Http\Livewire\System\SimbaReportIndex;
use Diepxuan\Catalog\Http\Livewire\System\TaskShell;
use Diepxuan\Catalog\Http\Livewire\System\YearSelector;
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
Route::middleware(['auth'])->group(static function (): void {
    Route::get('/gl/taikhoan', Taikhoan::class)->name('gl.taikhoan');
    Route::get('/gl/phieu-ke-toan', FinanceVoucherIndex::class)->defaults('voucherCode', 'GL1')->name('gl.vch.gl1');
    Route::get('/gl/chung-tu-ngoai-bang', FinanceVoucherIndex::class)->defaults('voucherCode', 'NB1')->name('gl.vch.nb1');
    Route::prefix('gl/baocao')->name('gl.rpt.')->group(static function (): void {
        Route::get('/so-dang-ky-ctgs', GlReportIndex::class)->defaults('routeName', 'gl.rpt.ctgs01')->name('ctgs01');
        Route::get('/chung-tu-ghi-so', GlReportIndex::class)->defaults('routeName', 'gl.rpt.ctgs02')->name('ctgs02');
        Route::get('/so-cai-ctgs', GlReportIndex::class)->defaults('routeName', 'gl.rpt.ctgs03')->name('ctgs03');
        Route::get('/bang-ke-ctgs', GlReportIndex::class)->defaults('routeName', 'gl.rpt.ctgs05')->name('ctgs05');
        Route::get('/so-cai', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc01')->name('nkc01');
        Route::get('/so-nhat-ky-chung', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc02')->name('nkc02');
        Route::get('/so-nhat-ky-thu-tien', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc03')->name('nkc03');
        Route::get('/so-nhat-ky-chi-tien', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc03-chi')->name('nkc03-chi');
        Route::get('/so-nhat-ky-ban-hang', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc04')->name('nkc04');
        Route::get('/so-nhat-ky-mua-hang', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc05')->name('nkc05');
        Route::get('/so-chi-tiet-tai-khoan', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc06')->name('nkc06');
        Route::get('/so-chu-t-tai-khoan', GlReportIndex::class)->defaults('routeName', 'gl.rpt.nkc07')->name('nkc07');
        Route::get('/bang-ke-chung-tu', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bk02')->name('bk02');
        Route::get('/tong-hop-phat-sinh-nhom', GlReportIndex::class)->defaults('routeName', 'gl.rpt.th01')->name('th01');
        Route::get('/thue-mua-vao', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bct01')->name('bct01');
        Route::get('/thue-ban-ra', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bct02')->name('bct02');
        Route::get('/to-khai-thue-gtgt', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bct03')->name('bct03');
        Route::get('/to-khai-thue-tndn-tam-tinh', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bct04')->name('bct04');
        Route::get('/quyet-toan-thue-tndn', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bct05')->name('bct05');
        Route::get('/phu-luc-ket-qua-hdkd', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bct06')->name('bct06');
        Route::get('/can-doi-phat-sinh', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctc01')->name('bctc01');
        Route::get('/can-doi-ke-toan', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctc02')->name('bctc02');
        Route::get('/ket-qua-kinh-doanh', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctc03')->name('bctc03');
        Route::get('/luu-chuyen-tien-te-truc-tiep', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctc04')->name('bctc04');
        Route::get('/thuyet-minh-bctc', GlReportIndex::class)->defaults('routeName', 'gl.rpt.tmbctc')->name('tmbctc');
        Route::get('/nguon-von-dau-tu', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtb02')->name('bctccdtb02');
        Route::get('/chi-tiet-nguon-von-dau-tu', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtf02')->name('bctccdtf02');
        Route::get('/thuc-hien-dau-tu-du-an', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtf03a')->name('bctccdtf03a');
        Route::get('/quyet-toan-dau-tu-du-an', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtf03b')->name('bctccdtf03b');
        Route::get('/thuc-hien-dau-tu', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtb03')->name('bctccdtb03');
        Route::get('/chi-phi-khac', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtf03c')->name('bctccdtf03c');
        Route::get('/chi-phi-ban-quan-ly-du-an', GlReportIndex::class)->defaults('routeName', 'gl.rpt.bctccdtf03d')->name('bctccdtf03d');
    });

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
    Route::get('banhang/dondathang', SoVoucherIndex::class)->defaults('voucherCode', 'SO1')->name('banhang.so1');
    Route::get('banhang/nhap-hang-ban-tra-lai', SoVoucherIndex::class)->defaults('voucherCode', 'SO4')->name('banhang.so4');
    Route::get('banhang/hoadon-dichvu', SoVoucherIndex::class)->defaults('voucherCode', 'SO5')->name('banhang.so5');
    Route::get('banhang/bu-tru-cong-no', FinanceVoucherIndex::class)->defaults('voucherCode', 'AR4')->name('ar.vch.ar4');
    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');
    Route::get('/co/danhmuc/dinh-muc-bom', Dmbom::class)->name('co.dmbom');
    Route::get('/co/danhmuc/dinh-muc-chi-tiet', Dinhmucchitiet::class)->name('co.dinhmucchitiet');
    Route::get('/gl/task/138', TaskShell::class)->defaults('title', 'GL GL chuyen so lieu so cai')->defaults('dll', 'GL task 138')->defaults('task', 'Task 138')->name('gl.task.138');
    Route::get('/gl/task/140', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC CTMV02')->defaults('dll', 'GL task 140')->defaults('task', 'Task 140')->name('gl.task.140');
    Route::get('/gl/task/141', TaskShell::class)->defaults('title', 'GL GL baocao F5 CTGS03')->defaults('dll', 'GL task 141')->defaults('task', 'Task 141')->name('gl.task.141');
    Route::get('/gl/task/142', TaskShell::class)->defaults('title', 'GL GL baocao BCTCCDTF03B')->defaults('dll', 'GL task 142')->defaults('task', 'Task 142')->name('gl.task.142');
    Route::get('/gl/task/143', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MVI211')->defaults('dll', 'GL task 143')->defaults('task', 'Task 143')->name('gl.task.143');
    Route::get('/gl/task/145', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MV21')->defaults('dll', 'GL task 145')->defaults('task', 'Task 145')->name('gl.task.145');
    Route::get('/gl/task/146', TaskShell::class)->defaults('title', 'GL GL danhmuc taikhoan')->defaults('dll', 'GL task 146')->defaults('task', 'Task 146')->name('gl.task.146');
    Route::get('/gl/task/147', TaskShell::class)->defaults('title', 'GL GL sodu dauky taikhoan')->defaults('dll', 'GL task 147')->defaults('task', 'Task 147')->name('gl.task.147');
    Route::get('/gl/task/148', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MV22c')->defaults('dll', 'GL task 148')->defaults('task', 'Task 148')->name('gl.task.148');
    Route::get('/gl/task/150', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MV22d')->defaults('dll', 'GL task 150')->defaults('task', 'Task 150')->name('gl.task.150');
    Route::get('/gl/task/151', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC CTMI')->defaults('dll', 'GL task 151')->defaults('task', 'Task 151')->name('gl.task.151');
    Route::get('/gl/task/152', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MV13')->defaults('dll', 'GL task 152')->defaults('task', 'Task 152')->name('gl.task.152');
    Route::get('/gl/task/153', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MV20C')->defaults('dll', 'GL task 153')->defaults('task', 'Task 153')->name('gl.task.153');
    Route::get('/gl/task/154', TaskShell::class)->defaults('title', 'GL GL baocao F5 BCTCCR01')->defaults('dll', 'GL task 154')->defaults('task', 'Task 154')->name('gl.task.154');
    Route::get('/gl/task/157', TaskShell::class)->defaults('title', 'GL GL bieumau BCTC MV22A')->defaults('dll', 'GL task 157')->defaults('task', 'Task 157')->name('gl.task.157');
    Route::get('/in/phieu-xuat-kho', TaskShell::class)->defaults('title', 'Phiếu xuất kho')->defaults('dll', 'INVchIN2.dll')->defaults('task', 'Task 226')->name('in.vch.in2');
    Route::get('/in/phieu-chuyen-kho', TaskShell::class)->defaults('title', 'Phiếu chuyển kho')->defaults('dll', 'INVchIN3.dll')->defaults('task', 'Task 227')->name('in.vch.in3');
    Route::get('/in/xuat-cong-cu-dung-cu', TaskShell::class)->defaults('title', 'Xuất CCDC')->defaults('dll', 'INVchIN4.dll')->defaults('task', 'Task 228')->name('in.vch.in4');
    Route::get('/in/tinh-gia-nhap-truoc-xuat-truoc', TaskShell::class)->defaults('title', 'Nhập trước xuất trước')->defaults('dll', 'INVchIN5.dll')->defaults('task', 'Task 235')->name('in.vch.in5');
    Route::get('/fa/phan-bo-cong-cu-dung-cu', TaskShell::class)->defaults('title', 'Phân bổ CCDC')->defaults('dll', 'FAAllocCC.dll')->defaults('task', 'Task 236')->name('fa.transfer.cc2gl');
    Route::get('/fa/phan-bo-tai-san', TaskShell::class)->defaults('title', 'Phân bổ TSCĐ')->defaults('dll', 'FAAllocTS.dll')->defaults('task', 'Task 237')->name('fa.transfer.ts2gl');
    Route::get('/fa/chuyen-tai-san', TaskShell::class)->defaults('title', 'Chuyển tài sản')->defaults('dll', 'FAChuyenTS.dll')->defaults('task', 'Task 238')->name('fa.vch.chuyents');
    Route::get('/fa/xoa-khau-hao', TaskShell::class)->defaults('title', 'Xóa khấu hao')->defaults('dll', 'FADelKH.dll')->defaults('task', 'Task 239')->name('fa.vch.xoakh');
    Route::get('/fa/dieu-chinh-khau-hao', TaskShell::class)->defaults('title', 'Điều chỉnh khấu hao')->defaults('dll', 'FADieuChinhKH.dll')->defaults('task', 'Task 240')->name('fa.vch.dieuchinhkh');
    Route::get('/fa/danh-muc-ccdc', TaskShell::class)->defaults('title', 'Danh mục CCDC')->defaults('dll', 'FADMCCDC.dll')->defaults('task', 'Task 241')->name('fa.dict.ccdc');
    Route::get('/fa/danh-muc-ly-do-tang-giam', TaskShell::class)->defaults('title', 'Lý do tăng giảm')->defaults('dll', 'FADMLDTG.dll')->defaults('task', 'Task 242')->name('fa.dict.ldtg');
    Route::get('/fa/danh-muc-tai-san', TaskShell::class)->defaults('title', 'Danh mục TSCĐ')->defaults('dll', 'FADMTS.dll')->defaults('task', 'Task 244')->name('fa.dict.ts');
    Route::get('/fa/bao-cao-phantich-01', TaskShell::class)->defaults('title', 'Phân tích TS 01')->defaults('dll', 'FARptBCPT01.dll')->defaults('task', 'Task 245')->name('fa.rpt.bcpt01');
    Route::get('/fa/bao-cao-phantich-06', TaskShell::class)->defaults('title', 'Phân tích TS 06')->defaults('dll', 'FARptBCPT06.dll')->defaults('task', 'Task 246')->name('fa.rpt.bcpt06');
    Route::get('/fa/bao-cao-phantich-07', TaskShell::class)->defaults('title', 'Phân tích TS 07')->defaults('dll', 'FARptBCPT07.dll')->defaults('task', 'Task 247')->name('fa.rpt.bcpt07');
    Route::get('/fa/bao-cao-phantich-08', TaskShell::class)->defaults('title', 'Phân tích TS 08')->defaults('dll', 'FARptBCPT08.dll')->defaults('task', 'Task 248')->name('fa.rpt.bcpt08');
    Route::get('/fa/tang-tai-san', TaskShell::class)->defaults('title', 'Tăng TSCĐ')->defaults('dll', 'FATangTS.dll')->defaults('task', 'Task 252')->name('fa.vch.tangts');
    Route::get('/fa/giam-tai-san-2', TaskShell::class)->defaults('title', 'Giảm TSCĐ 2')->defaults('dll', 'FAGiamTS2.dll')->defaults('task', 'Task 253')->name('fa.vch.giamts2');
    Route::get('/gl/task/254', TaskShell::class)->defaults('title', 'GL gl danh muc du toan ngan sach')->defaults('dll', 'GL task 254')->defaults('task', 'Task 254')->name('gl.task.254');
    Route::get('/gl/task/255', TaskShell::class)->defaults('title', 'GL gl dang ky chung tu ghi so')->defaults('dll', 'GL task 255')->defaults('task', 'Task 255')->name('gl.task.255');
    Route::get('/gl/task/256', TaskShell::class)->defaults('title', 'GL gl danh muc chung tu ghi so')->defaults('dll', 'GL task 256')->defaults('task', 'Task 256')->name('gl.task.256');
    Route::get('/gl/task/257', TaskShell::class)->defaults('title', 'GL gl danh muc danh gia ty gia')->defaults('dll', 'GL task 257')->defaults('task', 'Task 257')->name('gl.task.257');
    Route::get('/gl/task/259', TaskShell::class)->defaults('title', 'GL gl mau bctc mix')->defaults('dll', 'GL task 259')->defaults('task', 'Task 259')->name('gl.task.259');
    Route::get('/gl/task/260', TaskShell::class)->defaults('title', 'GL gl mau bctc mv01')->defaults('dll', 'GL task 260')->defaults('task', 'Task 260')->name('gl.task.260');
    Route::get('/gl/task/261', TaskShell::class)->defaults('title', 'GL gl mau bctc mv03')->defaults('dll', 'GL task 261')->defaults('task', 'Task 261')->name('gl.task.261');
    Route::get('/gl/task/262', TaskShell::class)->defaults('title', 'GL gl mau bctc mv08')->defaults('dll', 'GL task 262')->defaults('task', 'Task 262')->name('gl.task.262');
    Route::get('/gl/task/263', TaskShell::class)->defaults('title', 'GL gl mau bctc mv09')->defaults('dll', 'GL task 263')->defaults('task', 'Task 263')->name('gl.task.263');
    Route::get('/gl/task/264', TaskShell::class)->defaults('title', 'GL gl mau bctc mv10')->defaults('dll', 'GL task 264')->defaults('task', 'Task 264')->name('gl.task.264');
    Route::get('/gl/task/265', TaskShell::class)->defaults('title', 'GL gl mau bctc mv11')->defaults('dll', 'GL task 265')->defaults('task', 'Task 265')->name('gl.task.265');
    Route::get('/gl/task/266', TaskShell::class)->defaults('title', 'GL gl mau bctc mv12')->defaults('dll', 'GL task 266')->defaults('task', 'Task 266')->name('gl.task.266');
    Route::get('/gl/task/267', TaskShell::class)->defaults('title', 'GL gl mau bctc mv22b')->defaults('dll', 'GL task 267')->defaults('task', 'Task 267')->name('gl.task.267');
    Route::get('/gl/task/268', TaskShell::class)->defaults('title', 'GL gl mau bctc mv23')->defaults('dll', 'GL task 268')->defaults('task', 'Task 268')->name('gl.task.268');
    Route::get('/gl/task/269', TaskShell::class)->defaults('title', 'GL gl mau bctc mv24')->defaults('dll', 'GL task 269')->defaults('task', 'Task 269')->name('gl.task.269');
    Route::get('/gl/task/270', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi')->defaults('dll', 'GL task 270')->defaults('task', 'Task 270')->name('gl.task.270');
    Route::get('/gl/task/271', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi02b')->defaults('dll', 'GL task 271')->defaults('task', 'Task 271')->name('gl.task.271');
    Route::get('/gl/task/272', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi02a')->defaults('dll', 'GL task 272')->defaults('task', 'Task 272')->name('gl.task.272');
    Route::get('/gl/task/273', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi02c')->defaults('dll', 'GL task 273')->defaults('task', 'Task 273')->name('gl.task.273');
    Route::get('/gl/task/274', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi09')->defaults('dll', 'GL task 274')->defaults('task', 'Task 274')->name('gl.task.274');
    Route::get('/gl/task/275', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi10')->defaults('dll', 'GL task 275')->defaults('task', 'Task 275')->name('gl.task.275');
    Route::get('/gl/task/276', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi11')->defaults('dll', 'GL task 276')->defaults('task', 'Task 276')->name('gl.task.276');
    Route::get('/gl/task/277', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi12')->defaults('dll', 'GL task 277')->defaults('task', 'Task 277')->name('gl.task.277');
    Route::get('/gl/task/278', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi15c')->defaults('dll', 'GL task 278')->defaults('task', 'Task 278')->name('gl.task.278');
    Route::get('/gl/task/279', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi212')->defaults('dll', 'GL task 279')->defaults('task', 'Task 279')->name('gl.task.279');
    Route::get('/gl/task/280', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi25a')->defaults('dll', 'GL task 280')->defaults('task', 'Task 280')->name('gl.task.280');
    Route::get('/gl/task/281', TaskShell::class)->defaults('title', 'GL gl mau bctc mvi29b')->defaults('dll', 'GL task 281')->defaults('task', 'Task 281')->name('gl.task.281');
    Route::get('/gl/task/282', TaskShell::class)->defaults('title', 'GL gl mau bctc mvii')->defaults('dll', 'GL task 282')->defaults('task', 'Task 282')->name('gl.task.282');
    Route::get('/gl/task/283', TaskShell::class)->defaults('title', 'GL gl mau bctc mviii')->defaults('dll', 'GL task 283')->defaults('task', 'Task 283')->name('gl.task.283');
    Route::get('/gl/task/285', TaskShell::class)->defaults('title', 'GL gl post2dgtg')->defaults('dll', 'GL task 285')->defaults('task', 'Task 285')->name('gl.task.285');
    Route::get('/gl/task/286', TaskShell::class)->defaults('title', 'GL gl post2glkc')->defaults('dll', 'GL task 286')->defaults('task', 'Task 286')->name('gl.task.286');
    Route::get('/gl/task/287', TaskShell::class)->defaults('title', 'GL gl rpt bcpt01')->defaults('dll', 'GL task 287')->defaults('task', 'Task 287')->name('gl.task.287');
    Route::get('/gl/task/288', TaskShell::class)->defaults('title', 'GL gl rpt bcpt02')->defaults('dll', 'GL task 288')->defaults('task', 'Task 288')->name('gl.task.288');
    Route::get('/gl/task/289', TaskShell::class)->defaults('title', 'GL gl rpt bcpt03')->defaults('dll', 'GL task 289')->defaults('task', 'Task 289')->name('gl.task.289');
    Route::get('/gl/task/290', TaskShell::class)->defaults('title', 'GL gl rpt bcpt04')->defaults('dll', 'GL task 290')->defaults('task', 'Task 290')->name('gl.task.290');
    Route::get('/gl/task/291', TaskShell::class)->defaults('title', 'GL gl rpt bcpt05')->defaults('dll', 'GL task 291')->defaults('task', 'Task 291')->name('gl.task.291');
    Route::get('/gl/task/295', TaskShell::class)->defaults('title', 'GL gl rpt bctccdtf02')->defaults('dll', 'GL task 295')->defaults('task', 'Task 295')->name('gl.task.295');
    Route::get('/gl/task/296', TaskShell::class)->defaults('title', 'GL gl rpt bctccdtf03a')->defaults('dll', 'GL task 296')->defaults('task', 'Task 296')->name('gl.task.296');
    Route::get('/gl/task/297', TaskShell::class)->defaults('title', 'GL gl rpt bctccdtf03c')->defaults('dll', 'GL task 297')->defaults('task', 'Task 297')->name('gl.task.297');
    Route::get('/gl/task/298', TaskShell::class)->defaults('title', 'GL gl rpt bctccdtf03d')->defaults('dll', 'GL task 298')->defaults('task', 'Task 298')->name('gl.task.298');
    Route::get('/gl/task/252', TaskShell::class)->defaults('title', 'GL fa so theo doi tscd ccdc')->defaults('dll', 'GL task 252')->defaults('task', 'Task 252')->name('gl.task.252');
    Route::get('/gl/task/253', TaskShell::class)->defaults('title', 'GL fa kiem ke tai san')->defaults('dll', 'GL task 253')->defaults('task', 'Task 253')->name('gl.task.253');
    Route::get('/gl/task/299', TaskShell::class)->defaults('title', 'GL gl rpt bctccr02')->defaults('dll', 'GL task 299')->defaults('task', 'Task 299')->name('gl.task.299');
    Route::get('/gl/task/300', TaskShell::class)->defaults('title', 'GL gl rpt bctccr03')->defaults('dll', 'GL task 300')->defaults('task', 'Task 300')->name('gl.task.300');
    Route::get('/gl/task/301', TaskShell::class)->defaults('title', 'GL gl rpt bctccr04')->defaults('dll', 'GL task 301')->defaults('task', 'Task 301')->name('gl.task.301');
    Route::get('/gl/task/302', TaskShell::class)->defaults('title', 'GL gl rpt bk02')->defaults('dll', 'GL task 302')->defaults('task', 'Task 302')->name('gl.task.302');
    Route::get('/gl/task/307', TaskShell::class)->defaults('title', 'GL gl rpt f5bcpt08')->defaults('dll', 'GL task 307')->defaults('task', 'Task 307')->name('gl.task.307');
    Route::get('/gl/task/308', TaskShell::class)->defaults('title', 'GL gl rpt f5bctc02')->defaults('dll', 'GL task 308')->defaults('task', 'Task 308')->name('gl.task.308');
    Route::get('/gl/task/309', TaskShell::class)->defaults('title', 'GL gl rpt f5bctc02ct')->defaults('dll', 'GL task 309')->defaults('task', 'Task 309')->name('gl.task.309');
    Route::get('/gl/task/310', TaskShell::class)->defaults('title', 'GL gl rpt f5bctc03ct')->defaults('dll', 'GL task 310')->defaults('task', 'Task 310')->name('gl.task.310');
    Route::get('/gl/task/311', TaskShell::class)->defaults('title', 'GL gl rpt f5ctgs01')->defaults('dll', 'GL task 311')->defaults('task', 'Task 311')->name('gl.task.311');
    Route::get('/gl/task/312', TaskShell::class)->defaults('title', 'GL gl rpt f5ctgs02')->defaults('dll', 'GL task 312')->defaults('task', 'Task 312')->name('gl.task.312');
    Route::get('/gl/task/313', TaskShell::class)->defaults('title', 'GL gl rpt nkc08')->defaults('dll', 'GL task 313')->defaults('task', 'Task 313')->name('gl.task.313');
    Route::get('/gl/task/314', TaskShell::class)->defaults('title', 'GL gl rpt nkctnk1')->defaults('dll', 'GL task 314')->defaults('task', 'Task 314')->name('gl.task.314');
    Route::get('/gl/task/315', TaskShell::class)->defaults('title', 'GL gl rpt nkctsocai')->defaults('dll', 'GL task 315')->defaults('task', 'Task 315')->name('gl.task.315');
    Route::get('/gl/task/316', TaskShell::class)->defaults('title', 'GL gl rpt tmbctc')->defaults('dll', 'GL task 316')->defaults('task', 'Task 316')->name('gl.task.316');
    Route::get('/gl/task/317', TaskShell::class)->defaults('title', 'GL gl transfer glcdtk')->defaults('dll', 'GL task 317')->defaults('task', 'Task 317')->name('gl.task.317');
    Route::get('/gl/task/318', TaskShell::class)->defaults('title', 'GL gl vch gl1')->defaults('dll', 'GL task 318')->defaults('task', 'Task 318')->name('gl.task.318');
    Route::get('/gl/task/319', TaskShell::class)->defaults('title', 'GL gl rpt bct04')->defaults('dll', 'GL task 319')->defaults('task', 'Task 319')->name('gl.task.319');
    Route::get('/gl/task/320', TaskShell::class)->defaults('title', 'GL gl rpt bct05')->defaults('dll', 'GL task 320')->defaults('task', 'Task 320')->name('gl.task.320');
    Route::get('/gl/task/321', TaskShell::class)->defaults('title', 'GL gl rpt bct06')->defaults('dll', 'GL task 321')->defaults('task', 'Task 321')->name('gl.task.321');
    Route::get('/gl/task/322', TaskShell::class)->defaults('title', 'GL gl rpt bcpt08')->defaults('dll', 'GL task 322')->defaults('task', 'Task 322')->name('gl.task.322');
    Route::get('/gl/task/323', TaskShell::class)->defaults('title', 'GL gl rpt bctc01')->defaults('dll', 'GL task 323')->defaults('task', 'Task 323')->name('gl.task.323');
    Route::get('/gl/task/324', TaskShell::class)->defaults('title', 'GL gl rpt bctc02')->defaults('dll', 'GL task 324')->defaults('task', 'Task 324')->name('gl.task.324');
    Route::get('/gl/task/325', TaskShell::class)->defaults('title', 'GL gl rpt bctc03')->defaults('dll', 'GL task 325')->defaults('task', 'Task 325')->name('gl.task.325');
    Route::get('/gl/task/326', TaskShell::class)->defaults('title', 'GL gl rpt bctc04')->defaults('dll', 'GL task 326')->defaults('task', 'Task 326')->name('gl.task.326');
    Route::get('/gl/task/327', TaskShell::class)->defaults('title', 'GL gl rpt bk01')->defaults('dll', 'GL task 327')->defaults('task', 'Task 327')->name('gl.task.327');
    Route::get('/gl/task/328', TaskShell::class)->defaults('title', 'GL gl rpt f5bctc03')->defaults('dll', 'GL task 328')->defaults('task', 'Task 328')->name('gl.task.328');
    Route::get('/gl/task/329', TaskShell::class)->defaults('title', 'GL gl rpt f5bctc04')->defaults('dll', 'GL task 329')->defaults('task', 'Task 329')->name('gl.task.329');
    Route::get('/gl/task/330', TaskShell::class)->defaults('title', 'GL gl rpt nkc01all')->defaults('dll', 'GL task 330')->defaults('task', 'Task 330')->name('gl.task.330');
    Route::get('/gl/task/331', TaskShell::class)->defaults('title', 'GL gl rpt nkc01')->defaults('dll', 'GL task 331')->defaults('task', 'Task 331')->name('gl.task.331');
    Route::get('/gl/task/332', TaskShell::class)->defaults('title', 'GL gl rpt nkc02')->defaults('dll', 'GL task 332')->defaults('task', 'Task 332')->name('gl.task.332');
    Route::get('/gl/task/333', TaskShell::class)->defaults('title', 'GL gl rpt nkc03')->defaults('dll', 'GL task 333')->defaults('task', 'Task 333')->name('gl.task.333');
    Route::get('/gl/task/334', TaskShell::class)->defaults('title', 'GL gl rpt nkc04')->defaults('dll', 'GL task 334')->defaults('task', 'Task 334')->name('gl.task.334');
    Route::get('/gl/task/335', TaskShell::class)->defaults('title', 'GL gl rpt nkc05')->defaults('dll', 'GL task 335')->defaults('task', 'Task 335')->name('gl.task.335');
    Route::get('/gl/task/336', TaskShell::class)->defaults('title', 'GL gl rpt nkc06')->defaults('dll', 'GL task 336')->defaults('task', 'Task 336')->name('gl.task.336');
    Route::get('/gl/task/337', TaskShell::class)->defaults('title', 'GL gl rpt nkc07')->defaults('dll', 'GL task 337')->defaults('task', 'Task 337')->name('gl.task.337');
    Route::get('/task/339', TaskShell::class)->defaults('title', 'SO dondathang mauhoadon')->defaults('dll', 'task 339')->defaults('task', 'Task 339')->name('task.339');
    Route::get('/task/34', TaskShell::class)->defaults('title', 'ban hang khach hang lien ket ngan hang')->defaults('dll', 'task 34')->defaults('task', 'Task 34')->name('task.34');
    Route::get('/task/340', TaskShell::class)->defaults('title', 'SO phieuxuat banle')->defaults('dll', 'task 340')->defaults('task', 'Task 340')->name('task.340');
    Route::get('/task/341', TaskShell::class)->defaults('title', 'FA baocao phantich tscd 01')->defaults('dll', 'task 341')->defaults('task', 'Task 341')->name('task.341');
    Route::get('/task/342', TaskShell::class)->defaults('title', 'co lien ket ket chuyen')->defaults('dll', 'task 342')->defaults('task', 'Task 342')->name('task.342');
    Route::get('/task/343', TaskShell::class)->defaults('title', 'co phieu bao')->defaults('dll', 'task 343')->defaults('task', 'Task 343')->name('task.343');
    Route::get('/task/347', TaskShell::class)->defaults('title', 'co tinh gia thanh')->defaults('dll', 'task 347')->defaults('task', 'Task 347')->name('task.347');
    Route::get('/task/348', TaskShell::class)->defaults('title', 'co danh muc chi phi tap trung')->defaults('dll', 'task 348')->defaults('task', 'Task 348')->name('task.348');
    Route::get('/task/349', TaskShell::class)->defaults('title', 'co xoa post cp sang gl')->defaults('dll', 'task 349')->defaults('task', 'Task 349')->name('task.349');
    Route::get('/task/35', TaskShell::class)->defaults('title', 'ban hang khach hang lien ket nhom phan loai')->defaults('dll', 'task 35')->defaults('task', 'Task 35')->name('task.35');
    Route::get('/task/350', TaskShell::class)->defaults('title', 'co ngung khai bao cp')->defaults('dll', 'task 350')->defaults('task', 'Task 350')->name('task.350');
    Route::get('/task/351', TaskShell::class)->defaults('title', 'co post chi phi tap trung sang gl')->defaults('dll', 'task 351')->defaults('task', 'Task 351')->name('task.351');
    Route::get('/task/352', TaskShell::class)->defaults('title', 'co bao cao chi tiet pb')->defaults('dll', 'task 352')->defaults('task', 'Task 352')->name('task.352');
    Route::get('/task/355', TaskShell::class)->defaults('title', 'in danh muc vattu bom')->defaults('dll', 'task 355')->defaults('task', 'Task 355')->name('task.355');
    Route::get('/task/36', TaskShell::class)->defaults('title', 'ban hang khach hang lien ket danh muc tai khoan')->defaults('dll', 'task 36')->defaults('task', 'Task 36')->name('task.36');
    Route::get('/task/37', TaskShell::class)->defaults('title', 'ban hang khach hang lien ket ma thanh toan')->defaults('dll', 'task 37')->defaults('task', 'Task 37')->name('task.37');
    Route::get('/task/55', TaskShell::class)->defaults('title', 'ban hang hoa don ban hang')->defaults('dll', 'task 55')->defaults('task', 'Task 55')->name('task.55');
    Route::get('/task/59', TaskShell::class)->defaults('title', 'hang ton kho')->defaults('dll', 'task 59')->defaults('task', 'Task 59')->name('task.59');
    Route::get('/task/shell/005', TaskShell::class)->defaults('title', 'ar dieu khoan thanh toan')->defaults('dll', 'task 005')->defaults('task', 'Task 005')->name('task.shell.005');
    Route::get('/task/shell/006', TaskShell::class)->defaults('title', 'ar phan bo thanh toan')->defaults('dll', 'task 006')->defaults('task', 'Task 006')->name('task.shell.006');
    Route::get('/task/shell/007', TaskShell::class)->defaults('title', 'ar tinh lai so du khach hang')->defaults('dll', 'task 007')->defaults('task', 'Task 007')->name('task.shell.007');
    Route::get('/task/shell/016', TaskShell::class)->defaults('title', 'ar chung tu hach toan cong no')->defaults('dll', 'task 016')->defaults('task', 'Task 016')->name('task.shell.016');
    Route::get('/task/shell/017', TaskShell::class)->defaults('title', 'ap phan bo thanh toan')->defaults('dll', 'task 017')->defaults('task', 'Task 017')->name('task.shell.017');
    Route::get('/task/shell/018', TaskShell::class)->defaults('title', 'ap dieu khoan thanh toan')->defaults('dll', 'task 018')->defaults('task', 'Task 018')->name('task.shell.018');
    Route::get('/task/shell/022', TaskShell::class)->defaults('title', 'ca cong no khe uoc')->defaults('dll', 'task 022')->defaults('task', 'Task 022')->name('task.shell.022');
    Route::get('/task/shell/023', TaskShell::class)->defaults('title', 'ca cong no phan cap')->defaults('dll', 'task 023')->defaults('task', 'Task 023')->name('task.shell.023');
    Route::get('/task/shell/024', TaskShell::class)->defaults('title', 'ca tinh lai khe uoc')->defaults('dll', 'task 024')->defaults('task', 'Task 024')->name('task.shell.024');
    Route::get('/task/shell/025', TaskShell::class)->defaults('title', 'ca xoa lai khe uoc')->defaults('dll', 'task 025')->defaults('task', 'Task 025')->name('task.shell.025');
    Route::get('/task/shell/026', TaskShell::class)->defaults('title', 'ca lai khe uoc')->defaults('dll', 'task 026')->defaults('task', 'Task 026')->name('task.shell.026');
    Route::get('/task/shell/031', TaskShell::class)->defaults('title', 'ca voucher khe uoc vay')->defaults('dll', 'task 031')->defaults('task', 'Task 031')->name('task.shell.031');
    Route::get('/task/shell/046', TaskShell::class)->defaults('title', 'SO tien ich ban hang')->defaults('dll', 'task 046')->defaults('task', 'Task 046')->name('task.shell.046');
    Route::get('/task/shell/049', TaskShell::class)->defaults('title', 'GL bangcan doi phat sinh')->defaults('dll', 'task 049')->defaults('task', 'Task 049')->name('task.shell.049');
    Route::get('/task/shell/060', TaskShell::class)->defaults('title', 'SO dondathang bomauhoadon')->defaults('dll', 'task 060')->defaults('task', 'Task 060')->name('task.shell.060');
    Route::get('/task/shell/061', TaskShell::class)->defaults('title', 'SO xuatdulieu banhang')->defaults('dll', 'task 061')->defaults('task', 'Task 061')->name('task.shell.061');
    Route::get('/task/shell/075', TaskShell::class)->defaults('title', 'PO tien ich muahang')->defaults('dll', 'task 075')->defaults('task', 'Task 075')->name('task.shell.075');
    Route::get('/task/shell/110', TaskShell::class)->defaults('title', 'IN tinh gia binhquan didong')->defaults('dll', 'task 110')->defaults('task', 'Task 110')->name('task.shell.110');
    Route::get('/task/shell/113', TaskShell::class)->defaults('title', 'IN tinh gia trungbinh thang')->defaults('dll', 'task 113')->defaults('task', 'Task 113')->name('task.shell.113');
    Route::get('/task/shell/115', TaskShell::class)->defaults('title', 'IN nhap so ton dauky')->defaults('dll', 'task 115')->defaults('task', 'Task 115')->name('task.shell.115');
    Route::get('/task/shell/116', TaskShell::class)->defaults('title', 'IN baocao f5 chidao 01')->defaults('dll', 'task 116')->defaults('task', 'Task 116')->name('task.shell.116');
    Route::get('/task/shell/158', TaskShell::class)->defaults('title', 'ar rpt f5 bccn01a drilldown')->defaults('dll', 'task 158')->defaults('task', 'Task 158')->name('task.shell.158');
    Route::get('/task/shell/166', TaskShell::class)->defaults('title', 'co dmbom dinh muc nguyen vat lieu')->defaults('dll', 'task 166')->defaults('task', 'Task 166')->name('task.shell.166');
    Route::get('/task/shell/168', TaskShell::class)->defaults('title', '180 co ky thuat san xuat')->defaults('dll', 'task 168')->defaults('task', 'Task 168')->name('task.shell.168');
    Route::get('/task/shell/181', TaskShell::class)->defaults('title', '196 system framework infrastructure')->defaults('dll', 'task 181')->defaults('task', 'Task 181')->name('task.shell.181');
    Route::get('/so/thong-bao-huy-hoa-don', TaskShell::class)->defaults('title', 'Thông báo hủy hóa đơn')->defaults('dll', 'SOHuyHD.dll')->defaults('task', 'Task 212')->name('so.notice.huyhd');
    Route::get('/so/bang-ke-hoa-don', TaskShell::class)->defaults('title', 'Bảng kê hóa đơn')->defaults('dll', 'SOBKHD.dll')->defaults('task', 'Task 213')->name('so.rpt.bkhd');
    Route::get('/so/thong-bao-mat-chay-hong', TaskShell::class)->defaults('title', 'Thông báo mất/cháy/hỏng hóa đơn')->defaults('dll', 'SOMCHHD.dll')->defaults('task', 'Task 215')->name('so.notice.mchhd');
    Route::get('/so/thong-bao-xoa-hoa-don', TaskShell::class)->defaults('title', 'Thông báo xóa hóa đơn')->defaults('dll', 'SOXoaHD.dll')->defaults('task', 'Task 216')->name('so.notice.xoahd');
    Route::get('/si/nam-tai-chinh', TaskShell::class)->defaults('title', 'Thay đổi năm tài chính')->defaults('dll', 'SIChangeYear.dll')->defaults('task', 'Task 218')->name('si.year.change');
    Route::get('/si/import-export', TaskShell::class)->defaults('title', 'Chuyển số liệu import/export')->defaults('dll', 'SIImportExport.dll')->defaults('task', 'Task 219')->name('si.tool.importexport');
    Route::get('/si/thong-tin-san-pham', TaskShell::class)->defaults('title', 'Thông tin sản phẩm')->defaults('dll', 'SIProductInfo.dll')->defaults('task', 'Task 220')->name('si.info.product');
    Route::get('/si/sap-xep-so-chung-tu', TaskShell::class)->defaults('title', 'Sắp xếp lại số chứng từ')->defaults('dll', 'SISortVoucher.dll')->defaults('task', 'Task 221')->name('si.tool.sortno');
    Route::get('/si/cong-cu-he-thong-1', TaskShell::class)->defaults('title', 'Công cụ hệ thống')->defaults('dll', 'SITool.dll')->defaults('task', 'Task 222')->name('si.tool.system1');
    Route::get('/si/cong-cu-he-thong-2', TaskShell::class)->defaults('title', 'Công cụ hệ thống 2')->defaults('dll', 'SITool2.dll')->defaults('task', 'Task 223')->name('si.tool.system2');
    Route::get('/si/cap-nhat-log', TaskShell::class)->defaults('title', 'Cập nhật log')->defaults('dll', 'SILog.dll')->defaults('task', 'Task 224')->name('si.tool.log');
    Route::get('/so/danhmuc/chuyen-khoan', TaskShell::class)->defaults('title', 'Danh mục chuyển khoản')->defaults('dll', 'SODMCK.dll')->defaults('task', 'Task 205')->name('so.dict.ck');
    Route::get('/so/danhmuc/don-gia-vat-tu', TaskShell::class)->defaults('title', 'Đơn giá vật tư')->defaults('dll', 'SODMDGVT.dll')->defaults('task', 'Task 206')->name('so.dict.dgvt');
    Route::get('/so/danhmuc/dieu-khoan-thanh-toan', TaskShell::class)->defaults('title', 'Điều khoản thanh toán SO')->defaults('dll', 'SODMDKTT.dll')->defaults('task', 'Task 207')->name('so.dict.dktt');
    Route::get('/so/danhmuc/hinh-thuc-thanh-toan', TaskShell::class)->defaults('title', 'Hình thức thanh toán SO')->defaults('dll', 'SODMHTTT.dll')->defaults('task', 'Task 208')->name('so.dict.httt');
    Route::get('/so/danhmuc/khuyen-mai', TaskShell::class)->defaults('title', 'Khuyến mãi SO')->defaults('dll', 'SODMKM.dll')->defaults('task', 'Task 209')->name('so.dict.km');
    Route::get('/so/danhmuc/nhan-vien-kinh-doanh', TaskShell::class)->defaults('title', 'Nhân viên kinh doanh')->defaults('dll', 'SODMNVKD.dll')->defaults('task', 'Task 210')->name('so.dict.nvkd');
    Route::get('/so/danhmuc/thue-suat', TaskShell::class)->defaults('title', 'Thuế suất SO')->defaults('dll', 'SODMTS.dll')->defaults('task', 'Task 211')->name('so.dict.ts');
    Route::get('/so/danhmuc/mau-hoa-don', TaskShell::class)->defaults('title', 'Mẫu hóa đơn SO')->defaults('dll', 'SOND51dmmhd.dll')->defaults('task', 'Task 214')->name('so.dict.mhd');
    Route::get('/so/danhmuc/quyet-dinh-hoa-don', TaskShell::class)->defaults('title', 'Quyết định áp dụng hóa đơn')->defaults('dll', 'SOND51dmqdadhd.dll')->defaults('task', 'Task 043')->name('so.dict.qdadhd');
    Route::get('/so/danhmuc/quyet-dinh-hoa-don-adhd', TaskShell::class)->defaults('title', 'Quyết định hóa đơn theo điểm bán hàng')->defaults('dll', 'SOND51adhd.dll')->defaults('task', 'Task 047')->name('so.dict.adhd');
    Route::get('/so/danhmuc/phat-hanh-hoa-don', TaskShell::class)->defaults('title', 'Phát hành hóa đơn')->defaults('dll', 'SOND51phhd.dll')->defaults('task', 'Task 050')->name('so.dict.phhd');
    Route::get('/so/danhmuc/tram-dinh-khoan', TaskShell::class)->defaults('title', 'Trạm định khoản')->defaults('dll', 'SODMTD.dll')->defaults('task', 'Task 052')->name('so.dict.dmtd');
    Route::get('/so/danhmuc/gia-ban', TaskShell::class)->defaults('title', 'Danh mục giá bán')->defaults('dll', 'SODMGIABAN.dll')->defaults('task', 'Task 053')->name('so.dict.giaban');
    Route::get('/so/danhmuc/phuong-tien-van-tai', TaskShell::class)->defaults('title', 'Phương tiện vận tải')->defaults('dll', 'SODMPTVT.dll')->defaults('task', 'Task 055')->name('so.dict.ptvt');
    Route::get('/so/danhmuc/ma-hoa-don', TaskShell::class)->defaults('title', 'Mã hóa đơn')->defaults('dll', 'SOND51mathd.dll')->defaults('task', 'Task 056')->name('so.dict.mathd');
    Route::get('/so/danhmuc/hang-hoa-khuyen-mai', TaskShell::class)->defaults('title', 'Hàng hóa khuyến mãi')->defaults('dll', 'SODMHHKM.dll')->defaults('task', 'Task 058')->name('so.dict.hhkm');
    Route::get('/so/danhmuc/tra-khuyen-mai', TaskShell::class)->defaults('title', 'Trả khuyến mãi')->defaults('dll', 'SODMTKM.dll')->defaults('task', 'Task 062')->name('so.dict.tkm');
    Route::get('/banhang/khachhang', Khachhang::class)->name('ar.khachhang');
    Route::get('/banhang/khachhang/create', KhachhangForm::class)->name('ar.khachhang.create');
    Route::get('/banhang/khachhang/edit/{id}', KhachhangForm::class)->name('ar.khachhang.edit');
    Route::get('/ar/danhmuc/nhom-khach-hang', Nhomkhachhang::class)->name('ar.nhomkhachhang');
    Route::get('/ar/danhmuc/nhom-khach-hang/create', NhomkhachhangEdit::class)->name('ar.nhomkhachhang.create');
    Route::get('/ar/danhmuc/nhom-khach-hang/edit/{id}', NhomkhachhangEdit::class)->name('ar.nhomkhachhang.edit');
    Route::get('/ar/danhmuc/phan-loai-khach-hang', Phanloaikhachhang::class)->name('ar.phanloaikhachhang');
    Route::get('/ar/danhmuc/phan-loai-khach-hang/create', PhanloaikhachhangEdit::class)->name('ar.phanloaikhachhang.create');
    Route::get('/ar/danhmuc/phan-loai-khach-hang/edit/{id}', PhanloaikhachhangEdit::class)->name('ar.phanloaikhachhang.edit');
    Route::prefix('banhang/baocao')->name('so.rpt.')->group(static function (): void {
        Route::get('/bangke-banhang', SoReportIndex::class)->defaults('routeName', 'so.rpt.bk01')->name('bk01');
        Route::get('/bangke-banhang-nhom', SoReportIndex::class)->defaults('routeName', 'so.rpt.bk02')->name('bk02');
        Route::get('/tonghop-banhang', SoReportIndex::class)->defaults('routeName', 'so.rpt.th01')->name('th01');
        Route::get('/tonghop-banhang-ky', SoReportIndex::class)->defaults('routeName', 'so.rpt.th02')->name('th02');
        Route::get('/lailo-mathang', SoReportIndex::class)->defaults('routeName', 'so.rpt.bcpt03')->name('bcpt03');
        Route::get('/doanhso-bophan', SoReportIndex::class)->defaults('routeName', 'so.rpt.bcpt04')->name('bcpt04');
        Route::get('/banhang-hai-chitieu', SoReportIndex::class)->defaults('routeName', 'so.rpt.bcpt06')->name('bcpt06');
        Route::get('/lailo-hopdong', SoReportIndex::class)->defaults('routeName', 'so.rpt.lailo')->name('lailo');
        Route::get('/nhatky-banhang', SoReportIndex::class)->defaults('routeName', 'so.rpt.nkc04')->name('nkc04');
        Route::get('/lailo-mathang-lairong', SoReportIndex::class)->defaults('routeName', 'so.rpt.bcpt03-net')->name('bcpt03-net');
        Route::get('/doanhso-nhanvien', SoReportIndex::class)->defaults('routeName', 'so.rpt.bcpt04-nv')->name('bcpt04-nv');
        Route::get('/tonghop-lailo-hopdong', SoReportIndex::class)->defaults('routeName', 'so.rpt.lailo-th')->name('lailo-th');
    });
    Route::prefix('banhang/congno/baocao')->name('ar.rpt.')->group(static function (): void {
        Route::get('/bangke-hoadon-phai-thu', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bk01')->name('bk01');
        Route::get('/so-chitiet-mot-khachhang', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn01')->name('bccn01');
        Route::get('/so-chitiet-nhieu-khachhang', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn01a')->name('bccn01a');
        Route::get('/can-doi-phat-sinh', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn02')->name('bccn02');
        Route::get('/tonghop-sodu-mot-taikhoan', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn03')->name('bccn03');
        Route::get('/doi-chieu-cong-no-tonghop', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn05')->name('bccn05');
        Route::get('/can-doi-theo-chitieu', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn04')->name('bccn04');
        Route::get('/so-chitiet-mot-khachhang-soluong', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn01sl')->name('bccn01sl');
        Route::get('/so-chitiet-nhieu-khachhang-lientuc', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn01a-cont')->name('bccn01a-cont');
        Route::get('/tonghop-sodu-dau', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn03-open')->name('bccn03-open');
        Route::get('/tonghop-sodu-cuoi', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn03-close')->name('bccn03-close');
        Route::get('/doi-chieu-cong-no-chitiet', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn05-detail')->name('bccn05-detail');
        Route::get('/can-doi-cong-no-theo-han-thanh-toan', ArReportIndex::class)->defaults('routeName', 'ar.rpt.bccn06')->name('bccn06');
    });

    Route::get('/muahang/cungcap', Cungcap::class)->name('ar.cungcap');
    Route::get('/muahang/nhacungcap', Cungcap::class)->name('po.cungcap');
    Route::get('/muahang/chiphimuahang/danhmuc', PoDmCpIndex::class)->name('po.dmcp');
    Route::get('/po/danhmuc/dieu-khoan-thanh-toan', TaskShell::class)->defaults('title', 'Điều khoản thanh toán mua')->defaults('dll', 'PODMDKTT.dll')->defaults('task', 'Task 064')->name('po.dict.dktt');
    Route::get('/po/danhmuc/hinh-thuc-thanh-toan', TaskShell::class)->defaults('title', 'Hình thức thanh toán mua')->defaults('dll', 'PODMHTTT.dll')->defaults('task', 'Task 068')->name('po.dict.httt');
    Route::get('/po/danhmuc/thue-dau-vao', TaskShell::class)->defaults('title', 'Thuế đầu vào')->defaults('dll', 'POTAIN.dll')->defaults('task', 'Task 079')->name('po.dict.tain');
    Route::get('/muahang/bu-tru-cong-no', FinanceVoucherIndex::class)->defaults('voucherCode', 'AP4')->name('ap.vch.ap4');
    Route::prefix('muahang/baocao')->name('po.rpt.')->group(static function (): void {
        Route::get('/bangke-muahang', PoReportIndex::class)->defaults('routeName', 'po.rpt.bk01')->name('bk01');
        Route::get('/bangke-muahang-nhom', PoReportIndex::class)->defaults('routeName', 'po.rpt.bk02')->name('bk02');
        Route::get('/tonghop-muahang', PoReportIndex::class)->defaults('routeName', 'po.rpt.th01')->name('th01');
        Route::get('/tonghop-muahang-ky', PoReportIndex::class)->defaults('routeName', 'po.rpt.th02')->name('th02');
        Route::get('/bangke-chiphiphimuahang', PoReportIndex::class)->defaults('routeName', 'po.rpt.bk03')->name('bk03');
        Route::get('/tonghop-chiphiphimuahang', PoReportIndex::class)->defaults('routeName', 'po.rpt.th03')->name('th03');
        Route::get('/muahang-hai-chitieu', PoReportIndex::class)->defaults('routeName', 'po.rpt.bcpt03')->name('bcpt03');
        Route::get('/nhatky-muahang', PoReportIndex::class)->defaults('routeName', 'po.rpt.nkc05')->name('nkc05');
    });
    Route::prefix('muahang/congno/baocao')->name('ap.rpt.')->group(static function (): void {
        Route::get('/bangke-hoadon-phai-tra', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bk01')->name('bk01');
        Route::get('/so-chitiet-mot-nhacungcap', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn01')->name('bccn01');
        Route::get('/so-chitiet-nhieu-nhacungcap', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn01a')->name('bccn01a');
        Route::get('/can-doi-phat-sinh', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn02')->name('bccn02');
        Route::get('/tonghop-sodu-mot-taikhoan', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn03')->name('bccn03');
        Route::get('/so-chitiet-mot-nhacungcap-soluong', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn01sl')->name('bccn01sl');
        Route::get('/doi-chieu-cong-no-tonghop', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn05')->name('bccn05');
        Route::get('/so-chitiet-nhieu-nhacungcap-lientuc', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn01a-cont')->name('bccn01a-cont');
        Route::get('/can-doi-theo-chitieu', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn04')->name('bccn04');
        Route::get('/tonghop-sodu-dau', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn03-open')->name('bccn03-open');
        Route::get('/tonghop-sodu-cuoi', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn03-close')->name('bccn03-close');
        Route::get('/doi-chieu-cong-no-chitiet', ApReportIndex::class)->defaults('routeName', 'ap.rpt.bccn05-detail')->name('bccn05-detail');
    });

    Route::prefix('muahang')->name('muahang.')->group(static function (): void {
        Route::get('/hoadonmua', Hoadonmua::class)->name('hoadonmua');
        Route::get('/hoadonmua/create', HoadonmuaEdit::class)->name('hoadonmua.create');
        Route::get('/hoadonmua/edit/{stt_rec}', HoadonmuaEdit::class)->name('hoadonmua.edit');
        Route::get('/donhangmua', PoVoucherIndex::class)->defaults('voucherCode', 'PO1')->name('po1');
        Route::get('/chiphimuahang', PoVoucherIndex::class)->defaults('voucherCode', 'PO4')->name('po4');
        Route::get('/xuat-tralai-nhacungcap', PoVoucherIndex::class)->defaults('voucherCode', 'PO5')->name('po5');
        Route::get('/hoadonmuadichvu', PoVoucherIndex::class)->defaults('voucherCode', 'PO6')->name('po6');
        Route::get('/hoadonmuanhapkhau', PoVoucherIndex::class)->defaults('voucherCode', 'PO7')->name('po7');
    });

    Route::get('/si/chungtu/phieu-nhap-kho', TaskShell::class)->defaults('title', 'Phiếu nhập kho SI')->defaults('dll', 'SIVchSI1.dll')->defaults('task', 'Task 096')->name('si.vch.si1');
    Route::get('/si/congcu/backup-restore', TaskShell::class)->defaults('title', 'Sao lưu phục hồi dữ liệu')->defaults('dll', 'SIBackup.dll')->defaults('task', 'Task 097')->name('si.tool.backup');
    Route::get('/si/danhmuc/hop-dong', TaskShell::class)->defaults('title', 'Danh mục hợp đồng')->defaults('dll', 'SIDMHD.dll')->defaults('task', 'Task 098')->name('si.dict.hopdong');
    Route::get('/si/baocao/log-chung-tu', TaskShell::class)->defaults('title', 'Thống kê cập nhật chứng từ')->defaults('dll', 'SIRptLOGCT.dll')->defaults('task', 'Task 099')->name('si.rpt.logct');
    Route::get('/in/danhmuc/cong-no-fifo', TaskShell::class)->defaults('title', 'Công nợ định mức FIFO')->defaults('dll', 'INCDFIFO.dll')->defaults('task', 'Task 101')->name('in.dict.cdfifo');
    Route::get('/in/danhmuc/lo', TaskShell::class)->defaults('title', 'Danh mục lô')->defaults('dll', 'INDMLO.dll')->defaults('task', 'Task 106')->name('in.dict.lo');
    Route::get('/in/danhmuc/nhom-hang-vat', TaskShell::class)->defaults('title', 'Danh mục nhóm hàng VAT')->defaults('dll', 'INDMNHVAT.dll')->defaults('task', 'Task 109')->name('in.dict.nhvat');
    Route::get('/in/danhmuc/phan-loai-vat-tu', TaskShell::class)->defaults('title', 'Phân loại vật tư')->defaults('dll', 'INDMPLVT.dll')->defaults('task', 'Task 112')->name('in.dict.plvt');
    Route::get('khohang/sanpham', Dmvt::class)->name('in.dmvt');
    Route::get('khohang/nhomsanpham', Dmnhvt::class)->name('in.dmnhvt');
    Route::get('khohang/khohang', Dmkho::class)->name('in.khohang');
    Route::prefix('khohang/chungtu')->name('in.vch.')->group(static function (): void {
        Route::get('/phieu-nhap-kho', InVoucherIndex::class)->defaults('voucherCode', 'IN1')->name('in1');
        Route::get('/phieu-xuat-kho', InVoucherIndex::class)->defaults('voucherCode', 'IN2')->name('in2');
        Route::get('/phieu-xuat-chuyen-kho', InVoucherIndex::class)->defaults('voucherCode', 'IN3')->name('in3');
        Route::get('/phieu-nhap-dieu-chuyen', InVoucherIndex::class)->defaults('voucherCode', 'IN4')->name('in4');
        Route::get('/phieu-xuat-cong-cu-dung-cu', InVoucherIndex::class)->defaults('voucherCode', 'IN5')->name('in5');
        Route::get('/phieu-thao-do-lap-rap', InVoucherIndex::class)->defaults('voucherCode', 'IN6')->name('in6');
    });
    Route::get('khohang/tonkho', Tonkho::class)->name('in.tonkho');
    Route::prefix('khohang/baocao')->name('in.rpt.')->group(static function (): void {
        Route::get('/the-kho', InReportIndex::class)->defaults('routeName', 'in.rpt.ctvt01')->name('ctvt01');
        Route::get('/the-kho-in-tat-ca', InReportIndex::class)->defaults('routeName', 'in.rpt.ctvt02')->name('ctvt02');
        Route::get('/tonghop-nhapxuatton', InReportIndex::class)->defaults('routeName', 'in.rpt.cd01')->name('cd01');
        Route::get('/bangke-phieu-nhap', InReportIndex::class)->defaults('routeName', 'in.rpt.bkn04')->name('bkn04');
        Route::get('/tonghop-hang-nhap', InReportIndex::class)->defaults('routeName', 'in.rpt.thn01')->name('thn01');
        Route::get('/tonghop-hang-nhap-hai-chitieu', InReportIndex::class)->defaults('routeName', 'in.rpt.thn02')->name('thn02');
        Route::get('/bangke-phieu-xuat', InReportIndex::class)->defaults('routeName', 'in.rpt.bkx04')->name('bkx04');
        Route::get('/tonghop-hang-xuat', InReportIndex::class)->defaults('routeName', 'in.rpt.thx01')->name('thx01');
        Route::get('/tonghop-hang-xuat-hai-chitieu', InReportIndex::class)->defaults('routeName', 'in.rpt.thx02')->name('thx02');
        Route::get('/thongtin-tinhgia-ntxt', InReportIndex::class)->defaults('routeName', 'in.rpt.bk01')->name('bk01');
        Route::get('/banggia-trungbinh', InReportIndex::class)->defaults('routeName', 'in.rpt.banggiatb')->name('banggiatb');
    });

    Route::get('/fa/tinh-khau-hao', TaskShell::class)->defaults('title', 'Tính khấu hao TSCĐ')->defaults('dll', 'FACalKHTS.dll')->defaults('task', 'Task 119')->name('fa.calc.khts');
    Route::get('/fa/dinh-gia-tai-san', TaskShell::class)->defaults('title', 'Định giá lại TSCĐ')->defaults('dll', 'FADGTS.dll')->defaults('task', 'Task 124')->name('fa.vch.dgts');
    Route::get('/fa/bao-ho-chieu-cu', TaskShell::class)->defaults('title', 'Bảo hộ chiếu cũ TSCĐ')->defaults('dll', 'FADGTS.dll')->defaults('task', 'Task 131')->name('fa.vch.dgts-old');
    Route::get('/fa/tam-ngung-khau-hao', TaskShell::class)->defaults('title', 'Tạm ngưng khấu hao')->defaults('dll', 'FAThoiKHTS.dll')->defaults('task', 'Task 133')->name('fa.config.thoikh');
    Route::get('/fa/giam-tai-san', TaskShell::class)->defaults('title', 'Giảm tài sản cố định')->defaults('dll', 'FAGiamTS.dll')->defaults('task', 'Task 134')->name('fa.vch.giamts');
    Route::get('/fa/xoa-phan-bo-ts-sang-gl', TaskShell::class)->defaults('title', 'Xóa phân bổ TSCĐ sang GL')->defaults('dll', 'FADelTS2GL.dll')->defaults('task', 'Task 135')->name('fa.transfer.delts2gl');
    Route::get('/fa/xoa-phan-bo-cc-sang-gl', TaskShell::class)->defaults('title', 'Xóa phân bổ CCDC sang GL')->defaults('dll', 'FADelCC2GL.dll')->defaults('task', 'Task 136')->name('fa.transfer.delcc2gl');
    Route::prefix('taisan/baocao')->name('fa.rpt.')->group(static function (): void {
        Route::get('/the-tscd', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts01')->name('bcts01');
        Route::get('/so-tscd', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts08')->name('bcts08');
        Route::get('/so-theo-doi-tscd-ccdc', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts09')->name('bcts09');
        Route::get('/bang-tinh-khau-hao', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts04')->name('bcts04');
        Route::get('/bang-phan-bo-khau-hao', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts05')->name('bcts05');
        Route::get('/tang-giam-tai-san', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts06')->name('bcts06');
        Route::get('/chi-tiet-kiem-ke-tai-san', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcts07')->name('bcts07');
        Route::get('/so-chi-tiet-ccdc', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bcpt10')->name('bcpt10');
        Route::get('/bangke-phieu-nhap-ccdc', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bkn04')->name('bkn04');
        Route::get('/tonghop-hang-nhap-ccdc', FaReportIndex::class)->defaults('routeName', 'fa.rpt.thn01')->name('thn01');
        Route::get('/bangke-phieu-xuat-ccdc', FaReportIndex::class)->defaults('routeName', 'fa.rpt.bkx04')->name('bkx04');
        Route::get('/tonghop-hang-xuat-ccdc', FaReportIndex::class)->defaults('routeName', 'fa.rpt.thx01')->name('thx01');
    });
    Route::prefix('taisan/danhmuc')->name('fa.')->group(static function (): void {
        Route::get('/nguon-von', FaDictionaryIndex::class)->defaults('routeName', 'fa.dmnv')->name('dmnv');
        Route::get('/bo-phan-su-dung', FaDictionaryIndex::class)->defaults('routeName', 'fa.dmbpsd')->name('dmbpsd');
    });

    Route::prefix('chiphi/baocao')->name('co.rpt.')->group(static function (): void {
        Route::get('/bang-ke-chung-tu', CoReportIndex::class)->defaults('routeName', 'co.rpt.bk02')->name('bk02');
        Route::get('/tong-hop-phat-sinh-nhom', CoReportIndex::class)->defaults('routeName', 'co.rpt.th01')->name('th01');
        Route::get('/bang-tinh-phan-bo-cptt', CoReportIndex::class)->defaults('routeName', 'co.rpt.khcp')->name('khcp');
        Route::get('/the-gia-thanh', CoReportIndex::class)->defaults('routeName', 'co.rpt.gt01')->name('gt01');
        Route::get('/bang-gia-thanh-san-pham', CoReportIndex::class)->defaults('routeName', 'co.rpt.gt02')->name('gt02');
        Route::get('/bangke-phieu-nhap', CoReportIndex::class)->defaults('routeName', 'co.rpt.bkn04')->name('bkn04');
        Route::get('/tonghop-hang-nhap', CoReportIndex::class)->defaults('routeName', 'co.rpt.thn01')->name('thn01');
        Route::get('/tonghop-hang-nhap-hai-chitieu', CoReportIndex::class)->defaults('routeName', 'co.rpt.thn02')->name('thn02');
        Route::get('/bangke-phieu-xuat', CoReportIndex::class)->defaults('routeName', 'co.rpt.bkx04')->name('bkx04');
        Route::get('/tonghop-hang-xuat', CoReportIndex::class)->defaults('routeName', 'co.rpt.thx01')->name('thx01');
        Route::get('/tonghop-hang-xuat-hai-chitieu', CoReportIndex::class)->defaults('routeName', 'co.rpt.thx02')->name('thx02');
        Route::get('/bang-gia-thanh-cong-trinh', CoReportIndex::class)->defaults('routeName', 'co.rpt.gt03')->name('gt03');
        Route::get('/bang-gia-thanh-cong-trinh-du-toan', CoReportIndex::class)->defaults('routeName', 'co.rpt.gt03-dutoan')->name('gt03-dutoan');
        Route::get('/lai-lo-cong-trinh', CoReportIndex::class)->defaults('routeName', 'co.rpt.gt03-lailo')->name('gt03-lailo');
        Route::get('/can-doi-cong-no-cong-trinh', CoReportIndex::class)->defaults('routeName', 'co.rpt.bccn04')->name('bccn04');
    });
    Route::prefix('chiphi/danhmuc')->name('co.')->group(static function (): void {
        Route::get('/san-pham', CoDictionaryIndex::class)->defaults('routeName', 'co.dmsp')->name('dmsp');
        Route::get('/cong-trinh', CoDictionaryIndex::class)->defaults('routeName', 'co.dmspct')->name('dmspct');
        Route::get('/nhom-cong-trinh', CoDictionaryIndex::class)->defaults('routeName', 'co.dmnhspct')->name('dmnhspct');
    });

    Route::get('hethong/system', Dashboard::class)->name('system.system');
    Route::get('hethong/company', CompanySelector::class)->name('system.company');
    Route::get('/si/congcu/dong-bo-he-thong', TaskShell::class)->defaults('title', 'Đồng bộ hệ thống')->defaults('dll', 'SIUtilities.dll')->defaults('task', 'Task 081')->name('si.tool.sync');
    Route::get('/si/danhmuc/tuy-chon-10', TaskShell::class)->defaults('title', 'Danh mục tùy chọn 10')->defaults('dll', 'SIDM10.dll')->defaults('task', 'Task 082')->name('si.dict.option10');
    Route::get('/si/thongtin/chuong-trinh', TaskShell::class)->defaults('title', 'Thông tin chương trình')->defaults('dll', 'SIAbout.dll')->defaults('task', 'Task 083')->name('si.info.program');
    Route::get('/si/danhmuc/bo-phan', TaskShell::class)->defaults('title', 'Danh mục bộ phận')->defaults('dll', 'SIDMBP.dll')->defaults('task', 'Task 084')->name('si.dict.bophan');
    Route::get('/si/cauhinh/tham-so', TaskShell::class)->defaults('title', 'Tham số hệ thống')->defaults('dll', 'SIOptions.dll')->defaults('task', 'Task 085')->name('si.config.params');
    Route::get('/si/congcu/bao-tri', TaskShell::class)->defaults('title', 'Bảo trì hệ thống')->defaults('dll', 'SIMaintenance.dll')->defaults('task', 'Task 086')->name('si.tool.maintenance');
    Route::get('/si/danhmuc/nhom-hop-dong', TaskShell::class)->defaults('title', 'Danh mục nhóm hợp đồng')->defaults('dll', 'SIDMNHHD.dll')->defaults('task', 'Task 088')->name('si.dict.nhomhd');
    Route::get('/si/danhmuc/ty-gia', TaskShell::class)->defaults('title', 'Tỷ giá ngoại tệ')->defaults('dll', 'SIDMTGNT.dll')->defaults('task', 'Task 089')->name('si.dict.tygia');
    Route::get('/si/cauhinh/theme', TaskShell::class)->defaults('title', 'Cấu hình theme giao diện')->defaults('dll', 'SITheme.dll')->defaults('task', 'Task 090')->name('si.config.theme');
    Route::get('/si/danhmuc/nhap-xuat', TaskShell::class)->defaults('title', 'Danh mục nhập xuất')->defaults('dll', 'SIDMNX.dll')->defaults('task', 'Task 091')->name('si.dict.nx');
    Route::get('/si/danhmuc/tuy-chon-9', TaskShell::class)->defaults('title', 'Danh mục tùy chọn 9')->defaults('dll', 'SIDM9.dll')->defaults('task', 'Task 092')->name('si.dict.option9');
    Route::get('/si/danhmuc/ngan-hang', TaskShell::class)->defaults('title', 'Danh mục ngân hàng')->defaults('dll', 'SIDMNH.dll')->defaults('task', 'Task 095')->name('si.dict.nganhang');
    Route::prefix('hethong/danhmuc')->name('si.')->group(static function (): void {
        Route::get('/chung-tu', SiDictionaryIndex::class)->defaults('routeName', 'si.dmct')->name('dmct');
        Route::get('/khoan-muc-phi', SiDictionaryIndex::class)->defaults('routeName', 'si.dmphi')->name('dmphi');
        Route::get('/ngoai-te', SiDictionaryIndex::class)->defaults('routeName', 'si.dmnt')->name('dmnt');
    });
    Route::resource('hethong/user', SystemUserController::class)->names('system.user');
    Route::resource('hethong/website', SystemWebsiteController::class)->names('system.website');
    Route::get('hethong/menu', Menu::class)->name('system.menu');
    Route::get('hethong/year', YearSelector::class)->name('system.year');
    Route::get('simba/dictionaries/{routeName}', SimbaDictionaryIndex::class)
        ->where('routeName', '[A-Za-z0-9_.-]+')
        ->name('simba.dictionary')
    ;
    Route::get('simba/reports/{routeName}', SimbaReportIndex::class)
        ->where('routeName', '[A-Za-z0-9_.-]+')
        ->name('simba.report')
    ;
    Route::get('simba/processes/{routeName}', SimbaProcessIndex::class)
        ->where('routeName', '[A-Za-z0-9_.-]+')
        ->name('simba.process')
    ;

    // Balance Management Routes - Livewire Components
    Route::prefix('hethong/balance')->name('system.balance.')->group(static function (): void {
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
