<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\Po\Rpt;

use Diepxuan\Catalog\Http\Livewire\Po\Rpt\Arrptbccn01Sl;
use PHPUnit\Framework\TestCase;

final class Arrptbccn01SlTest extends TestCase
{
    public function testReportLabelTranslatesSimbaSummaryTokens(): void
    {
        self::assertSame('Dư đầu kỳ', Arrptbccn01Sl::reportLabel('#ARRptBCCN_DDK'));
        self::assertSame('Tổng phát sinh trong kỳ', Arrptbccn01Sl::reportLabel('#ARRptBCCN_TPS'));
        self::assertSame('Dư cuối kỳ', Arrptbccn01Sl::reportLabel('#ARRptBCCN_DCK'));
    }

    public function testCsvValueUsesReportLabelTranslation(): void
    {
        self::assertSame('Dư đầu kỳ', Arrptbccn01Sl::csvValue('#ARRptBCCN_DDK'));
        self::assertSame('Diễn giải thường', Arrptbccn01Sl::csvValue('Diễn giải thường'));
    }

    public function testCsvPresentationFormatsDateDocumentAndVndAmounts(): void
    {
        // Dong tong hop TPS khong co ma_vt -> CSV khong prefix tab.
        $summaryRow = [
            'ngay_ct'   => '2026-01-09 00:00:00',
            'so_ct'     => 'PN001',
            'stt_rec'   => '001wPO3000000001',
            'dien_giai' => '#ARRptBCCN_TPS',
            'ps_no'     => '30000.0000',
            'ps_no_nt'  => '1.2500',
        ];

        self::assertSame('09/01/2026 PN001 001wPO3000000001', Arrptbccn01Sl::csvValueForColumn($summaryRow, 'document'));
        self::assertSame(
            'Tổng phát sinh trong kỳ',
            Arrptbccn01Sl::csvValueForColumn($summaryRow, 'dien_giai')
        );
        self::assertSame('30,000', Arrptbccn01Sl::csvValueForColumn($summaryRow, 'ps_no'));
    }

    public function testCsvPresentationForProductLineIndentsAndIncludesItemSummary(): void
    {
        $row = [
            'ngay_ct'   => '2026-01-09 00:00:00',
            'so_ct'     => 'PN001',
            'dien_giai' => 'Nhập hàng',
            'ma_vt'     => 'VT001',
            'ten_vt'    => 'Vật tư test',
            'dvt'       => 'Cái',
            'so_luong'  => '2.5000',
            'gia'       => '12000.0000',
            'tien'      => '30000.0000',
            'ps_no'     => '30000.0000',
        ];

        $dienGiai = Arrptbccn01Sl::csvValueForColumn($row, 'dien_giai');
        self::assertSame("\t", mb_substr($dienGiai, 0, 1));
        self::assertStringContainsString('Nhập hàng | VT001 - Vật tư test (Cái)', $dienGiai);
        self::assertSame('2.5', Arrptbccn01Sl::csvValueForColumn($row, 'so_luong'));
        self::assertSame('12,000', Arrptbccn01Sl::csvValueForColumn($row, 'gia'));
        self::assertSame('30,000', Arrptbccn01Sl::csvValueForColumn($row, 'tien'));
    }

    public function testCsvPresentationUsesForeignCurrencyColumnsWhenRequested(): void
    {
        $row = [
            'gia'      => '12000.0000',
            'gia_nt'   => '0.5000',
            'tien'     => '30000.0000',
            'tien_nt'  => '1.2500',
            'ps_co'    => '30000.0000',
            'ps_co_nt' => '1.2500',
        ];

        self::assertSame('0.5', Arrptbccn01Sl::csvValueForColumn($row, 'gia', true));
        self::assertSame('1.25', Arrptbccn01Sl::csvValueForColumn($row, 'tien', true));
        self::assertSame('1.25', Arrptbccn01Sl::csvValueForColumn($row, 'ps_co', true));
    }

    public function testPresentationColumnsHideRawColumnsAndKeepReadableGroups(): void
    {
        $component = new Arrptbccn01Sl();
        $component->rows = [[
            'ngay_ct'   => '2026-01-09 00:00:00',
            'so_ct'     => 'PN001',
            'stt_rec'   => '001wPO3000000001',
            'dien_giai' => 'Nhập hàng',
            'ma_vt'     => 'VT001',
            'ten_vt'    => 'Vật tư test',
            'dvt'       => 'Cái',
            'so_luong'  => '2.0000',
            'gia'       => '12000.0000',
            'tien'      => '24000.0000',
            'ps_no'     => '24000.0000',
            'ps_co'     => '0',
            'ma_cty'    => '001',
            'ma_kh'     => 'NCC01',
        ]];

        self::assertSame([
            'document',
            'dien_giai',
            'so_luong',
            'gia',
            'tien',
            'ps_no',
            'ps_co',
        ], array_column($component->presentationColumns(), 'key'));
    }
    public function testRowIsProductLineTrueWhenMaVtPresent(): void
    {
        $component = new Arrptbccn01Sl();
        $component->rows = [[
            'dien_giai' => 'Nhập hàng',
            'ma_vt'     => 'VT001',
        ]];

        self::assertTrue($component->rowIsProductLine($component->rows[0]));
    }

    public function testRowIsProductLineFalseWhenMaVtMissing(): void
    {
        $component = new Arrptbccn01Sl();
        $component->rows = [[
            'dien_giai' => 'Nhập hàng',
        ], [
            'dien_giai' => '#ARRptBCCN_TPS',
        ], [
            'dien_giai' => 'Nhập hàng',
            'ma_vt'     => '   ',
        ]];

        self::assertFalse($component->rowIsProductLine($component->rows[0]));
        self::assertFalse($component->rowIsProductLine($component->rows[1]));
        self::assertFalse($component->rowIsProductLine($component->rows[2]));
    }

    public function testRowLineClassIndentsProductLineAndHighlightsSummary(): void
    {
        $component = new Arrptbccn01Sl();

        $productRow = ['dien_giai' => 'Nhập hàng', 'ma_vt' => 'VT001'];
        $summaryRow = ['dien_giai' => '#ARRptBCCN_TPS', 'ma_vt' => null];
        $voucherRow = ['dien_giai' => 'Nhập hàng', 'ma_vt' => null];

        self::assertSame('ps-4', $component->rowLineClass($productRow));
        self::assertSame('', $component->rowLineClass($summaryRow));
        self::assertSame('', $component->rowLineClass($voucherRow));
    }

    public function testCsvDienGiaiPrefixesTabForProductLine(): void
    {
        $productRow = [
            'dien_giai' => 'Nhập hàng',
            'ma_vt'     => 'VT001',
            'ten_vt'    => 'Vật tư test',
            'dvt'       => 'Cái',
        ];
        $voucherRow = [
            'dien_giai' => 'Nhập hàng',
        ];

        self::assertSame("\t", mb_substr(Arrptbccn01Sl::csvValueForColumn($productRow, 'dien_giai'), 0, 1));
        self::assertStringNotContainsString("\t", Arrptbccn01Sl::csvValueForColumn($voucherRow, 'dien_giai'));
    }
    public function testMoneyColumnsRoundVndAndKeepFourDecimalsForForeignCurrency(): void
    {
        $row = [
            'gia'      => '12000.0000',
            'tien'     => '30000.0000',
            'ps_no'    => '30000.0000',
            'ps_co'    => '30000.0000',
            'gia_nt'   => '0.5000',
            'tien_nt'  => '1.2500',
            'ps_no_nt' => '1.2500',
            'ps_co_nt' => '1.2500',
        ];

        // VND: 0 chu so le.
        self::assertSame('12,000', Arrptbccn01Sl::csvValueForColumn($row, 'gia', false));
        self::assertSame('30,000', Arrptbccn01Sl::csvValueForColumn($row, 'tien', false));
        self::assertSame('30,000', Arrptbccn01Sl::csvValueForColumn($row, 'ps_no', false));
        self::assertSame('30,000', Arrptbccn01Sl::csvValueForColumn($row, 'ps_co', false));

        // Ngoai te: 4 chu so le.
        self::assertSame('0.5', Arrptbccn01Sl::csvValueForColumn($row, 'gia', true));
        self::assertSame('1.25', Arrptbccn01Sl::csvValueForColumn($row, 'tien', true));
        self::assertSame('1.25', Arrptbccn01Sl::csvValueForColumn($row, 'ps_no', true));
        self::assertSame('1.25', Arrptbccn01Sl::csvValueForColumn($row, 'ps_co', true));
    }
}
