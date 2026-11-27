<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\ReportColumnLabels;
use PHPUnit\Framework\TestCase;

/**
 * Test parse dinh dang cot cua SimbaERP trong sysReportResx.
 *
 * Dinh dang that (menu 10.20.02, mau 04, vi-VN):
 *   ngay_ct:H=Ngay CT:F=dd/MM/yyyy:W=75,so_ct:H=So CT:W=62,...
 *
 * Test thuan logic, KHONG ket noi DB.
 */
class ReportColumnLabelsTest extends TestCase
{
    public function testParseTraVeLabelTheoDinhDangSimbaErp(): void
    {
        $labels = ReportColumnLabels::parse(
            'ngay_ct:H=Ngày CT:F=dd/MM/yyyy:W=75,so_ct:H=Số CT:W=62,ma_ct:H=Mã CT:W=64'
        );

        self::assertSame('Ngày CT', $labels['ngay_ct']);
        self::assertSame('Số CT', $labels['so_ct']);
        self::assertSame('Mã CT', $labels['ma_ct']);
    }

    public function testParseBoQuaPhanTuRong(): void
    {
        $labels = ReportColumnLabels::parse('so_ct:H=Số CT,,ma_ct:H=Mã CT,');

        self::assertCount(2, $labels);
        self::assertArrayHasKey('so_ct', $labels);
        self::assertArrayHasKey('ma_ct', $labels);
    }

    public function testParseChiuDuocCotThieuHeader(): void
    {
        // "ma_lo" khong co H= -> khong tao label, khong loi
        $labels = ReportColumnLabels::parse('ma_lo,so_ct:H=Số CT');

        self::assertSame(['so_ct' => 'Số CT'], $labels);
    }

    public function testParseChuoiRongTraVeMangRong(): void
    {
        self::assertSame([], ReportColumnLabels::parse(null));
        self::assertSame([], ReportColumnLabels::parse(''));
        self::assertSame([], ReportColumnLabels::parse('   '));
    }

    public function testParseChuanHoaTenCotVeChuThuong(): void
    {
        // SimbaERP co cho ghi 'Ngay_ct', 'Ma_vt' -> phai ve chu thuong
        $labels = ReportColumnLabels::parse('Ngay_ct:H=Ngày CT,Ma_vt:H=Mã VT');

        self::assertArrayHasKey('ngay_ct', $labels);
        self::assertArrayHasKey('ma_vt', $labels);
    }

    public function testParseColumnsGiuThuTuVaChiLayTenCot(): void
    {
        $columns = ReportColumnLabels::parseColumns(
            'ngay_ct:H=Ngày CT:F=dd/MM/yyyy:W=75,so_ct:H=Số CT:W=62,ma_ct:H=Mã CT'
        );

        self::assertSame(['ngay_ct', 'so_ct', 'ma_ct'], $columns);
    }

    public function testParseColumnsChuoiRongTraVeMangRong(): void
    {
        self::assertSame([], ReportColumnLabels::parseColumns(null));
        self::assertSame([], ReportColumnLabels::parseColumns(''));
    }

    public function testForKhongCoMenuIdTraVeMangRong(): void
    {
        self::assertSame([], ReportColumnLabels::for(null));
        self::assertSame([], ReportColumnLabels::for(''));
        self::assertSame([], ReportColumnLabels::for('   '));
    }

    public function testVisibleColumnsKhongCoMenuIdTraVeMangRong(): void
    {
        self::assertSame([], ReportColumnLabels::visibleColumns(null));
        self::assertSame([], ReportColumnLabels::visibleColumns(''));
    }
}
