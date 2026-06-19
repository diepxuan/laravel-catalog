<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class SourceRouteCoverageTest extends TestCase
{
    public function testComponentBackedSourceRoutesExist(): void
    {
        foreach ($this->componentBackedSourceRoutes() as $route) {
            self::assertTrue(Route::has($route['routeName']), "Missing route name {$route['routeName']}");
            self::assertSame(
                ltrim($route['url'], '/'),
                Route::getRoutes()->getByName($route['routeName'])?->uri(),
                "Route URI mismatch for {$route['routeName']}"
            );
        }
    }

    public function testComponentBackedSourceRouteUrlsResolveToExpectedRouteNames(): void
    {
        foreach ($this->componentBackedSourceRoutes() as $route) {
            $resolved = Route::getRoutes()->match(request()->create($route['url'], 'GET'));

            self::assertSame($route['routeName'], $resolved->getName(), "Route resolve mismatch for {$route['url']}");
            self::assertSame($route['module'], $resolved->defaults['module'] ?? null);
            self::assertSame($route['kind'], $resolved->defaults['kind'] ?? null);
            self::assertSame($route['slug'], $resolved->defaults['slug'] ?? null);
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

    public function testLegacyCreateEditUrlsStillResolve(): void
    {
        foreach ([
            '/cash/nhanvien/create',
            '/cash/nhanvien/edit/1',
            '/muahang/nhacungcap/create',
            '/muahang/nhacungcap/edit/1',
        ] as $url) {
            $resolved = Route::getRoutes()->match(request()->create($url, 'GET'));

            self::assertNotNull($resolved->getName(), "Legacy URL {$url} did not resolve to a named route");
        }
    }

    /**
     * @return list<array{module:string,kind:string,slug:string,routeName:string,url:string}>
     */
    private function componentBackedSourceRoutes(): array
    {
        return [
            ['module' => 'ca', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'ca.dict.ardmkh', 'url' => '/_simba-source/ca/dict/ardmkh'],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca1', 'routeName' => 'ca.vch.cavchca1', 'url' => '/_simba-source/ca/vch/cavchca1'],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca2', 'routeName' => 'ca.vch.cavchca2', 'url' => '/_simba-source/ca/vch/cavchca2'],
            ['module' => 'ca', 'kind' => 'vch', 'slug' => 'cavchca4', 'routeName' => 'ca.vch.cavchca4', 'url' => '/_simba-source/ca/vch/cavchca4'],
            ['module' => 'gl', 'kind' => 'dict', 'slug' => 'gldmtk', 'routeName' => 'gl.dict.gldmtk', 'url' => '/_simba-source/gl/dict/gldmtk'],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmkho', 'routeName' => 'in.dict.indmkho', 'url' => '/_simba-source/in/dict/indmkho'],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmnhvt', 'routeName' => 'in.dict.indmnhvt', 'url' => '/_simba-source/in/dict/indmnhvt'],
            ['module' => 'in', 'kind' => 'dict', 'slug' => 'indmvt', 'routeName' => 'in.dict.indmvt', 'url' => '/_simba-source/in/dict/indmvt'],
            ['module' => 'po', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'po.dict.ardmkh', 'url' => '/_simba-source/po/dict/ardmkh'],
            ['module' => 'po', 'kind' => 'dict', 'slug' => 'podmcp', 'routeName' => 'po.dict.podmcp', 'url' => '/_simba-source/po/dict/podmcp'],
            ['module' => 'so', 'kind' => 'dict', 'slug' => 'ardmkh', 'routeName' => 'so.dict.ardmkh', 'url' => '/_simba-source/so/dict/ardmkh'],
            ['module' => 'so', 'kind' => 'dict', 'slug' => 'ardmplkh', 'routeName' => 'so.dict.ardmplkh', 'url' => '/_simba-source/so/dict/ardmplkh'],
            ['module' => 'so', 'kind' => 'vch', 'slug' => 'sovchso1', 'routeName' => 'so.vch.sovchso1', 'url' => '/_simba-source/so/vch/sovchso1'],
        ];
    }
}
