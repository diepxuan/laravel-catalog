<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Feature;

use Illuminate\Support\Facades\Route;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Tests\TestCase;

/**
 * Bao dam MOI component Livewire deu co route tuong ung.
 *
 * Tu 2026-11-26, route duoc sinh TU DONG tu cau truc thu muc
 * (xem docs/PROJECT_CONVENTIONS.md muc 2.2):
 *
 *   src/Http/Livewire/{Module}/{Kind}/{PascalSlug}.php      -> {module}.{kind}.{slug}
 *   src/Http/Livewire/{Module}/{Kind}/{PascalSlug}Form.php  -> {module}.{kind}.{slug}.create + .edit
 *   src/Http/Livewire/{Module}/{Kind}/{PascalSlug}Edit.php  -> {module}.{kind}.{slug}.create + .edit
 *
 * Test nay quet THAT thu muc (khong hardcode danh sach) -> bat ngay
 * component mo coi, sai namespace, hoac sai convention dat ten.
 */
final class SourceRouteCoverageTest extends TestCase
{
    /** Component khong sinh route (helper/layout/abstract). */
    private const NON_ROUTABLE_SUFFIXES = ['Abstract', 'Concerns', 'List', 'Page', 'Rename'];

    public function testMoiComponentLivewireDeuCoRouteTuongUng(): void
    {
        $violations = [];

        foreach ($this->componentFiles() as $file) {
            $class = basename($file, '.php');

            if ($this->isNonRoutable($class)) {
                continue;
            }

            $module = strtolower(basename(\dirname(\dirname($file))));
            $kindDir = basename(\dirname($file));
            $kind = strtolower($kindDir);

            $fqcn = 'Diepxuan\\Catalog\\Http\\Livewire\\' . ucfirst($module) . '\\' . $kindDir . '\\' . $class;

            if (!class_exists($fqcn)) {
                $violations[] = "Class khong load duoc: {$fqcn} (file: {$file})";

                continue;
            }

            $isForm = (bool) preg_match('/(Form|Edit)$/', $class);
            $base = $isForm ? preg_replace('/(Form|Edit)$/', '', $class) : $class;
            $slug = strtolower($base);

            $expected = $isForm
                ? ["{$module}.{$kind}.{$slug}.create", "{$module}.{$kind}.{$slug}.edit"]
                : ["{$module}.{$kind}.{$slug}"];

            foreach ($expected as $routeName) {
                $registered = Route::getRoutes()->getByName($routeName);

                if (null === $registered) {
                    $violations[] = "Thieu route '{$routeName}' cho {$fqcn}"
                        . " (ky vong tu file {$class} trong {$kindDir}/)";

                    continue;
                }

                $actual = $registered->getActionName();

                if ($actual !== $fqcn) {
                    $violations[] = "Route '{$routeName}' tro sai component:"
                        . " '{$actual}', ky vong '{$fqcn}'";
                }
            }
        }

        self::assertSame([], $violations, "Phat hien component khong co route:\n  - " . implode("\n  - ", $violations));
    }

    public function testKhongConEntryKhaiBaoTayNaoTrongSourceRoutes(): void
    {
        $routeFile = dirname(__DIR__, 2) . '/routes/web.php';
        $src = (string) file_get_contents($routeFile);

        preg_match_all("/^\s+\['uri' => '([^']+)', 'name' => '([^']+)'/m", $src, $m);

        // Chi con si.vch.year (helper co route POST rieng cho doi nam lam viec).
        $allowed = ['si.vch.year'];
        $unexpected = array_values(array_diff($m[2], $allowed));

        self::assertSame(
            [],
            $unexpected,
            "routes/web.php con entry khai bao tay (nen de auto-register sinh):\n  - "
                . implode("\n  - ", $unexpected)
        );
    }

    /**
     * @return list<string> duong dan tuyet doi toi file .php trong Livewire/{Module}/{Kind}/
     */
    private function componentFiles(): array
    {
        $root = dirname(__DIR__, 2) . '/src/Http/Livewire';
        $files = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            assert($file instanceof SplFileInfo);

            if (!$file->isFile() || 'php' !== $file->getExtension()) {
                continue;
            }

            $path = $file->getPathname();

            // Chi lay dung 2 cap: {Module}/{Kind}/{Class}.php
            if (!preg_match('#/Livewire/[A-Za-z0-9]+/(Dict|Vch|Rpt|Proc)/[A-Za-z0-9_]+\.php$#', $path)) {
                continue;
            }

            $files[] = $path;
        }

        sort($files);

        return $files;
    }

    private function isNonRoutable(string $class): bool
    {
        foreach (self::NON_ROUTABLE_SUFFIXES as $suffix) {
            if (str_ends_with($class, $suffix)) {
                return true;
            }
        }

        return false;
    }

    public function testShellSourceRoutesAreNotRegisteredAsConcreteComponentRoutes(): void
    {
        foreach ([
            'gl.vch.glvchgl1',
            'po.vch.povchpo1',
            'so.rpt.sorptbcpt03',
        ] as $routeName) {
            self::assertFalse(Route::has($routeName), "Shell route {$routeName} should stay handled by /simba shell");
        }
    }

    public function testCanonicalPoSupplierCreateEditUrlsResolve(): void
    {
        foreach ([
            '/simba/po/dict/ardmkh/create' => 'simba.create',
            '/simba/po/dict/ardmkh/1CHIBANRAO/edit' => 'simba.edit',
        ] as $url => $routeName) {
            $resolved = Route::getRoutes()->match(request()->create($url, 'GET'));

            self::assertSame($routeName, $resolved->getName(), "Canonical PO supplier URL {$url} resolved incorrectly");
            self::assertSame('po', $resolved->parameter('module'));
            self::assertSame('dict', $resolved->parameter('kind'));
            self::assertSame('ardmkh', $resolved->parameter('slug'));
        }
    }

    public function testCanonicalSmksUrlResolvesThroughSimbaShell(): void
    {
        $resolved = Route::getRoutes()->match(request()->create('/simba/si/vch/smks', 'GET'));

        self::assertSame('simba.show', $resolved->getName());
        self::assertSame('si', $resolved->parameter('module'));
        self::assertSame('vch', $resolved->parameter('kind'));
        self::assertSame('smks', $resolved->parameter('slug'));
    }

    public function testLegacySystemLockUrlRedirectsToSmks(): void
    {
        $resolved = Route::getRoutes()->match(request()->create('/hethong/system', 'GET'));

        self::assertSame('system.system', $resolved->getName());
        self::assertStringContainsString('RedirectController', $resolved->getActionName());
        self::assertSame('/simba/si/vch/smks', $resolved->defaults['destination'] ?? null);
    }

    public function testLegacyPoSupplierCreateEditUrlsDoNotResolve(): void
    {
        foreach ([
            '/muahang/nhacungcap/create',
            '/muahang/nhacungcap/edit/1CHIBANRAO',
        ] as $url) {
            self::assertFalse($this->urlResolves($url), "Legacy PO supplier URL {$url} should not resolve");
        }
    }

    public function testLegacyEmployeeCreateEditUrlsDoNotResolve(): void
    {
        foreach ([
            '/cash/nhanvien/create',
            '/cash/nhanvien/edit/1',
        ] as $url) {
            self::assertFalse($this->urlResolves($url), "Legacy employee URL {$url} should not resolve");
        }
    }

    public function testCanonicalEmployeeCreateEditUrlsResolve(): void
    {
        foreach ([
            '/simba/ca/dict/ardmkh/create' => 'simba.create',
            '/simba/ca/dict/ardmkh/1/edit' => 'simba.edit',
        ] as $url => $routeName) {
            $resolved = Route::getRoutes()->match(request()->create($url, 'GET'));

            self::assertSame($routeName, $resolved->getName(), "Canonical employee URL {$url} resolved incorrectly");
            self::assertSame('ca', $resolved->parameter('module'));
            self::assertSame('dict', $resolved->parameter('kind'));
            self::assertSame('ardmkh', $resolved->parameter('slug'));
        }
    }

    public function testCanonicalSoCustomerCreateEditUrlsResolve(): void
    {
        foreach ([
            '/simba/so/dict/ardmkh/create' => 'simba.create',
            '/simba/so/dict/ardmkh/KH001/edit' => 'simba.edit',
        ] as $url => $routeName) {
            $resolved = Route::getRoutes()->match(request()->create($url, 'GET'));

            self::assertSame($routeName, $resolved->getName(), "Canonical SO customer URL {$url} resolved incorrectly");
            self::assertSame('so', $resolved->parameter('module'));
            self::assertSame('dict', $resolved->parameter('kind'));
            self::assertSame('ardmkh', $resolved->parameter('slug'));
        }
    }

    public function testCanonicalSorptbk01UrlsResolve(): void
    {
        foreach ([
            '/simba/so/rpt/sorptbk01' => 'simba.show',
            '/simba/so/rpt/sorptbk01062002' => 'simba.show',
        ] as $url => $routeName) {
            $resolved = Route::getRoutes()->match(request()->create($url, 'GET'));

            self::assertSame($routeName, $resolved->getName(), "Canonical SORptBK01 URL {$url} resolved incorrectly");
            self::assertSame('so', $resolved->parameter('module'));
            self::assertSame('rpt', $resolved->parameter('kind'));
        }
    }

    private function urlResolves(string $url): bool
    {
        try {
            Route::getRoutes()->match(request()->create($url, 'GET'));

            return true;
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return false;
        }
    }

    /**
     * @return list<array{module:string,kind:string,slug:string,routeName:string,url:string,component:class-string}>
     */
    private function componentBackedSourceRoutes(): array
    {
        return [
            ['module' => 'ca', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'ca.dict.ardmkh', 'url' => '/_simba-source/ca/dict/ardmkh', 'component' => \Diepxuan\Catalog\Http\Livewire\Ca\Dict\Ardmkh::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca1', 'routeName' => 'ca.vch.cavchca1', 'url' => '/_simba-source/ca/vch/cavchca1', 'component' => \Diepxuan\Catalog\Http\Livewire\Ca\Vch\Phieuthu::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca2', 'routeName' => 'ca.vch.cavchca2', 'url' => '/_simba-source/ca/vch/cavchca2', 'component' => \Diepxuan\Catalog\Http\Livewire\Ca\Vch\Phieuchi::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca3', 'routeName' => 'ca.vch.cavchca3', 'url' => '/_simba-source/ca/vch/cavchca3', 'component' => \Diepxuan\Catalog\Http\Livewire\Ca\Vch\Baoco::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca4', 'routeName' => 'ca.vch.cavchca4', 'url' => '/_simba-source/ca/vch/cavchca4', 'component' => \Diepxuan\Catalog\Http\Livewire\Ca\Vch\Baono::class],
            ['module' => 'gl', 'kind' => 'dict', 'slug' => 'gldmtk', 'routeName' => 'gl.dict.gldmtk', 'url' => '/_simba-source/gl/dict/gldmtk', 'component' => \Diepxuan\Catalog\Http\Livewire\Gl\Dict\Taikhoan::class],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmkho', 'routeName' => 'in.dict.indmkho', 'url' => '/_simba-source/in/dict/indmkho', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Dict\Dmkho::class],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmnhvt', 'routeName' => 'in.dict.indmnhvt', 'url' => '/_simba-source/in/dict/indmnhvt', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Dict\Dmnhvt::class],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmvt', 'routeName' => 'in.dict.indmvt', 'url' => '/_simba-source/in/dict/indmvt', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Dict\IndmvtPage::class],
            ['module' => 'in', 'kind' => 'rpt', 'slug' => 'inrptcd02', 'routeName' => 'in.rpt.inrptcd02', 'url' => '/_simba-source/in/rpt/inrptcd02', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Rpt\Inrptcd02::class],
            ['module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn01103011', 'routeName' => 'po.rpt.arrptbccn01103011', 'url' => '/_simba-source/po/rpt/arrptbccn01103011', 'component' => \Diepxuan\Catalog\Http\Livewire\Po\Rpt\Arrptbccn01::class],
            ['module' => 'po', 'kind' => 'rpt', 'slug' => 'arrptbccn01103023', 'routeName' => 'po.rpt.arrptbccn01103023', 'url' => '/_simba-source/po/rpt/arrptbccn01103023', 'component' => \Diepxuan\Catalog\Http\Livewire\Po\Rpt\Arrptbccn01Sl::class],
            ['module' => 'po', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'po.dict.ardmkh', 'url' => '/_simba-source/po/dict/ardmkh', 'component' => \Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh::class],
            ['module' => 'po', 'kind' => 'dict', 'slug' => 'podmcp', 'routeName' => 'po.dict.podmcp', 'url' => '/_simba-source/po/dict/podmcp', 'component' => \Diepxuan\Catalog\Http\Livewire\Po\Dict\Podmcp::class],
            ['module' => 'si', 'kind' => 'vch', 'slug' => 'smks', 'routeName' => 'si.vch.smks', 'url' => '/_simba-source/si/vch/smks', 'component' => \Diepxuan\Catalog\Http\Livewire\Si\Vch\Smks::class],
            ['module' => 'so', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'so.dict.ardmkh', 'url' => '/_simba-source/so/dict/ardmkh', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Dict\Ardmkh::class],
            ['module' => 'so', 'kind' => 'dict', 'slug' => 'ardmplkh', 'routeName' => 'so.dict.ardmplkh', 'url' => '/_simba-source/so/dict/ardmplkh', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Dict\Phanloaikhachhang::class],
            ['module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn01', 'routeName' => 'so.rpt.arrptbccn01', 'url' => '/_simba-source/so/rpt/arrptbccn01', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01::class],
            ['module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn01063014', 'routeName' => 'so.rpt.arrptbccn01063014', 'url' => '/_simba-source/so/rpt/arrptbccn01063014', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01::class],
            ['module' => 'so', 'kind' => 'rpt', 'slug' => 'arrptbccn01063038', 'routeName' => 'so.rpt.arrptbccn01063038', 'url' => '/_simba-source/so/rpt/arrptbccn01063038', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01Sl::class],
            ['module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbk01', 'routeName' => 'so.rpt.sorptbk01', 'url' => '/_simba-source/so/rpt/sorptbk01', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Rpt\Sorptbk01::class],
            ['module' => 'so', 'kind' => 'rpt', 'slug' => 'sorptbk01062002', 'routeName' => 'so.rpt.sorptbk01062002', 'url' => '/_simba-source/so/rpt/sorptbk01062002', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Rpt\Sorptbk01::class],
            ['module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso1', 'routeName' => 'so.vch.sovchso1', 'url' => '/_simba-source/so/vch/sovchso1', 'component' => \Diepxuan\Catalog\Http\Livewire\So\Vch\Hoadonbanhang::class],
        ];
    }
}
