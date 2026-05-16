<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:46
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Simba\Models\PoPh0;
use Diepxuan\Simba\Models\PoPh1;
use Diepxuan\Simba\Models\PoPh2;
use Diepxuan\Simba\Models\PoPh3;
use Diepxuan\Simba\Models\PoPh4;
use Diepxuan\Simba\Models\PoPh5;
use Diepxuan\Simba\Models\PoPh6;
use Diepxuan\Simba\Models\PoPh8;

final class PoVoucherRegistry
{
    /**
     * PO voucher metadata verified from simba-docs/data/SiDmCt.md.
     *
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return [
            'PO0' => [
                'title'       => 'Phiếu đề nghị mua hàng',
                'route'       => 'muahang.po0',
                'menuid'      => '10.10.01',
                'ph'          => 'poPh0',
                'ct'          => 'poCt0',
                'model'       => PoPh0::class,
                'description' => 'Phiếu đề nghị mua hàng',
            ],
            'PO1' => [
                'title'       => 'Đơn hàng mua',
                'route'       => 'muahang.po1',
                'menuid'      => '10.10.02',
                'sys_menuid'  => '10.10.05',
                'ph'          => 'poPh1',
                'ct'          => 'poCt1',
                'model'       => PoPh1::class,
                'description' => 'Đơn hàng mua',
            ],
            'PO2' => [
                'title'       => 'Phiếu nhập mua',
                'route'       => 'muahang.po2',
                'menuid'      => '10.10.04',
                'ph'          => 'poPh2',
                'ct'          => 'poCt2',
                'model'       => PoPh2::class,
                'description' => 'Phiếu nhập mua',
            ],
            'PO3' => [
                'title'       => 'Hoá đơn mua hàng trong nước',
                'route'       => 'muahang.hoadonmua',
                'menuid'      => '10.10.14',
                'ph'          => 'poPh3',
                'ct'          => 'poCt3',
                'cp'          => 'poCp3',
                'model'       => PoPh3::class,
                'description' => 'Hoá đơn mua hàng trong nước',
            ],
            'PO4' => [
                'title'       => 'Phiếu chi phí mua hàng',
                'route'       => 'muahang.po4',
                'menuid'      => '10.10.20',
                'ph'          => 'poPh4',
                'ct'          => 'poCt4',
                'cp'          => 'poCp4',
                'model'       => PoPh4::class,
                'description' => 'Phiếu chi phí mua hàng',
            ],
            'PO5' => [
                'title'       => 'Phiếu xuất trả lại nhà cung cấp',
                'route'       => 'muahang.po5',
                'menuid'      => '10.10.23',
                'ph'          => 'poPh5',
                'ct'          => 'poCt5',
                'model'       => PoPh5::class,
                'description' => 'Phiếu xuất trả lại nhà cung cấp',
            ],
            'PO6' => [
                'title'       => 'Hoá đơn mua dịch vụ',
                'route'       => 'muahang.po6',
                'menuid'      => '10.10.26',
                'ph'          => 'poPh6',
                'ct'          => 'poCt6',
                'model'       => PoPh6::class,
                'description' => 'Hoá đơn mua dịch vụ',
            ],
            'PO7' => [
                'title'       => 'Hoá đơn mua hàng nhập khẩu',
                'route'       => 'muahang.po7',
                'menuid'      => '10.10.17',
                'ph'          => 'poPh3',
                'ct'          => 'poCt3',
                'cp'          => 'poCp3',
                'model'       => PoPh3::class,
                'description' => 'Hoá đơn mua hàng nhập khẩu',
            ],
            'PO8' => [
                'title'       => 'Phiếu nhập mua xuất thẳng',
                'route'       => 'muahang.po8',
                'menuid'      => '10.10.05',
                'ph'          => 'poPh3',
                'ct'          => 'poCt3',
                'cp'          => 'poCp8',
                'model'       => PoPh8::class,
                'description' => 'Phiếu nhập mua xuất thẳng',
            ],
        ];
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $voucherCode): ?array
    {
        return self::vouchers()[strtoupper($voucherCode)] ?? null;
    }
}
