<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\SimbaNumberFormat;
use PHPUnit\Framework\TestCase;

/**
 * Test dinh dang so theo cau hinh SimbaERP.
 *
 * Pattern lay tu SiSetup (cty 001):
 *   Qty_Input_Format      = '### ### ###.##'
 *   Price_Input_Format    = '### ### ### ###'
 *   Amount_Input_Format   = '### ### ### ### ###'
 *   FCAmount_Input_Format = '### ### ### ##0.#0'
 *
 * Test thuan logic, KHONG ket noi DB.
 */
class SimbaNumberFormatTest extends TestCase
{
    public function testSoLuongBoSoKhongThua(): void
    {
        self::assertSame('3', SimbaNumberFormat::applyPattern(3.0, '### ### ###.##'));
        self::assertSame('2', SimbaNumberFormat::applyPattern(2.0, '### ### ###.##'));
    }

    public function testSoLuongGiuPhanThapPhanKhiCo(): void
    {
        self::assertSame('3.5', SimbaNumberFormat::applyPattern(3.5, '### ### ###.##'));
        self::assertSame('3.25', SimbaNumberFormat::applyPattern(3.25, '### ### ###.##'));
    }

    public function testTienNganCachBangDauCach(): void
    {
        // SimbaERP dung dau CACH ngan cach nghin, khong phai dau cham
        self::assertSame('4 400 000', SimbaNumberFormat::applyPattern(4_400_000.0, '### ### ### ### ###'));
        self::assertSame('1 500 000', SimbaNumberFormat::applyPattern(1_500_000.0, '### ### ### ###'));
    }

    public function testTienLamTronKhongThapPhan(): void
    {
        self::assertSame('1 234 568', SimbaNumberFormat::applyPattern(1_234_567.89, '### ### ### ### ###'));
    }

    public function testNgoaiTeGiuHaiSoThapPhan(): void
    {
        // FCAmount_Input_Format = '### ### ### ##0.#0' -> toi da 2 so, toi thieu 1
        // (.NET: '#0' = 1 chu so bat buoc -> luon hien it nhat 1 so thap phan)
        self::assertSame('1 234 567.89', SimbaNumberFormat::applyPattern(1_234_567.89, '### ### ### ##0.#0'));
        self::assertSame('1 234 567.0', SimbaNumberFormat::applyPattern(1_234_567.0, '### ### ### ##0.#0'));
    }

    public function testSoKhongVaSoAm(): void
    {
        self::assertSame('0', SimbaNumberFormat::applyPattern(0.0, '### ### ###.##'));
        self::assertSame('-4 400 000', SimbaNumberFormat::applyPattern(-4_400_000.0, '### ### ### ### ###'));
    }

    public function testFormatTraVeRongVoiGiaTriRong(): void
    {
        self::assertSame('', SimbaNumberFormat::format(null, 'OA'));
        self::assertSame('', SimbaNumberFormat::format('', 'OA'));
        self::assertSame('', SimbaNumberFormat::format('   ', 'OA'));
    }

    public function testFormatGiaTriKhongPhaiSoTraNguyenBan(): void
    {
        self::assertSame('HDMH00115', SimbaNumberFormat::format('HDMH00115', 'OA'));
    }

    public function testFormatTheoMaSimbaErp(): void
    {
        // QT = so luong, OA/FP = tien, OP = don gia
        self::assertSame('3', SimbaNumberFormat::format('3.0000', 'QT'));
        self::assertSame('4 400 000', SimbaNumberFormat::format('4400000.0000', 'OA'));
        self::assertSame('1 500 000', SimbaNumberFormat::format('1500000.0000', 'OP'));
        self::assertSame('0', SimbaNumberFormat::format('.0000', 'OA'));
    }

    public function testFormatNgayTheoDdMmYyyy(): void
    {
        self::assertSame('03/01/2025', SimbaNumberFormat::formatDate('2025-01-03 00:00:00'));
        self::assertSame('03/01/2025', SimbaNumberFormat::formatDate('2025-01-03'));
    }

    public function testFormatNgayRongTraRong(): void
    {
        self::assertSame('', SimbaNumberFormat::formatDate(null));
        self::assertSame('', SimbaNumberFormat::formatDate(''));
    }
}
