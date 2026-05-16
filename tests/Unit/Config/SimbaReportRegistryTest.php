<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:15
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\SimbaReportRegistry;
use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class SimbaReportRegistryTest extends TestCase
{
    public function testReportRegistryMatchesSysReportInfo(): void
    {
        $data    = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $reports = collect();
        foreach ($data->table('sysReportInfo.md')->merge($data->table('zSysReportInfo.md')) as $row) {
            $reports->put($row['menuid'] . '|' . $row['ma_mau'], $reports->get($row['menuid'] . '|' . $row['ma_mau'], $row));
        }

        foreach (SimbaReportRegistry::reports() as $route => $metadata) {
            if ('sysReportDrillDownInfo' === ($metadata['source_table'] ?? '')) {
                continue;
            }

            $key = $metadata['menuid'] . '|' . $metadata['ma_mau'];

            self::assertTrue($reports->has($key), "Missing report info for route {$route}");
            $source = $reports->get($key);

            self::assertSame($metadata['spname'], $source['spname'], "Wrong spname for route {$route}");
            self::assertSame($metadata['rptname'], $source['rptname'], "Wrong rptname for route {$route}");
        }
    }

    public function testGeneratedReportsAreBoundToReportMenus(): void
    {
        $menus = (new SimbaDocsMenuRepository(\dirname(__DIR__, 5)))->activeMenus()
            ->mapWithKeys(static fn ($menu): array => [$menu->menuid => $menu])
        ;

        foreach (SimbaReportRegistry::reports() as $route => $metadata) {
            $menu = $menus->get($metadata['menuid']);

            self::assertNotNull($menu, "Missing report menu for route {$route}");
            if ('sysReportDrillDownInfo' === ($metadata['source_table'] ?? '')) {
                self::assertNotSame('', trim((string) $menu->dllName), "Drilldown report route {$route} is missing DLL anchor");

                continue;
            }

            self::assertTrue($menu->isReport() || $menu->isMasterData() || '1' === (string) $menu->report, "Report route {$route} is not bound to a report/list menu");
        }
    }

    public function testGeneratedDrilldownReportsUseSysReportDrillDownInfo(): void
    {
        $data = new SimbaDocsDataRepository(\dirname(__DIR__, 5));

        $drilldownTargets = [];
        foreach ($data->table('sysReportDrillDownInfo.md') as $row) {
            foreach (['drilldown_menuid', 'drilldown_menuid1', 'drilldown_menuid2', 'drilldown_menuid3', 'drilldown_menuid4'] as $field) {
                $menuid = trim((string) ($row[$field] ?? ''));
                if ('' === $menuid) {
                    continue;
                }

                $drilldownTargets[$menuid] = true;
            }
        }

        self::assertNotEmpty($drilldownTargets);

        $checked = 0;
        foreach (SimbaReportRegistry::reports() as $route => $metadata) {
            if ('sysReportDrillDownInfo' !== ($metadata['source_table'] ?? '')) {
                continue;
            }

            self::assertArrayHasKey($metadata['menuid'], $drilldownTargets, "Missing drilldown metadata for route {$route}");
            self::assertNotSame('', $metadata['dll_name'], "Missing drilldown DLL for route {$route}");
            ++$checked;
        }
    }

    public function testReportFlagMenusWithReportInfoUseReportRoutes(): void
    {
        $data  = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $menus = (new SimbaDocsMenuRepository(\dirname(__DIR__, 5)))->activeMenus()
            ->filter(static fn ($menu): bool => '1' === (string) $menu->report)
            ->mapWithKeys(static fn ($menu): array => [$menu->menuid => $menu])
        ;

        $reportInfoMenuIds = $data->table('sysReportInfo.md')
            ->merge($data->table('zSysReportInfo.md'))
            ->pluck('menuid')
            ->filter()
            ->unique()
            ->values()
        ;

        $reportRoutesByMenu = [];
        foreach (SimbaReportRegistry::reports() as $route => $metadata) {
            $reportRoutesByMenu[$metadata['menuid']][] = $route;
        }

        $checked = 0;
        foreach ($reportInfoMenuIds as $menuid) {
            if (!$menus->has($menuid)) {
                continue;
            }

            self::assertArrayHasKey($menuid, $reportRoutesByMenu, "Missing report-flag route for {$menuid}");
            ++$checked;
        }

        self::assertGreaterThan(0, $checked);
    }
}
