<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-30 20:14:14
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
    public const DATASET_SI_DM_CT                   = 'si_dm_ct';
    public const DATASET_SYS_MENU                   = 'sys_menu';
    public const DATASET_SYS_DICTIONARY_INFO        = 'sys_dictionary_info';
    public const DATASET_SYS_REPORT_DRILL_DOWN_INFO = 'sys_report_drill_down_info';
    public const DATASET_SYS_REPORT_INFO            = 'sys_report_info';

    /** @var array<string, Collection|EloquentCollection> */
    private array $loaded = [];

    /** @var array<string, array<string, mixed>> */
    private array $indexes = [];

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
            foreach (array_keys($this->indexes) as $key) {
                if (str_starts_with($key, $dataset . ':')) {
                    unset($this->indexes[$key]);
                }
            }

            return;
        }

        $this->loaded  = [];
        $this->indexes = [];
    }

    /**
     * @return array<string, mixed>
     */
    public function indexBy(string $dataset, string $field): array
    {
        $this->assertKnownDataset($dataset);
        $key = $dataset . ':' . $field;

        if (isset($this->indexes[$key])) {
            return $this->indexes[$key];
        }

        $index = [];
        foreach ($this->get($dataset) as $row) {
            $value = trim((string) data_get($row, $field));
            if ('' !== $value && !isset($index[$value])) {
                $index[$value] = $row;
            }
        }

        return $this->indexes[$key] = $index;
    }

    public function refresh(?string $dataset = null): array|Collection|EloquentCollection
    {
        $this->forget($dataset);

        if (null !== $dataset) {
            return $this->get($dataset);
        }

        return $this->warm();
    }

    protected function load(string $dataset): Collection|EloquentCollection
    {
        return match ($dataset) {
            self::DATASET_SI_DM_CT            => SiDmCt::query()->get(),
            self::DATASET_SYS_MENU            => $this->mergedMenus(),
            self::DATASET_SYS_DICTIONARY_INFO => SysDictionaryInfo::query()
                ->select(['menuid', 'code_name', 'table_name', 'PK', 'carry_field_list'])
                ->toBase()
                ->get(),
            self::DATASET_SYS_REPORT_DRILL_DOWN_INFO => SysReportDrillDownInfo::query()
                ->select(['menuid', 'press_key_name'])
                ->toBase()
                ->get(),
            self::DATASET_SYS_REPORT_INFO => $this->mergedReportInfo(),
        };
    }

    /**
     * @return Collection<int, SysMenu>
     */
    protected function mergedMenus(): Collection
    {
        return SysMenu::query()
            ->select($this->menuColumns())
            // ->where('active', '1')
            ->get()
            ->merge(Zsysmenu::query()
                ->select($this->menuColumns())
                // ->where('active', '1')
                ->get())
            ->filter(static fn (SysMenu $menu): bool => '' !== trim((string) $menu->menuid))
            ->unique(static fn (SysMenu $menu): string => (string) $menu->menuid)
            ->values()
        ;
    }

    /**
     * @return Collection<int, object>
     */
    protected function mergedReportInfo(): Collection
    {
        return SysReportInfo::query()
            ->select(['menuid', 'ma_mau', 'spname', 'rptname'])
            ->toBase()
            ->get()
            ->merge(ZSysReportInfo::query()->select(['menuid', 'ma_mau', 'spname', 'rptname'])->toBase()->get())
            ->filter(static fn (object $report): bool => '' !== trim((string) $report->menuid))
            ->values()
        ;
    }

    protected function assertKnownDataset(string $dataset): void
    {
        if (!\in_array($dataset, $this->datasets(), true)) {
            throw new \InvalidArgumentException("Unknown Simba metadata dataset [{$dataset}].");
        }
    }

    /**
     * @return list<string>
     */
    private function menuColumns(): array
    {
        return [
            'menuid',
            'stt',
            'type',
            'moduleid',
            'bar',
            'short_name',
            'dllName',
            'command',
            'code_name',
            'report',
            'active',
        ];
    }
}
