<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Models\Simba\sysReportResx;

/**
 * Doc nhan (label) cot bao cao tu bang sysReportResx.
 *
 * NGUON SU THAT:
 *   sysReportResx.formated_col_list / ct_formated_col_list
 *   (language = 'vi-VN') la dinh nghia cot CHINH THUC cua SimbaERP,
 *   gom: ten cot : H=<header> : F=<format> : W=<width>
 *
 * Vi du (menu 10.20.02, mau 04):
 *   ngay_ct:H=Ngay CT:F=dd/MM/yyyy:W=75,so_ct:H=So CT:W=62,...
 *
 * KHONG tu dat label. Neu khong tim thay -> tra ve ten cot tho
 * (de nguoi dung thay ro du lieu goc thay vi nhan sai).
 */
class ReportColumnLabels
{
    /** Cache theo "{menuid}|{ma_mau}" trong 1 request. */
    protected static array $cache = [];

    /**
     * Lay map [ten_cot => label] cho bao cao.
     *
     * @return array<string, string>
     */
    public static function for(?string $menuId, ?string $maMau = null, string $language = 'vi-VN'): array
    {
        if (null === $menuId || '' === trim($menuId)) {
            return [];
        }

        $key = $menuId . '|' . ($maMau ?? '') . '|' . $language;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        return self::$cache[$key] = self::load($menuId, $maMau, $language);
    }

    /**
     * Parse chuoi formated_col_list thanh map label.
     *
     * Dinh dang moi phan tu: "ten_cot:H=Header:F=Format:W=Width"
     * Cac truong H/F/W deu optional.
     *
     * @return array<string, string>
     */
    public static function parse(?string $formatedColList): array
    {
        if (null === $formatedColList || '' === trim($formatedColList)) {
            return [];
        }

        $labels = [];

        foreach (explode(',', $formatedColList) as $segment) {
            $segment = trim($segment);

            if ('' === $segment) {
                continue;
            }

            $parts = explode(':', $segment);
            $column = trim(array_shift($parts) ?? '');

            if ('' === $column) {
                continue;
            }

            $label = '';

            foreach ($parts as $part) {
                if (str_starts_with($part, 'H=')) {
                    $label = trim(substr($part, 2));
                    break;
                }
            }

            if ('' !== $label) {
                $labels[mb_strtolower($column)] = $label;
            }
        }

        return $labels;
    }

    /**
     * Danh sach cot CAN HIEN cho bao cao, theo dung thu tu trong
     * sysReportResx.formated_col_list (dinh nghia chinh thuc SimbaERP).
     *
     * Tra [] neu khong co dinh nghia -> noi goi giu nguyen toan bo cot
     * (khong tu y an bot du lieu).
     *
     * @return list<string>
     */
    public static function visibleColumns(?string $menuId, ?string $maMau = null, string $language = 'vi-VN'): array
    {
        if (null === $menuId || '' === trim($menuId)) {
            return [];
        }

        $key = '__cols__|' . $menuId . '|' . ($maMau ?? '') . '|' . $language;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $columns = [];

        foreach (self::candidateRows($menuId, $maMau, $language) as $row) {
            $parsed = self::parseColumns(self::rowValue($row, 'formated_col_list'));

            if ([] !== $parsed) {
                $columns = $parsed;
                break;
            }
        }

        return self::$cache[$key] = $columns;
    }

    /**
     * Map label cho bang CHI TIET (ct_formated_col_list).
     *
     * SimbaERP tach 2 danh sach cot:
     *   formated_col_list    -> bang master (danh sach phieu)
     *   ct_formated_col_list -> bang chi tiet
     *
     * @return array<string, string>
     */
    public static function forDetail(?string $menuId, ?string $maMau = null, string $language = 'vi-VN'): array
    {
        if (null === $menuId || '' === trim($menuId)) {
            return [];
        }

        $key = '__detail__|' . $menuId . '|' . ($maMau ?? '') . '|' . $language;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        foreach (self::candidateRows($menuId, $maMau, $language) as $row) {
            $labels = self::parse(self::rowValue($row, 'ct_formated_col_list'));

            if ([] !== $labels) {
                return self::$cache[$key] = $labels;
            }
        }

        return self::$cache[$key] = [];
    }

    /**
     * Danh sach cot cua bang CHI TIET theo ct_formated_col_list.
     *
     * @return list<string>
     */
    public static function visibleDetailColumns(?string $menuId, ?string $maMau = null, string $language = 'vi-VN'): array
    {
        if (null === $menuId || '' === trim($menuId)) {
            return [];
        }

        $key = '__detailcols__|' . $menuId . '|' . ($maMau ?? '') . '|' . $language;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        foreach (self::candidateRows($menuId, $maMau, $language) as $row) {
            $columns = self::parseColumns(self::rowValue($row, 'ct_formated_col_list'));

            if ([] !== $columns) {
                return self::$cache[$key] = $columns;
            }
        }

        return self::$cache[$key] = [];
    }

    /**
     * Map [ten_cot => ma format] (F=...) tu formated_col_list.
     *
     * Ma format SimbaERP: QT (so luong), OA/FP (tien), FA (tien ngoai te),
     * OP (don gia), dd/MM/yyyy (ngay), hoac pattern .NET truc tiep.
     *
     * @return array<string, string>
     */
    public static function formats(?string $menuId, ?string $maMau = null, string $language = 'vi-VN'): array
    {
        return self::formatMap($menuId, $maMau, $language, 'formated_col_list', '__fmt__');
    }

    /**
     * Map [ten_cot => ma format] cua bang CHI TIET (ct_formated_col_list).
     *
     * @return array<string, string>
     */
    public static function detailFormats(?string $menuId, ?string $maMau = null, string $language = 'vi-VN'): array
    {
        return self::formatMap($menuId, $maMau, $language, 'ct_formated_col_list', '__fmtct__');
    }

    /**
     * Parse 1 chuoi formated_col_list -> [cot => F=...].
     *
     * @return array<string, string>
     */
    public static function parseFormats(?string $formatedColList): array
    {
        if (null === $formatedColList || '' === trim($formatedColList)) {
            return [];
        }

        $formats = [];

        foreach (explode(',', $formatedColList) as $segment) {
            $segment = trim($segment);

            if ('' === $segment) {
                continue;
            }

            $parts = explode(':', $segment);
            $column = trim(array_shift($parts) ?? '');

            if ('' === $column) {
                continue;
            }

            foreach ($parts as $part) {
                if (str_starts_with($part, 'F=')) {
                    $format = trim(substr($part, 2));

                    if ('' !== $format) {
                        $formats[mb_strtolower($column)] = $format;
                    }

                    break;
                }
            }
        }

        return $formats;
    }

    /**
     * @param string $field cot Resx chua danh sach cot
     *
     * @return array<string, string>
     */
    protected static function formatMap(?string $menuId, ?string $maMau, string $language, string $field, string $prefix): array
    {
        if (null === $menuId || '' === trim($menuId)) {
            return [];
        }

        $key = $prefix . '|' . $menuId . '|' . ($maMau ?? '') . '|' . $language;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        foreach (self::candidateRows($menuId, $maMau, $language) as $row) {
            $formats = self::parseFormats(self::rowValue($row, $field));

            if ([] !== $formats) {
                return self::$cache[$key] = $formats;
            }
        }

        return self::$cache[$key] = [];
    }

    /**
     * Parse formated_col_list -> list ten cot (giu thu tu).
     *
     * @return list<string>
     */
    public static function parseColumns(?string $formatedColList): array
    {
        if (null === $formatedColList || '' === trim($formatedColList)) {
            return [];
        }

        $columns = [];

        foreach (explode(',', $formatedColList) as $segment) {
            $segment = trim($segment);

            if ('' === $segment) {
                continue;
            }

            $column = trim(explode(':', $segment)[0] ?? '');

            if ('' !== $column) {
                $columns[] = mb_strtolower($column);
            }
        }

        return $columns;
    }

    /**
     * Map label TOAN CUC: gop formated_col_list + ct_formated_col_list cua
     * moi bao cao trong sysReportResx.
     *
     * Dung lam fallback cho cac cot khong nam trong dinh nghia cua bao cao
     * hien tai (vd SP tra 40 cot nhung formated_col_list chi dinh nghia 14).
     *
     * @return array<string, string>
     */
    public static function allLabels(string $language = 'vi-VN'): array
    {
        $key = '__all__|' . $language;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $map = [];

        try {
            $rows = sysReportResx::query()->where('language', $language)->get();
        } catch (\Throwable) {
            return self::$cache[$key] = [];
        }

        foreach ($rows as $row) {
            foreach (['formated_col_list', 'ct_formated_col_list', 'formated_col_list_nt', 'ct_formated_col_list_nt'] as $field) {
                foreach (self::parse(self::rowValue($row, $field)) as $column => $label) {
                    // Giu label dau tien gap: dinh nghia cua bao cao dung ma_mau
                    // thuong chinh xac hon; o day chi can 1 label hop ly.
                    if (!isset($map[$column])) {
                        $map[$column] = $label;
                    }
                }
            }
        }

        return self::$cache[$key] = $map;
    }

    /**
     * @return array<string, string>
     */
    protected static function load(string $menuId, ?string $maMau, string $language): array
    {
        foreach (self::candidateRows($menuId, $maMau, $language) as $row) {
            $labels = self::parse(self::rowValue($row, 'formated_col_list'));

            if ([] !== $labels) {
                return $labels;
            }
        }

        return [];
    }

    /**
     * Thu tu uu tien: dung ma_mau + vi-VN -> dung ma_mau bat ky -> moi ma_mau.
     *
     * @return iterable<object|array<string, mixed>>
     */
    protected static function candidateRows(string $menuId, ?string $maMau, string $language): iterable
    {
        $base = sysReportResx::query()->where('menuid', $menuId);

        if (null !== $maMau && '' !== trim($maMau)) {
            $rows = (clone $base)->where('ma_mau', $maMau)->where('language', $language)->get();

            if ($rows->isNotEmpty()) {
                yield from $rows;
            }

            $rows = (clone $base)->where('ma_mau', $maMau)->get();

            if ($rows->isNotEmpty()) {
                yield from $rows;
            }
        }

        $rows = (clone $base)->where('language', $language)->get();

        if ($rows->isNotEmpty()) {
            yield from $rows;
        }

        yield from (clone $base)->get();
    }

    /**
     * @param object|array<string, mixed> $row
     */
    protected static function rowValue(object|array $row, string $key): ?string
    {
        $value = \is_array($row) ? ($row[$key] ?? null) : ($row->{$key} ?? null);

        return null === $value ? null : (string) $value;
    }
}
