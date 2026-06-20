<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\SimbaMetadataService;
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
            SimbaMetadataService::DATASET_ZSYS_REPORT_INFO,
        ], $service->datasets());
    }

    public function testUnknownDatasetIsRejected(): void
    {
        $service = new SimbaMetadataService();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown Simba metadata dataset [unknown].');

        $service->get('unknown');
    }
}
