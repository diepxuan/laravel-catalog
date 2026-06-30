<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\SimbaMetadataService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class SimbaMetadataServiceTest extends TestCase
{
    public function testDatasetsIncludeSimbaMetadataSources(): void
    {
        $service = new SimbaMetadataService();

        self::assertSame([
            SimbaMetadataService::DATASET_SI_DM_CT,
            SimbaMetadataService::DATASET_SYS_MENU,
            SimbaMetadataService::DATASET_SYS_DICTIONARY_INFO,
            SimbaMetadataService::DATASET_SYS_REPORT_DRILL_DOWN_INFO,
            SimbaMetadataService::DATASET_SYS_REPORT_INFO,
        ], $service->datasets());
    }

    public function testUnknownDatasetIsRejected(): void
    {
        $service = new SimbaMetadataService();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown Simba metadata dataset [unknown].');

        $service->get('unknown');
    }

    public function testIndexByCachesRowsAndKeepsFirstDuplicateKey(): void
    {
        $service = new class extends SimbaMetadataService {
            public int $loads = 0;

            protected function load(string $dataset): Collection
            {
                ++$this->loads;

                return collect([
                    (object) ['menuid' => '90.10.02', 'code_name' => 'SIDMCT'],
                    (object) ['menuid' => '90.10.02', 'code_name' => 'SIDMCT_DUP'],
                    (object) ['menuid' => '  ', 'code_name' => 'EMPTY'],
                ]);
            }
        };

        $index = $service->indexBy(SimbaMetadataService::DATASET_SYS_DICTIONARY_INFO, 'menuid');
        $again = $service->indexBy(SimbaMetadataService::DATASET_SYS_DICTIONARY_INFO, 'menuid');

        self::assertSame(1, $service->loads);
        self::assertSame($index, $again);
        self::assertSame('SIDMCT', $index['90.10.02']->code_name);
        self::assertArrayNotHasKey('', $index);
    }

    public function testForgetDatasetClearsRelatedIndexes(): void
    {
        $service = new class extends SimbaMetadataService {
            public int $loads = 0;

            protected function load(string $dataset): Collection
            {
                ++$this->loads;

                return collect([(object) ['menuid' => '90.10.02']]);
            }
        };

        $service->indexBy(SimbaMetadataService::DATASET_SYS_DICTIONARY_INFO, 'menuid');
        $service->forget(SimbaMetadataService::DATASET_SYS_DICTIONARY_INFO);
        $service->indexBy(SimbaMetadataService::DATASET_SYS_DICTIONARY_INFO, 'menuid');

        self::assertSame(2, $service->loads);
    }

    public function testReportMetadataIndexPrefersSysReportInfoOverZSysReportInfoForSameMenuId(): void
    {
        $service = new class extends SimbaMetadataService {
            protected function mergedReportInfo(): Collection
            {
                return collect([
                    (object) ['menuid' => '02.10.02', 'ma_mau' => '01', 'spname' => 'asSysReport', 'rptname' => 'SYS.RPT'],
                    (object) ['menuid' => '02.10.02', 'ma_mau' => '99', 'spname' => 'asZReport', 'rptname' => 'Z.RPT'],
                ]);
            }
        };

        $index = $service->indexBy(SimbaMetadataService::DATASET_SYS_REPORT_INFO, 'menuid');

        self::assertSame('asSysReport', $index['02.10.02']->spname);
        self::assertSame('SYS.RPT', $index['02.10.02']->rptname);
    }
}
