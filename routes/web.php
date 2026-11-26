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
use Diepxuan\Catalog\Http\Livewire\So\Dict\Phanloaikhachhang;
use Diepxuan\Catalog\Http\Livewire\So\Dict\PhanloaikhachhangEdit;
use Diepxuan\Catalog\Http\Livewire\So\Vch\Hoadonbanhang;
use Diepxuan\Catalog\Http\Livewire\So\Dict\Ardmkh as SoArdmkh;
use Diepxuan\Catalog\Http\Livewire\So\Dict\ArdmkhForm as SoArdmkhForm;
use Diepxuan\Catalog\Http\Livewire\Ca\Dict\Ardmkh as CaArdmkh;
use Diepxuan\Catalog\Http\Livewire\Ca\Dict\ArdmkhForm as CaArdmkhForm;
use Diepxuan\Catalog\Http\Livewire\Ca\Dict\Kheuoc;
use Diepxuan\Catalog\Http\Livewire\Ca\Dict\KheuocEdit;
use Diepxuan\Catalog\Http\Livewire\Ca\Rpt\Chi as CaRptChi;
use Diepxuan\Catalog\Http\Livewire\Ca\Rpt\Nganhang as CaRptNganhang;
use Diepxuan\Catalog\Http\Livewire\Ca\Rpt\Thu as CaRptThu;
use Diepxuan\Catalog\Http\Livewire\Ca\Rpt\Tienmat as CaRptTienmat;
use Diepxuan\Catalog\Http\Livewire\Home\Dashboard as DashboardLivewire;
use Diepxuan\Catalog\Http\Controllers\YearController;
use Diepxuan\Catalog\Http\Livewire\In\Rpt\Inrptcd02;
use Diepxuan\Catalog\Http\Livewire\Po\Dict\Podmcp;
use Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh as PoArdmkh;
use Diepxuan\Catalog\Http\Livewire\Po\Dict\ArdmkhForm;
use Diepxuan\Catalog\Http\Livewire\Si\Vch\Smks;
use Diepxuan\Catalog\Http\Livewire\Si\Vch\YearSelector;
use Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01Sl as SoArrptbccn01Sl;
use Diepxuan\Catalog\Http\Livewire\So\Vch\Sovchso3;
use Diepxuan\Catalog\Http\Livewire\So\Vch\Sovchso3Edit;
use Diepxuan\Catalog\Http\Livewire\Po\Rpt\Arrptbccn01 as PoArrptbccn01;
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
use Diepxuan\Support\Http\Middleware\CorpAutoLogin;
use Diepxuan\Support\Http\Middleware\DebugOnly;
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
    Route::resource('banhang/bangkebanhang', SellController::class)->names('sell.list');

    // Source routes for SimbaERP screens generated from simba-docs/data/sysMenu.md + zsysmenu.md.
    // Menus with concrete Livewire screens point to the component; the rest render the metadata shell.
    Route::prefix('_simba-source')->group(static function (): void {
        Route::redirect('{path}', '/simba/{path}')
            ->where('path', '.*')
        ;

        $sourceRoutes = [
            // Component đã chuẩn hóa namespace (Ca\Dict\Kheuoc) và sẵn sàng; cần bật menu trong sysMenu trước.
            ['uri' => 'si/vch/year', 'name' => 'si.vch.year', 'module' => 'si', 'kind' => 'vch', 'slug' => 'year', 'component' => YearSelector::class],
        ];

        // Cache route da khai bao de tranh trung name khi dang ky theo convention.
        $registeredNames = array_column($sourceRoutes, 'name');

        foreach ($sourceRoutes as $sourceRoute) {
            Route::get($sourceRoute['uri'], $sourceRoute['component'])
                ->defaults('module', $sourceRoute['module'])
                ->defaults('kind', $sourceRoute['kind'])
                ->defaults('slug', $sourceRoute['slug'])
                // BUG FIX: SimbaMenuTargetResolver::componentForRoute() doc
                // defaults['component'] de biet route da co Livewire component
                // that hay chua. Thieu key nay -> SimbaPage LUON render shell
                // metadata du component da ton tai. Xem task 105.
                ->defaults('component', $sourceRoute['component'])
                ->name($sourceRoute['name'])
            ;
        }

        // -------------------------------------------------------------------
        // Tu dong dang ky route cho component dat DUNG convention
        // (PROJECT_CONVENTIONS muc 2.2):
        //
        //   src/Http/Livewire/{Module}/{Kind}/{PascalSlug}.php
        //     => route {module}.{kind}.{slug}
        //     => uri   {module}/{kind}/{slug}
        //
        // Muc dich: moi man bao cao / chung tu chi can THEM FILE, khong phai sua
        // routes/web.php -> nhieu PR song song khong con conflict o file dung chung.
        //
        // Component da khai bao tuong minh trong $sourceRoutes (khi slug/uri khong
        // theo convention, hoac co create/edit rieng) se duoc bo qua o day.
        // -------------------------------------------------------------------
        $livewireRoot = __DIR__ . '/../src/Http/Livewire';
        $kindSegments = ['Dict' => 'dict', 'Vch' => 'vch', 'Rpt' => 'rpt', 'Proc' => 'proc'];

        foreach ($kindSegments as $kindDir => $kind) {
            foreach (glob($livewireRoot . '/*/' . $kindDir, GLOB_ONLYDIR) ?: [] as $moduleDir) {
                $module = strtolower(basename(\dirname($moduleDir)));

                foreach (glob($moduleDir . '/*.php') ?: [] as $file) {
                    $class = basename($file, '.php');

                    // Component chinh: {PascalSlug}.php  -> route {module}.{kind}.{slug}
                    // Form/Edit:       {PascalSlug}Form.php hoac {PascalSlug}Edit.php
                    //                  -> route {module}.{kind}.{slug}.create + .edit
                    //
                    // Nho vay moi man hinh CRUD chi can THEM FILE, khong phai sua
                    // $sourceRoutes. Xem docs/PROJECT_CONVENTIONS.md muc 2.2.
                    $isForm = (bool) preg_match('/(Form|Edit)$/', $class);

                    // Helper khac (List/Page/Rename) van bo qua.
                    if (!$isForm && preg_match('/(List|Page|Rename)$/', $class)) {
                        continue;
                    }

                    $baseClass = $isForm ? preg_replace('/(Form|Edit)$/', '', $class) : $class;
                    $slug = strtolower($baseClass);

                    $fqcn = 'Diepxuan\\Catalog\\Http\\Livewire\\' . ucfirst($module) . '\\' . $kindDir . '\\' . $class;
                    if (!class_exists($fqcn)) {
                        continue;
                    }

                    if ($isForm) {
                        // create: uri {module}/{kind}/{slug}/create
                        $createName = "{$module}.{$kind}.{$slug}.create";
                        if (!in_array($createName, $registeredNames, true)) {
                            Route::get("{$module}/{$kind}/{$slug}/create", $fqcn)
                                ->defaults('module', $module)
                                ->defaults('kind', $kind)
                                ->defaults('slug', $slug)
                                ->defaults('component', $fqcn)
                                ->defaults('action', 'create')
                                ->name($createName)
                            ;
                            $registeredNames[] = $createName;
                        }

                        // edit: uri {module}/{kind}/{slug}/{id}/edit
                        $editName = "{$module}.{$kind}.{$slug}.edit";
                        if (!in_array($editName, $registeredNames, true)) {
                            Route::get("{$module}/{$kind}/{$slug}/{id}/edit", $fqcn)
                                ->defaults('module', $module)
                                ->defaults('kind', $kind)
                                ->defaults('slug', $slug)
                                ->defaults('component', $fqcn)
                                ->defaults('action', 'edit')
                                ->where('id', '[^/]+')
                                ->name($editName)
                            ;
                            $registeredNames[] = $editName;
                        }

                        continue;
                    }

                    $routeName = "{$module}.{$kind}.{$slug}";

                    if (in_array($routeName, $registeredNames, true)) {
                        continue;
                    }

                    Route::get("{$module}/{$kind}/{$slug}", $fqcn)
                        ->defaults('module', $module)
                        ->defaults('kind', $kind)
                        ->defaults('slug', $slug)
                        ->defaults('component', $fqcn)
                        ->name($routeName)
                    ;
                    $registeredNames[] = $routeName;
                }
            }
        }
    });

    // Canonical Simba shell entrypoint. Source routes above keep component/defaults;
    // public /simba/... URLs always render through SimbaPage.
    Route::prefix('simba')->name('simba.')->group(static function (): void {
        Route::get('/', SimbaPage::class)->name('index');
        Route::post('si/vch/year/select', [YearController::class, 'select'])->name('si.vch.year.select');
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
        Route::redirect('year', '/simba/si/vch/year')->name('year');

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

// Chẩn đoán scheme/proxy: chỉ mở khi APP_DEBUG=true và APP_ENV != production.
// Trên production route trả 404 để không lộ thông tin hạ tầng.
Route::get('/check-proxy', static fn () => [
    'app_url'           => config('app.url'),
    'url()'             => url('/'),
    'secure_url()'      => secure_url('/'),
    'request_scheme'    => request()->getScheme(),
    'request_secure'    => request()->isSecure(),
    'x_forwarded_proto' => request()->header('x-forwarded-proto'),
    'server_https'      => $_SERVER['HTTPS'] ?? null,
])->middleware([DebugOnly::class]);

/*
|--------------------------------------------------------------------------
| Test Routes - UTF-8 Blade Test
|--------------------------------------------------------------------------
*/

Route::get('test/utf8', static fn () => view('catalog::test.utf8-test'))->name('test.utf8')->middleware(['web', DebugOnly::class]);
