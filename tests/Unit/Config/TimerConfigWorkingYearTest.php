<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\TimerConfig;
use Illuminate\Support\Facades\Session;

/**
 * Pin behavior: predefined period (thang/quy/nua nam/ca nam) luon tinh lai
 * theo nam lam viec hien tai (session('year')); chi custom mode giu cache.
 *
 * @coversDefaultClass \Diepxuan\Catalog\Config\TimerConfig
 */
final class TimerConfigWorkingYearTest extends \Tests\TestCase
{
    /**
     * @covers ::timer
     */
    public function testPredefinedPeriodRecalculatesWhenWorkingYearChanges(): void
    {
        Session::start();

        // Luu timer thang 8 theo nam mac dinh (nam he thong)
        TimerConfig::timer(['id' => 't08']);

        // Sep doi nam lam viec sang 2025 qua header
        session(['year' => 2025]);

        $timer = TimerConfig::timer();

        self::assertSame('t08', $timer['id']);
        self::assertSame('2025-08-01', $timer['from']);
        self::assertSame('2025-08-31', $timer['to']);
    }

    /**
     * @covers ::timer
     */
    public function testYearPeriodFollowsWorkingYear(): void
    {
        Session::start();

        session(['year' => 2025]);
        TimerConfig::timer(['id' => 'y']);

        // Doi nam lam viec -> range phai doi theo
        session(['year' => 2026]);

        $timer = TimerConfig::timer();

        self::assertSame('y', $timer['id']);
        self::assertSame('2026-01-01', $timer['from']);
        self::assertSame('2026-12-31', $timer['to']);
    }

    /**
     * @covers ::timer
     */
    public function testCustomModeKeepsUserRangeAfterYearChange(): void
    {
        Session::start();

        TimerConfig::timer(['id' => 'c', 'from' => '2026-03-01', 'to' => '2026-03-31']);

        session(['year' => 2025]);

        $timer = TimerConfig::timer();

        self::assertSame('c', $timer['id']);
        self::assertSame('2026-03-01', $timer['from']);
        self::assertSame('2026-03-31', $timer['to']);
    }
}
