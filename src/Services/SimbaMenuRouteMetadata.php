<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-18 20:10:00
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Simba\Models\SysDictionaryInfo;
use Diepxuan\Simba\Models\SysMenu;
use Diepxuan\Simba\Models\SysReportDrillDownInfo;
use Diepxuan\Simba\Models\SysReportInfo;
use Diepxuan\Simba\Models\ZSysReportInfo;

final class SimbaMenuRouteMetadata
{
    public const TYPE_DICTIONARY = 'dictionary';
    public const TYPE_VOUCHER    = 'voucher';
    public const TYPE_REPORT     = 'report';
    public const TYPE_CUSTOM     = 'custom';

    private ?array $routes = null;
    private ?array $dictionaries = null;
    private ?array $reports = null;
    private ?array $processes = null;

    /**
     * @param null|iterable<int, SysMenu> $testMenus
     */
    public function __construct(private readonly ?SimbaMenuRepository $menus = null, private readonly ?iterable $testMenus = null)
    {
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function routes(): array
    {
        if (null !== $this->routes) {
            return $this->routes;
        }

        $routes = [];
        foreach ($this->activeLeafMenus() as $menu) {
            $sourceType = $this->sourceTypeFor($menu);
            if (null === $sourceType) {
                continue;
            }

            $routeName = $this->routeNameFor($menu, $sourceType, $routes);
            $routes[$routeName] = $this->metadataFor($menu, $sourceType);
        }

        return $this->routes = $routes;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function routesWithoutProcesses(): array
    {
        return array_filter(
            $this->routes(),
            static fn (array $metadata): bool => SimbaMenuRouteMetadata::TYPE_CUSTOM !== ($metadata['source_type'] ?? null)
        );
    }

    /**
     * @return null|array<string, mixed>
     */
    public function getRoute(string $routeName): ?array
    {
        return $this->routes()[$routeName] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function dictionaries(): array
    {
        if (null !== $this->dictionaries) {
            return $this->dictionaries;
        }

        return $this->dictionaries = array_filter(
            $this->routes(),
            static fn (array $metadata): bool => SimbaMenuRouteMetadata::TYPE_DICTIONARY === ($metadata['source_type'] ?? null)
        );
    }

    /**
     * @return null|array<string, mixed>
     */
    public function getDictionary(string $routeName): ?array
    {
        return $this->dictionaries()[$routeName] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function reports(): array
    {
        if (null !== $this->reports) {
            return $this->reports;
        }

        return $this->reports = array_filter(
            $this->routes(),
            static fn (array $metadata): bool => SimbaMenuRouteMetadata::TYPE_REPORT === ($metadata['source_type'] ?? null)
        );
    }

    /**
     * @return null|array<string, mixed>
     */
    public function getReport(string $routeName): ?array
    {
        return $this->reports()[$routeName] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function processes(): array
    {
        if (null !== $this->processes) {
            return $this->processes;
        }

        return $this->processes = array_filter(
            $this->routes(),
            static fn (array $metadata): bool => SimbaMenuRouteMetadata::TYPE_CUSTOM === ($metadata['source_type'] ?? null)
        );
    }

    /**
     * @return null|array<string, mixed>
     */
    public function getProcess(string $routeName): ?array
    {
        return $this->processes()[$routeName] ?? null;
    }

    /**
     * @return iterable<int, SysMenu>
     */
    private function activeLeafMenus(): iterable
    {
        if (null !== $this->testMenus) {
            return $this->testMenus;
        }

        return ($this->menus ?? new SimbaMenuRepository())->activeMenus();
    }

    private function sourceTypeFor(SysMenu $menu): ?string
    {
        $type = (string) $menu->type;
        if (SysMenu::TYPE_MODULE_ROOT === $type) {
            return null;
        }

        if (SysMenu::TYPE_MASTER === $type) {
            return SimbaMenuRouteMetadata::TYPE_DICTIONARY;
        }

        if (SysMenu::TYPE_VOUCHER === $type) {
            return SimbaMenuRouteMetadata::TYPE_VOUCHER;
        }

        if (SysMenu::TYPE_REPORT === $type || (bool) $menu->report) {
            if ($this->hasCallableMetadata($menu) || null !== $this->reportRow($menu)) {
                return SimbaMenuRouteMetadata::TYPE_REPORT;
            }

            return null;
        }

        if ($this->hasCallableMetadata($menu)) {
            return SimbaMenuRouteMetadata::TYPE_CUSTOM;
        }

        return null;
    }

    /**
     * @param array<string, array<string, mixed>> $existing
     */
    private function routeNameFor(SysMenu $menu, string $sourceType, array $existing): string
    {
        $module = strtolower((string) $menu->moduleid);
        $kind = match ($sourceType) {
            SimbaMenuRouteMetadata::TYPE_DICTIONARY => 'dict',
            SimbaMenuRouteMetadata::TYPE_VOUCHER => 'vch',
            SimbaMenuRouteMetadata::TYPE_REPORT => 'rpt',
            default => 'proc',
        };
        $source = (string) ($menu->dllName ?: $menu->code_name ?: $menu->command ?: $menu->menuid);
        $slug = $this->slug($source) ?: $this->slug((string) $menu->menuid);
        $route = "{$module}.{$kind}.{$slug}";

        if (!isset($existing[$route])) {
            return $route;
        }

        return "{$route}-{$this->slug((string) $menu->menuid)}";
    }

    /**
     * @return array<string, mixed>
     */
    private function metadataFor(SysMenu $menu, string $sourceType): array
    {
        $base = $this->baseMetadata($menu, $sourceType);

        return match ($sourceType) {
            SimbaMenuRouteMetadata::TYPE_DICTIONARY => array_merge($base, $this->dictionaryMetadata($menu)),
            SimbaMenuRouteMetadata::TYPE_REPORT => array_merge($base, $this->reportMetadata($menu)),
            SimbaMenuRouteMetadata::TYPE_VOUCHER => array_merge($base, $this->voucherMetadata($menu)),
            SimbaMenuRouteMetadata::TYPE_CUSTOM => array_merge($base, $this->processMetadata($menu)),
            default => $base,
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function baseMetadata(SysMenu $menu, string $sourceType): array
    {
        return [
            'module'      => (string) $menu->moduleid,
            'menuid'      => (string) $menu->menuid,
            'source_type' => $sourceType,
            'title'       => $menu->getDisplayName(),
            'type'        => (string) $menu->type,
            'dll_name'    => (string) $menu->dllName,
            'command'     => (string) $menu->command,
            'code_name'   => (string) $menu->code_name,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function dictionaryMetadata(SysMenu $menu): array
    {
        $row = $this->dictionaryRow($menu);
        if (null === $row) {
            return ['metadata_missing' => 'dictionary_info'];
        }

        return [
            'code_name'     => (string) ($row->code_name ?: $menu->code_name),
            'table'         => (string) $row->table_name,
            'pk'            => $this->splitCsv((string) $row->PK),
            'fields'        => $this->splitCsv(strtolower((string) $row->carry_field_list)),
            'source_menuid' => (string) $row->menuid,
        ];
    }

    private function dictionaryRow(SysMenu $menu): ?SysDictionaryInfo
    {
        if (null !== $this->testMenus) {
            return null;
        }

        $menuid = trim((string) $menu->menuid);
        $codeName = trim((string) $menu->code_name);
        $dllName = trim((string) $menu->dllName);

        $query = SysDictionaryInfo::query();
        $row = $query->where('menuid', $menuid)->first();
        if (null !== $row) {
            return $row;
        }

        if ('' !== $codeName) {
            $row = SysDictionaryInfo::query()->where('code_name', $codeName)->first();
            if (null !== $row) {
                return $row;
            }
        }

        if ('' !== $dllName) {
            $row = SysDictionaryInfo::query()->where('table_name', $dllName)->first();
            if (null !== $row) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function reportMetadata(SysMenu $menu): array
    {
        $row = $this->reportRow($menu);
        if (null === $row) {
            return ['metadata_missing' => 'report_info'];
        }

        $metadata = [
            'ma_mau' => (string) $row->ma_mau,
            'spname' => (string) $row->spname,
            'rptname' => (string) $row->rptname,
        ];

        $drilldown = SysReportDrillDownInfo::query()
            ->where('menuid', (string) $menu->menuid)
            ->first()
        ;
        if (null !== $drilldown) {
            $metadata['press_key_name'] = (string) $drilldown->press_key_name;
        }

        return $metadata;
    }

    private function reportRow(SysMenu $menu): mixed
    {
        if (null !== $this->testMenus) {
            return null;
        }

        $menuid = (string) $menu->menuid;
        $row = SysReportInfo::query()->where('menuid', $menuid)->first();
        if (null !== $row) {
            return $row;
        }

        return ZSysReportInfo::query()->where('menuid', $menuid)->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function voucherMetadata(SysMenu $menu): array
    {
        $codeName = trim((string) $menu->code_name);
        if ('' === $codeName) {
            return ['metadata_missing' => 'voucher_code'];
        }

        return ['ma_ct' => $codeName];
    }

    /**
     * @return array<string, mixed>
     */
    private function processMetadata(SysMenu $menu): array
    {
        return [
            'name' => $menu->getDisplayName(),
        ];
    }

    private function hasCallableMetadata(SysMenu $menu): bool
    {
        return '' !== trim((string) $menu->dllName)
            || '' !== trim((string) $menu->command)
            || '' !== trim((string) $menu->code_name);
    }

    /**
     * @return list<string>
     */
    private function splitCsv(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    private function slug(string $value): string
    {
        $slug = strtolower((string) preg_replace('/[^A-Za-z0-9]+/', '-', $value));

        return trim($slug, '-');
    }
}
