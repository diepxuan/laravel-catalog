<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\So\Rpt\Arrptbccn01;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

final class Arrptbccn01Test extends TestCase
{
    public function testDisplayValueNormalizesScalarValuesForReportTable(): void
    {
        $component = new Arrptbccn01();

        self::assertSame('', $component->displayValue(null));
        self::assertSame('1', $component->displayValue(true));
        self::assertSame('0', $component->displayValue(false));
        self::assertSame('1250.5', $component->displayValue(1250.5));
        self::assertSame('', $component->displayValue(['unexpected']));
    }

    public function testNumericColumnDetectionMatchesReportTableAlignment(): void
    {
        $component = new Arrptbccn01();

        self::assertTrue($component->isNumericColumn('ps_no'));
        self::assertTrue($component->isNumericColumn('du_ck'));
        self::assertTrue($component->isNumericColumn('t_tt_nt'));
        self::assertTrue($component->isNumericColumn('tien_fm'));

        self::assertFalse($component->isNumericColumn('ma_nt'));
        self::assertFalse($component->isNumericColumn('ma_kh'));
        self::assertFalse($component->isNumericColumn('tk'));
        self::assertFalse($component->isNumericColumn('dien_giai'));
    }

    public function testSelectRowStoresDetailPayload(): void
    {
        $component = new Arrptbccn01();
        $component->rows = [
            ['ma_kh' => 'KH001', 'ps_no' => 1500],
            ['ma_kh' => 'KH002', 'ps_no' => 2500],
        ];

        $component->selectRow(1);

        self::assertSame(1, $component->selectedRowIndex);
        self::assertSame(['ma_kh' => 'KH002', 'ps_no' => 2500], $component->selectedRow);

        $component->selectRow(99);

        self::assertNull($component->selectedRowIndex);
        self::assertSame([], $component->selectedRow);
    }

    public function testExportCsvRequiresLoadedRows(): void
    {
        $component = new Arrptbccn01();

        self::assertNull($component->exportCsv());
        self::assertSame('Chưa có dữ liệu để xuất.', $component->errorMessage);
    }

    public function testExportCsvReturnsStreamedDownloadForLoadedRows(): void
    {
        $component = new Arrptbccn01();
        $component->columns = ['ma_kh', 'ps_no'];
        $component->rows = [['ma_kh' => 'KH001', 'ps_no' => 1500]];

        $response = $component->exportCsv();

        self::assertInstanceOf(StreamedResponse::class, $response);
        self::assertStringContainsString('arrptbccn01-', (string) $response->headers->get('content-disposition'));
    }
}
