<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-20 16:10:00
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Models\Simba\SysDictionaryInfo;
use Diepxuan\Catalog\Models\Simba\SysMenu;
use Diepxuan\Catalog\Models\Simba\Zsysmenu;
use Diepxuan\Simba\Models\SiDmCt;
use Diepxuan\Simba\Models\SysReportDrillDownInfo;
use Diepxuan\Simba\Models\SysReportInfo;
use Diepxuan\Simba\Models\ZSysReportInfo;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class SimbaMetadataService
{
    public const DATASET_SI_DM_CT = 'si_dm_ct';
    public const DATASET_SYS_MENU = 'sys_menu';
    public const DATASET_SYS_DICTIONARY_INFO = 'sys_dictionary_info';
    public const DATASET_SYS_REPORT_DRILL_DOWN_INFO = 'sys_report_drill_down_info';
    public const DATASET_SYS_REPORT_INFO = 'sys_report_info';

    /** @var array<string, Collection|EloquentCollection> */
    private array $loaded = [];

    /**
     * @return list<string>
     */
    public function datasets(): array
    {
        return [
            self::DATASET_SI_DM_CT,
            self::DATASET_SYS_MENU,
            self::DATASET_SYS_DICTIONARY_INFO,
            self::DATASET_SYS_REPORT_DRILL_DOWN_INFO,
            self::DATASET_SYS_REPORT_INFO,
        ];
    }

    /**
     * Load all Simba metadata datasets once for the current service instance.
     *
     * @return array<string, Collection|EloquentCollection>
     */
    public function warm(): array
    {
        $data = [];

        foreach ($this->datasets() as $dataset) {
            $data[$dataset] = $this->get($dataset);
        }

        return $data;
    }

    /**
     * Alias for warm(), useful when callers want all loaded datasets.
     *
     * @return array<string, Collection|EloquentCollection>
     */
    public function all(): array
    {
        return $this->warm();
    }

    /**
     * @return Collection|EloquentCollection
     */
    public function get(string $dataset): Collection|EloquentCollection
    {
        $this->assertKnownDataset($dataset);

        return $this->loaded[$dataset] ??= $this->load($dataset);
    }

    public function forget(?string $dataset = null): void
    {
        if (null !== $dataset) {
            $this->assertKnownDataset($dataset);
            unset($this->loaded[$dataset]);

            return;
        }

        $this->loaded = [];
    }

    public function refresh(?string $dataset = null): array|Collection|EloquentCollection
    {
        $this->forget($dataset);

        if (null !== $dataset) {
            return $this->get($dataset);
        }

        return $this->warm();
    }

    /**
     * @return Collection|EloquentCollection
     */
    protected function load(string $dataset): Collection|EloquentCollection
    {
        return match ($dataset) {
            self::DATASET_SI_DM_CT => SiDmCt::query()->get(),
            self::DATASET_SYS_MENU => $this->mergedMenus(),
            self::DATASET_SYS_DICTIONARY_INFO => SysDictionaryInfo::query()->get(),
            self::DATASET_SYS_REPORT_DRILL_DOWN_INFO => SysReportDrillDownInfo::query()->get(),
            self::DATASET_SYS_REPORT_INFO => $this->mergedReportInfo(),
        };
    }

    /**
     * @return Collection<int, SysMenu>
     */
    protected function mergedMenus(): Collection
    {
        return SysMenu::query()
            ->get()
            ->merge(Zsysmenu::query()->get())
            ->filter(static fn (SysMenu $menu): bool => '' !== trim((string) $menu->menuid))
            ->unique(static fn (SysMenu $menu): string => (string) $menu->menuid)
            ->values()
        ;
    }

    /**
     * @return Collection<int, SysReportInfo>
     */
    protected function mergedReportInfo(): Collection
    {
        return SysReportInfo::query()
            ->get()
            ->merge(ZSysReportInfo::query()->get())
            ->filter(static fn (SysReportInfo $report): bool => '' !== trim((string) $report->menuid))
            ->values()
        ;
    }

    protected function assertKnownDataset(string $dataset): void
    {
        if (!in_array($dataset, $this->datasets(), true)) {
            throw new \InvalidArgumentException("Unknown Simba metadata dataset [{$dataset}].");
        }
    }
}
