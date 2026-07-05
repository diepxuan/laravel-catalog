<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\In\Rpt\Inrptcd02;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

final class Inrptcd02Test extends TestCase
{
    public function testDisplayValueNormalizesScalarValuesForReportTable(): void
    {
        $component = new Inrptcd02();

        self::assertSame('', $component->displayValue(null));
        self::assertSame('1', $component->displayValue(true));
        self::assertSame('0', $component->displayValue(false));
        self::assertSame('1250.5', $component->displayValue(1250.5));
        self::assertSame('', $component->displayValue(['unexpected']));
    }

    public function testNumericColumnDetectionMatchesInventoryReportAlignment(): void
    {
        $component = new Inrptcd02();

        self::assertTrue($component->isNumericColumn('sl_ton'));
        self::assertTrue($component->isNumericColumn('t_tt_nt'));
        self::assertTrue($component->isNumericColumn('tien_fm'));

        self::assertFalse($component->isNumericColumn('ma_vt'));
        self::assertFalse($component->isNumericColumn('ten_vt'));
        self::assertFalse($component->isNumericColumn('tk_vt'));
        self::assertFalse($component->isNumericColumn('dvt'));
    }

    public function testSelectRowStoresDetailPayload(): void
    {
        $component = new Inrptcd02();
        $component->rows = [
            ['ma_vt' => 'VT001', 'sl_ton' => 15],
            ['ma_vt' => 'VT002', 'sl_ton' => 25],
        ];

        $component->selectRow(1);

        self::assertSame(1, $component->selectedRowIndex);
        self::assertSame(['ma_vt' => 'VT002', 'sl_ton' => 25], $component->selectedRow);

        $component->selectRow(99);

        self::assertNull($component->selectedRowIndex);
        self::assertSame([], $component->selectedRow);
    }

    public function testExportCsvRequiresLoadedRows(): void
    {
        $component = new Inrptcd02();

        self::assertNull($component->exportCsv());
        self::assertSame('Chưa có dữ liệu để xuất.', $component->errorMessage);
    }

    public function testExportCsvReturnsStreamedDownloadForLoadedRows(): void
    {
        $component = new Inrptcd02();
        $component->columns = ['ma_vt', 'sl_ton'];
        $component->rows = [['ma_vt' => 'VT001', 'sl_ton' => 15]];

        $response = $component->exportCsv();

        self::assertInstanceOf(StreamedResponse::class, $response);
        self::assertStringContainsString('inrptcd02-', (string) $response->headers->get('content-disposition'));
    }
}
