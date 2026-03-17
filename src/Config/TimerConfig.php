<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-17 11:43:45
 */

namespace Diepxuan\Catalog\Config;

use Illuminate\Support\Carbon;

/**
 * Timer configuration utility class.
 *
 * Defines time period identifiers for report filtering:
 * - t01-t12: Tháng 01 đến Tháng 12
 * - q1-q4: Quý 1 đến Quý 4
 * - h1: 6 tháng đầu năm (T1-T6)
 * - h2: 6 tháng cuối năm (T7-T12)
 * - y: Cả năm
 * - c: Custom (từ ... đến ...)
 */
class TimerConfig
{
    /** Time period prefixes */
    public const PREFIX_MONTH     = 't';
    public const PREFIX_QUARTER   = 'q';
    public const PREFIX_HALF_YEAR = 'h';
    public const PREFIX_YEAR      = 'y';
    public const PREFIX_CUSTOM    = 'c';

    /** Valid time IDs */
    public const TIME_MONTHS     = ['t01', 't02', 't03', 't04', 't05', 't06', 't07', 't08', 't09', 't10', 't11', 't12'];
    public const TIME_QUARTERS   = ['q1', 'q2', 'q3', 'q4'];
    public const TIME_HALF_YEARS = ['h1', 'h2'];
    public const TIME_YEAR       = 'y';
    public const TIME_CUSTOM     = 'c';

    /**
     * Get/set timer settings.
     *
     * @param null|array{id?: string, from?: string, to?: string} $time Timer settings (null = read only)
     *
     * @return array{id: string, from: string, to: string}
     */
    public static function timer(?array $time = null): array
    {
        // Read mode: không có params
        if (null === $time) {
            $timeId = session('timeId', self::getDefaultTimeId());
            $from   = session('timeStart');
            $to     = session('timeEnd');

            // Nếu chưa có session, tính toán mặc định
            if (!$from || !$to) {
                $result = self::calculateTimeRange($timeId);
                $from   = $result['from'];
                $to     = $result['to'];
            }

            return [
                'id'   => $timeId,
                'from' => $from->toDateString(),
                'to'   => $to->toDateString(),
            ];
        }

        // Write mode: có params
        $timeId = $time['id'] ?? session('timeId', self::getDefaultTimeId());
        $from   = $time['from'] ?? null;
        $to     = $time['to'] ?? null;

        // Tính toán date range dựa trên timeId (trừ khi là custom)
        if (self::isCustom($timeId)) {
            // Custom mode: sử dụng from/to được cung cấp
            $from = $from ? Carbon::parse($from) : now()->startOfMonth();
            $to   = $to ? Carbon::parse($to) : now()->endOfMonth();
        } else {
            // Predefined mode: tính toán từ timeId, ignore from/to
            $result = self::calculateTimeRange($timeId);
            $from   = $result['from'];
            $to     = $result['to'];
        }

        // Lưu session
        session([
            'timeId'    => $timeId,
            'timeStart' => $from,
            'timeEnd'   => $to,
        ]);

        return [
            'id'   => $timeId,
            'from' => $from->toDateString(),
            'to'   => $to->toDateString(),
        ];
    }

    /**
     * Check if a timeId is valid.
     *
     * @param string $timeId Time period identifier
     *
     * @return bool True if valid, false otherwise
     */
    public static function isValid(string $timeId): bool
    {
        return \array_key_exists($timeId, self::options());
    }

    /**
     * Get all available timer options with labels.
     *
     * @return array<string, string> Key-value pairs of timeId => label
     */
    public static function options(): array
    {
        $months = collect(range(1, 12))
            ->mapWithKeys(static function ($month) {
                $key   = self::PREFIX_MONTH . str_pad("{$month}", 2, '0', STR_PAD_LEFT);
                $label = 'Tháng ' . str_pad("{$month}", 2, '0', STR_PAD_LEFT);

                return [$key => $label];
            })
            ->toArray()
        ;

        $quarters = collect(range(1, 4))
            ->mapWithKeys(static fn ($quarter) => [
                self::PREFIX_QUARTER . $quarter => "Quý {$quarter}",
            ])
            ->toArray()
        ;

        $halfYears = [
            'h1' => '6 tháng đầu năm',
            'h2' => '6 tháng cuối năm',
        ];

        $year = [
            'y' => 'Cả năm',
        ];

        $custom = [
            'c' => 'Từ ... đến ...',
        ];

        return [
            ...$months,
            ...$quarters,
            ...$halfYears,
            ...$year,
            ...$custom,
        ];
    }

    /**
     * Check if timeId is a month period (t01-t12).
     *
     * @param string $timeId Time period identifier
     *
     * @return bool True if month period
     */
    public static function isMonth(string $timeId): bool
    {
        return \in_array($timeId, self::TIME_MONTHS, true);
    }

    /**
     * Check if timeId is a quarter period (q1-q4).
     *
     * @param string $timeId Time period identifier
     *
     * @return bool True if quarter period
     */
    public static function isQuarter(string $timeId): bool
    {
        return \in_array($timeId, self::TIME_QUARTERS, true);
    }

    /**
     * Check if timeId is a half-year period (h1-h2).
     *
     * @param string $timeId Time period identifier
     *
     * @return bool True if half-year period
     */
    public static function isHalfYear(string $timeId): bool
    {
        return \in_array($timeId, self::TIME_HALF_YEARS, true);
    }

    /**
     * Check if timeId is year period (y).
     *
     * @param string $timeId Time period identifier
     *
     * @return bool True if year period
     */
    public static function isYear(string $timeId): bool
    {
        return self::TIME_YEAR === $timeId;
    }

    /**
     * Check if timeId is custom period (c).
     *
     * @param string $timeId Time period identifier
     *
     * @return bool True if custom period
     */
    public static function isCustom(string $timeId): bool
    {
        return self::TIME_CUSTOM === $timeId;
    }

    /**
     * Extract month number from month timeId.
     *
     * @param string $timeId Time period identifier (e.g., 't03')
     *
     * @return null|int Month number (1-12) or null if not a month
     */
    public static function getMonthFromTimeId(string $timeId): ?int
    {
        if (!self::isMonth($timeId)) {
            return null;
        }

        return (int) substr($timeId, 1);
    }

    /**
     * Extract quarter number from quarter timeId.
     *
     * @param string $timeId Time period identifier (e.g., 'q2')
     *
     * @return null|int Quarter number (1-4) or null if not a quarter
     */
    public static function getQuarterFromTimeId(string $timeId): ?int
    {
        if (!self::isQuarter($timeId)) {
            return null;
        }

        return (int) substr($timeId, 1);
    }

    /**
     * Get default timeId (current month).
     */
    public static function getDefaultTimeId(): string
    {
        return 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate date range for a given timeId.
     *
     * @param string $timeId Time period identifier
     *
     * @return array{from: Carbon, to: Carbon}
     */
    public static function calculateTimeRange(string $timeId): array
    {
        $year = \CatalogService::year();
        $now  = now();

        // Month (t01-t12)
        if (self::isMonth($timeId)) {
            $month = self::getMonthFromTimeId($timeId);
            $from  = (clone $now)->setYear($year)->setMonth($month)->startOfMonth();
            $to    = (clone $from)->endOfMonth();
        }
        // Quarter (q1-q4)
        elseif (self::isQuarter($timeId)) {
            $quarter = self::getQuarterFromTimeId($timeId);
            $from    = (clone $now)->setYear($year)->setMonth(($quarter - 1) * 3 + 1)->startOfQuarter();
            $to      = (clone $from)->endOfQuarter();
        }
        // Half year (h1, h2)
        elseif (self::isHalfYear($timeId)) {
            if ('h1' === $timeId) {
                $from = (clone $now)->setYear($year)->setMonth(1)->startOfMonth();
                $to   = (clone $now)->setYear($year)->setMonth(6)->endOfMonth();
            } else { // h2
                $from = (clone $now)->setYear($year)->setMonth(7)->startOfMonth();
                $to   = (clone $now)->setYear($year)->setMonth(12)->endOfMonth();
            }
        }
        // Year (y)
        elseif (self::isYear($timeId)) {
            $from = (clone $now)->setYear($year)->startOfYear();
            $to   = (clone $now)->setYear($year)->endOfYear();
        }
        // Custom (c) - default to current month
        elseif (self::isCustom($timeId)) {
            $from = (clone $now)->setYear($year)->startOfMonth();
            $to   = (clone $from)->endOfMonth();
        }
        // Invalid timeId - fallback to current month
        else {
            $from   = (clone $now)->setYear($year)->startOfMonth();
            $to     = (clone $from)->endOfMonth();
            $timeId = 't' . str_pad("{$now->month}", 2, '0', STR_PAD_LEFT);
        }

        return [
            'from' => $from,
            'to'   => $to,
        ];
    }
}
