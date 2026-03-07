<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-03-06
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\TimerConfig;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Diepxuan\Catalog\Config\TimerConfig
 */
class TimerConfigTest extends TestCase
{
    /**
     * @covers ::options
     */
    public function testOptionsReturnsAllPeriods(): void
    {
        $options = TimerConfig::options();

        // Check months (t01-t12)
        for ($month = 1; $month <= 12; $month++) {
            $key = 't' . str_pad("{$month}", 2, '0', STR_PAD_LEFT);
            $this->assertArrayHasKey($key, $options);
            $this->assertEquals("Tháng " . str_pad("{$month}", 2, '0', STR_PAD_LEFT), $options[$key]);
        }

        // Check quarters (q1-q4)
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $key = "q{$quarter}";
            $this->assertArrayHasKey($key, $options);
            $this->assertEquals("Quý {$quarter}", $options[$key]);
        }

        // Check half years
        $this->assertArrayHasKey('h1', $options);
        $this->assertEquals('6 tháng đầu năm', $options['h1']);
        $this->assertArrayHasKey('h2', $options);
        $this->assertEquals('6 tháng cuối năm', $options['h2']);

        // Check year
        $this->assertArrayHasKey('y', $options);
        $this->assertEquals('Cả năm', $options['y']);

        // Check custom
        $this->assertArrayHasKey('c', $options);
        $this->assertEquals('Từ ... đến ...', $options['c']);
    }

    /**
     * @covers ::isValid
     */
    public function testIsValidReturnsTrueForValidTimeIds(): void
    {
        // Months
        for ($month = 1; $month <= 12; $month++) {
            $key = 't' . str_pad("{$month}", 2, '0', STR_PAD_LEFT);
            $this->assertTrue(TimerConfig::isValid($key));
        }

        // Quarters
        foreach (['q1', 'q2', 'q3', 'q4'] as $key) {
            $this->assertTrue(TimerConfig::isValid($key));
        }

        // Half years
        $this->assertTrue(TimerConfig::isValid('h1'));
        $this->assertTrue(TimerConfig::isValid('h2'));

        // Year
        $this->assertTrue(TimerConfig::isValid('y'));

        // Custom
        $this->assertTrue(TimerConfig::isValid('c'));
    }

    /**
     * @covers ::isValid
     */
    public function testIsValidReturnsFalseForInvalidTimeIds(): void
    {
        $invalidIds = [
            't00',
            't13',
            'q0',
            'q5',
            'h0',
            'h3',
            'x',
            'invalid',
            '',
            't1', // Missing leading zero
        ];

        foreach ($invalidIds as $invalidId) {
            $this->assertFalse(TimerConfig::isValid($invalidId), "Failed for invalid ID: {$invalidId}");
        }
    }

    /**
     * @covers ::isMonth
     */
    public function testIsMonth(): void
    {
        for ($month = 1; $month <= 12; $month++) {
            $key = 't' . str_pad("{$month}", 2, '0', STR_PAD_LEFT);
            $this->assertTrue(TimerConfig::isMonth($key));
        }

        $this->assertFalse(TimerConfig::isMonth('q1'));
        $this->assertFalse(TimerConfig::isMonth('h1'));
        $this->assertFalse(TimerConfig::isMonth('y'));
        $this->assertFalse(TimerConfig::isMonth('c'));
    }

    /**
     * @covers ::isQuarter
     */
    public function testIsQuarter(): void
    {
        foreach (['q1', 'q2', 'q3', 'q4'] as $key) {
            $this->assertTrue(TimerConfig::isQuarter($key));
        }

        $this->assertFalse(TimerConfig::isQuarter('t01'));
        $this->assertFalse(TimerConfig::isQuarter('h1'));
        $this->assertFalse(TimerConfig::isQuarter('y'));
        $this->assertFalse(TimerConfig::isQuarter('c'));
    }

    /**
     * @covers ::isHalfYear
     */
    public function testIsHalfYear(): void
    {
        $this->assertTrue(TimerConfig::isHalfYear('h1'));
        $this->assertTrue(TimerConfig::isHalfYear('h2'));

        $this->assertFalse(TimerConfig::isHalfYear('t01'));
        $this->assertFalse(TimerConfig::isHalfYear('q1'));
        $this->assertFalse(TimerConfig::isHalfYear('y'));
        $this->assertFalse(TimerConfig::isHalfYear('c'));
    }

    /**
     * @covers ::isYear
     */
    public function testIsYear(): void
    {
        $this->assertTrue(TimerConfig::isYear('y'));

        $this->assertFalse(TimerConfig::isYear('t01'));
        $this->assertFalse(TimerConfig::isYear('q1'));
        $this->assertFalse(TimerConfig::isYear('h1'));
        $this->assertFalse(TimerConfig::isYear('c'));
    }

    /**
     * @covers ::isCustom
     */
    public function testIsCustom(): void
    {
        $this->assertTrue(TimerConfig::isCustom('c'));

        $this->assertFalse(TimerConfig::isCustom('t01'));
        $this->assertFalse(TimerConfig::isCustom('q1'));
        $this->assertFalse(TimerConfig::isCustom('h1'));
        $this->assertFalse(TimerConfig::isCustom('y'));
    }

    /**
     * @covers ::getMonthFromTimeId
     */
    public function testGetMonthFromTimeId(): void
    {
        for ($month = 1; $month <= 12; $month++) {
            $key = 't' . str_pad("{$month}", 2, '0', STR_PAD_LEFT);
            $this->assertEquals($month, TimerConfig::getMonthFromTimeId($key));
        }

        $this->assertNull(TimerConfig::getMonthFromTimeId('q1'));
        $this->assertNull(TimerConfig::getMonthFromTimeId('h1'));
        $this->assertNull(TimerConfig::getMonthFromTimeId('y'));
        $this->assertNull(TimerConfig::getMonthFromTimeId('c'));
    }

    /**
     * @covers ::getQuarterFromTimeId
     */
    public function testGetQuarterFromTimeId(): void
    {
        foreach ([1 => 'q1', 2 => 'q2', 3 => 'q3', 4 => 'q4'] as $quarter => $key) {
            $this->assertEquals($quarter, TimerConfig::getQuarterFromTimeId($key));
        }

        $this->assertNull(TimerConfig::getQuarterFromTimeId('t01'));
        $this->assertNull(TimerConfig::getQuarterFromTimeId('h1'));
        $this->assertNull(TimerConfig::getQuarterFromTimeId('y'));
        $this->assertNull(TimerConfig::getQuarterFromTimeId('c'));
    }
}
