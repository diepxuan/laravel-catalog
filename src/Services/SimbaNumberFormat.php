<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Models\Simba\SiSetup;

/**
 * Dinh dang so theo cau hinh SimbaERP.
 *
 * NGUON SU THAT:
 *   - Ma format trong sysReportResx (.formated_col_list F=...) anh xa sang
 *     cot cau hinh trong bang SiSetup (theo Framework.dll, class frmReport):
 *
 *       case "QT": val.DefaultCellStyle.Format = SiSetup.Qty_Input_Format;
 *       case "OA": val.DefaultCellStyle.Format = SiSetup.Amount_Input_Format;
 *       case "FP": val.DefaultCellStyle.Format = SiSetup.Amount_Input_Format;
 *       case "FA": val.DefaultCellStyle.Format = SiSetup.FCAmount_Input_Format;
 *       case "OP": val.DefaultCellStyle.Format = SiSetup.Price_Input_Format;
 *
 *   - Gia tri thuc te trong DB (cty 001, kiem 2026-11-27):
 *       Qty_Input_Format       = '### ### ###.##'
 *       Price_Input_Format     = '### ### ### ###'
 *       Amount_Input_Format    = '### ### ### ### ###'
 *       FCAmount_Input_Format  = '### ### ### ##0.#0'
 *
 * Dinh dang .NET: '#' = chu so hoac bo trong, '0' = chu so bat buoc,
 * '.' = dau thap phan, ' ' trong phan nguyen = dau ngan cach nghin
 * (theo culture vi-VN: '.' ngan cach nghin, ',' thap phan — nhung SimbaERP
 * dung '.' lam dau thap phan trong pattern nen ta format theo pattern roi
 * doi dau khi hien thi).
 */
class SimbaNumberFormat
{
    /** Cache cau hinh trong 1 request. */
    protected static ?array $config = null;

    /**
     * Lay cau hinh format tu SiSetup (cache).
     *
     * @return array<string, string>
     */
    public static function config(): array
    {
        if (null !== self::$config) {
            return self::$config;
        }

        $defaults = [
            'QT' => '### ### ###.##',
            'OA' => '### ### ### ### ###',
            'FP' => '### ### ### ### ###',
            'FA' => '### ### ### ##0.#0',
            'OP' => '### ### ### ###',
        ];

        try {
            $setup = SiSetup::query()->first();
        } catch (\Throwable) {
            return self::$config = $defaults;
        }

        if (null === $setup) {
            return self::$config = $defaults;
        }

        return self::$config = [
            'QT' => self::pick($setup->Qty_Input_Format, $defaults['QT']),
            'OA' => self::pick($setup->Amount_Input_Format, $defaults['OA']),
            'FP' => self::pick($setup->Amount_Input_Format, $defaults['FP']),
            'FA' => self::pick($setup->FCAmount_Input_Format, $defaults['FA']),
            'OP' => self::pick($setup->Price_Input_Format, $defaults['OP']),
        ];
    }

    /**
     * Format 1 gia tri so theo ma format SimbaERP.
     *
     * @param mixed  $value  gia tri goc (string tu SQL Server, int, float)
     * @param null|string $code ma format: QT/OA/FP/FA/OP, hoac pattern .NET truc tiep
     */
    public static function format(mixed $value, ?string $code): string
    {
        if (null === $value || '' === $value || '' === trim((string) $value)) {
            return '';
        }

        if (!is_numeric($value)) {
            return (string) $value;
        }

        $code = null === $code ? '' : trim($code);
        $config = self::config();

        // Ma 2 ky tu -> tra cuu cau hinh; con lai coi la pattern .NET truc tiep
        $pattern = $config[mb_strtoupper($code)] ?? ($code !== '' ? $code : $config['OA']);

        return self::applyPattern((float) $value, $pattern);
    }

    /**
     * Ap dung pattern .NET (### ### ###.##) len so.
     *
     * Quy tac:
     *   - phan nguyen: nhom 3 chu so bang dau cach
     *   - phan thap phan: theo so luong '#'/'0' sau dau '.'
     *   - '0' o cuoi -> luon hien (ke ca 0)
     *   - '#' o cuoi -> bo so 0 thua
     */
    public static function applyPattern(float $number, string $pattern): string
    {
        $pattern = trim($pattern);

        if ('' === $pattern) {
            return (string) $number;
        }

        $negative = $number < 0;
        $number = abs($number);

        // Tach phan pattern theo dau '.' dau tien
        $dotPos = mb_strpos($pattern, '.');
        $integerPattern = false === $dotPos ? $pattern : mb_substr($pattern, 0, $dotPos);
        $decimalPattern = false === $dotPos ? '' : mb_substr($pattern, $dotPos + 1);

        // So chu so thap phan = so ky tu '#' hoac '0' trong pattern thap phan
        $decimals = mb_strlen(preg_replace('/[^#0]/', '', $decimalPattern) ?? '');
        $minDecimals = mb_strlen(preg_replace('/[^0]/', '', $decimalPattern) ?? '');

        $rounded = round($number, $decimals);
        $formatted = number_format($rounded, $decimals, '.', ' ');

        // number_format luon giu du so thap phan -> cat bot so 0 thua neu pattern cho phep
        if ($decimals > $minDecimals && mb_strpos($formatted, '.') !== false) {
            [$int, $dec] = explode('.', $formatted, 2);
            $dec = rtrim($dec, '0');

            while (mb_strlen($dec) < $minDecimals) {
                $dec .= '0';
            }

            $formatted = '' === $dec ? $int : $int . '.' . $dec;
        }

        return ($negative ? '-' : '') . $formatted;
    }

    /**
     * Dinh dang ngay theo pattern .NET (dd/MM/yyyy).
     */
    public static function formatDate(mixed $value, string $pattern = 'dd/MM/yyyy'): string
    {
        if (null === $value || '' === trim((string) $value)) {
            return '';
        }

        try {
            $date = $value instanceof \DateTimeInterface
                ? $value
                : new \DateTimeImmutable((string) $value);
        } catch (\Throwable) {
            return (string) $value;
        }

        // .NET -> PHP date format
        $php = strtr($pattern, [
            'dd' => 'd',
            'MM' => 'm',
            'yyyy' => 'Y',
            'HH' => 'H',
            'hh' => 'h',
            'mm' => 'i',
            'ss' => 's',
        ]);

        return $date->format($php);
    }

    protected static function pick(mixed $value, string $default): string
    {
        $value = null === $value ? '' : trim((string) $value);

        return '' === $value ? $default : $value;
    }
}
