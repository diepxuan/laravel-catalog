<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-07-21
 */

namespace Diepxuan\Catalog\Http\Livewire\Component\Concerns;

/**
 * Loc dong `ksd` (khoa su dung) theo convention chung cua Portal.
 *
 * Simba luu `ksd = 0/1` hoac `'0'/'1'`, ky tu trang hoac null. Trait nay
 * chuan hoa: dong `true/1/'1'` (cac dang cua "bi khoa"), nguoc lai giu.
 *
 * Ap dung cho cac component autocomplete SP wrapper (Httt/Chiphi/Ngoaite...),
 * hoac bat ky cho can loai bo row inactive.
 */
trait HasKsdFilter
{
    /**
     * Kiem tra row co dang bi khoa hay khong (nguoc lai cua `isActiveRow`).
     */
    protected static function isKsdLocked(mixed $ksd): bool
    {
        if (is_string($ksd)) {
            $ksd = trim($ksd);
        }

        return in_array($ksd, [true, 1, '1'], true);
    }

    /**
     * Row dang su dung (khong bi khoa).
     */
    protected static function isActiveRow(array $row): bool
    {
        return !self::isKsdLocked($row['ksd'] ?? false);
    }
}
