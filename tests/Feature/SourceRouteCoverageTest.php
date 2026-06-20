<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class SourceRouteCoverageTest extends TestCase
{
    public function testComponentBackedSourceRoutesExistAsNamedComponentRegistry(): void
    {
        foreach ($this->componentBackedSourceRoutes() as $route) {
            $registered = Route::getRoutes()->getByName($route['routeName']);

            self::assertNotNull($registered, "Missing route name {$route['routeName']}");
            self::assertSame(
                ltrim($route['url'], '/'),
                $registered->uri(),
                "Route URI mismatch for {$route['routeName']}"
            );
            self::assertSame($route['module'], $registered->defaults['module'] ?? null);
            self::assertSame($route['kind'], $registered->defaults['kind'] ?? null);
            self::assertSame($route['slug'], $registered->defaults['slug'] ?? null);
            self::assertSame($route['component'], $registered->getActionName());
        }
    }

    public function testSimbaSourceUrlsRedirectToCanonicalSimbaUrls(): void
    {
        foreach ($this->componentBackedSourceRoutes() as $route) {
            $resolved = Route::getRoutes()->match(request()->create($route['url'], 'GET'));

            self::assertNull($resolved->getName(), "Source URL {$route['url']} should match the unnamed catch-all redirect");
            self::assertStringContainsString('RedirectController', $resolved->getActionName());
            self::assertSame('/simba/{path}', $resolved->defaults['destination'] ?? null);
            self::assertSame($route['module'] . '/' . $route['kind'] . '/' . $route['slug'], $resolved->parameter('path'));
        }

        foreach ([
            '/_simba-source/po/dict/ardmkh/create' => '/simba/po/dict/ardmkh/create',
            '/_simba-source/po/dict/ardmkh/1CHIBANRAO/edit' => '/simba/po/dict/ardmkh/1CHIBANRAO/edit',
        ] as $sourceUrl => $canonicalUrl) {
            $resolved = Route::getRoutes()->match(request()->create($sourceUrl, 'GET'));

            self::assertNull($resolved->getName(), "Source URL {$sourceUrl} should match the unnamed catch-all redirect");
            self::assertStringContainsString('RedirectController', $resolved->getActionName());
            self::assertSame('/simba/{path}', $resolved->defaults['destination'] ?? null);
            self::assertSame(ltrim(str_replace('/simba/', '', $canonicalUrl), '/'), $resolved->parameter('path'));
        }
    }

    public function testShellSourceRoutesAreNotRegisteredAsConcreteComponentRoutes(): void
    {
        foreach ([
            'gl.vch.glvchgl1',
            'po.vch.povchpo1',
            'po.vch.povchpo3',
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

    public function testLegacyPoSupplierCreateEditUrlsDoNotResolve(): void
    {
        foreach ([
            '/muahang/nhacungcap/create',
            '/muahang/nhacungcap/edit/1CHIBANRAO',
        ] as $url) {
            self::assertFalse($this->urlResolves($url), "Legacy PO supplier URL {$url} should not resolve");
        }
    }

    public function testLegacyEmployeeCreateEditUrlsStillResolve(): void
    {
        foreach ([
            '/cash/nhanvien/create',
            '/cash/nhanvien/edit/1',
        ] as $url) {
            $resolved = Route::getRoutes()->match(request()->create($url, 'GET'));

            self::assertNotNull($resolved->getName(), "Legacy employee URL {$url} did not resolve to a named route");
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
            ['module' => 'ca', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'ca.dict.ardmkh', 'url' => '/_simba-source/ca/dict/ardmkh', 'component' => \Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc\Nhanvien::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca1', 'routeName' => 'ca.vch.cavchca1', 'url' => '/_simba-source/ca/vch/cavchca1', 'component' => \Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuthu::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca2', 'routeName' => 'ca.vch.cavchca2', 'url' => '/_simba-source/ca/vch/cavchca2', 'component' => \Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuchi::class],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca4', 'routeName' => 'ca.vch.cavchca4', 'url' => '/_simba-source/ca/vch/cavchca4', 'component' => \Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baoco::class],
            ['module' => 'gl', 'kind' => 'dict', 'slug' => 'gldmtk', 'routeName' => 'gl.dict.gldmtk', 'url' => '/_simba-source/gl/dict/gldmtk', 'component' => \Diepxuan\Catalog\Http\Livewire\Gl\Taikhoan::class],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmkho', 'routeName' => 'in.dict.indmkho', 'url' => '/_simba-source/in/dict/indmkho', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Dmkho::class],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmnhvt', 'routeName' => 'in.dict.indmnhvt', 'url' => '/_simba-source/in/dict/indmnhvt', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Dmnhvt::class],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmvt', 'routeName' => 'in.dict.indmvt', 'url' => '/_simba-source/in/dict/indmvt', 'component' => \Diepxuan\Catalog\Http\Livewire\In\Dmvt::class],
            ['module' => 'po', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'po.dict.ardmkh', 'url' => '/_simba-source/po/dict/ardmkh', 'component' => \Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh::class],
            ['module' => 'po', 'kind' => 'dict', 'slug' => 'podmcp', 'routeName' => 'po.dict.podmcp', 'url' => '/_simba-source/po/dict/podmcp', 'component' => \Diepxuan\Catalog\Http\Livewire\Muahang\PoDmCpIndex::class],
            ['module' => 'so', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'so.dict.ardmkh', 'url' => '/_simba-source/so/dict/ardmkh', 'component' => \Diepxuan\Catalog\Http\Livewire\Banhang\Khachhang::class],
            ['module' => 'so', 'kind' => 'dict', 'slug' => 'ardmplkh', 'routeName' => 'so.dict.ardmplkh', 'url' => '/_simba-source/so/dict/ardmplkh', 'component' => \Diepxuan\Catalog\Http\Livewire\AR\Danhmuc\Phanloaikhachhang::class],
            ['module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso1', 'routeName' => 'so.vch.sovchso1', 'url' => '/_simba-source/so/vch/sovchso1', 'component' => \Diepxuan\Catalog\Http\Livewire\Banhang\Hoadonbanhang::class],
        ];
    }
}
