<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-06 23:34:00
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Simba\Models\SiDmCt;
use Diepxuan\Simba\SModel\SModel;

final class SimbaVoucherMetadata
{
    /**
     * @param list<string>                         $codes
     * @param array<string, array<string, string>> $overrides
     *
     * @return array<string, array<string, string>>
     */
    public static function forCodes(array $codes, array $overrides = []): array
    {
        $normalizedCodes = array_values(array_unique(array_map('strtoupper', $codes)));
        $rows = SiDmCt::query()
            ->where('ma_cty', self::companyCode())
            ->whereIn('ma_ct', $normalizedCodes)
            ->get()
            ->keyBy(static fn (SiDmCt $voucher): string => strtoupper((string) $voucher->ma_ct))
        ;

        $metadata = [];
        foreach ($normalizedCodes as $code) {
            $voucher = $rows->get($code);
            $override = $overrides[$code] ?? [];

            $metadata[$code] = array_filter([
                'title'       => (string) ($voucher?->ten_ct ?: ($override['title'] ?? $code)),
                'route'       => $override['route'] ?? '',
                'menuid'      => (string) ($override['menuid'] ?? $voucher?->menuid ?? ''),
                'sys_menuid'  => $override['sys_menuid'] ?? null,
                'ph'          => (string) ($voucher?->ph ?? ''),
                'ct'          => (string) ($voucher?->ct ?? ''),
                'cp'          => $override['cp'] ?? null,
                'table_ph'    => $override['table_ph'] ?? self::tableName((string) ($voucher?->ph ?? '')),
                'table_ct'    => $override['table_ct'] ?? self::tableName((string) ($voucher?->ct ?? '')),
                'model'       => $override['model'] ?? null,
                'description' => $override['description'] ?? (string) ($voucher?->ten_ct ?: ''),
            ], static fn (?string $value): bool => null !== $value);
        }

        return $metadata;
    }

    private static function tableName(string $source): string
    {
        return '' === trim($source) ? '' : strtoupper($source);
    }

    private static function companyCode(): string
    {
        if (class_exists(\CatalogService::class) && method_exists(\CatalogService::class, 'company')) {
            return (string) (\CatalogService::company()?->ma_cty ?? SModel::CTY);
        }

        return SModel::CTY;
    }
}
