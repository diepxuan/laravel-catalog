<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-03-06
 */

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\CatalogService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Diepxuan\Catalog\Services\CatalogService
 */
class CatalogServiceTimerTest extends TestCase
{
    protected CatalogService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CatalogService();

        // Mock session
        Session::shouldReceive('get')->with('year', \Mockery::any())->andReturn(2026);
        Session::shouldReceive('get')->with('timeId', \Mockery::any())->andReturn('t03');
        Session::shouldReceive('get')->with('timeStart', null)->andReturn(null);
        Session::shouldReceive('get')->with('timeEnd', null)->andReturn(null);
        Session::shouldReceive('put')->with(\Mockery::any())->andReturn(null);
    }

    /**
     * @covers ::getTimer
     */
    public function testGetTimerReturnsDefaultWhenSessionEmpty(): void
    {
        Session::shouldReceive('get')->with('timeId', \Mockery::any())->andReturn(null);

        $result = $this->service->getTimer();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('from', $result);
        $this->assertArrayHasKey('to', $result);
        $this->assertMatchesRegularExpression('/^t\d{2}$/', $result['id']);
    }

    /**
     * @covers ::setTimer
     * @covers ::calculateTimeRange
     */
    public function testSetTimerWithMonth(): void
    {
        Session::shouldReceive('get')->with('timeId', \Mockery::any())->andReturn('t03');

        $result = $this->service->setTimer(['id' => 't03']);

        $this->assertEquals('t03', $result['id']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result['from']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result['to']);
    }

    /**
     * @covers ::setTimer
     * @covers ::calculateTimeRange
     */
    public function testSetTimerWithQuarter(): void
    {
        $result = $this->service->setTimer(['id' => 'q1']);

        $this->assertEquals('q1', $result['id']);
        // Q1: Jan-Mar
        $this->assertStringContainsString('-01-', $result['from']);
        $this->assertStringContainsString('-03-', $result['to']);
    }

    /**
     * @covers ::setTimer
     * @covers ::calculateTimeRange
     */
    public function testSetTimerWithHalfYearH1(): void
    {
        $result = $this->service->setTimer(['id' => 'h1']);

        $this->assertEquals('h1', $result['id']);
        // H1: Jan-Jun
        $this->assertStringContainsString('-01-', $result['from']);
        $this->assertStringContainsString('-06-', $result['to']);
    }

    /**
     * @covers ::setTimer
     * @covers ::calculateTimeRange
     */
    public function testSetTimerWithHalfYearH2(): void
    {
        $result = $this->service->setTimer(['id' => 'h2']);

        $this->assertEquals('h2', $result['id']);
        // H2: Jul-Dec
        $this->assertStringContainsString('-07-', $result['from']);
        $this->assertStringContainsString('-12-', $result['to']);
    }

    /**
     * @covers ::setTimer
     * @covers ::calculateTimeRange
     */
    public function testSetTimerWithYear(): void
    {
        $result = $this->service->setTimer(['id' => 'y']);

        $this->assertEquals('y', $result['id']);
        // Year: Jan 1 - Dec 31
        $this->assertStringContainsString('-01-01', $result['from']);
        $this->assertStringContainsString('-12-31', $result['to']);
    }

    /**
     * @covers ::setTimer
     * @covers ::calculateTimeRange
     */
    public function testSetTimerWithCustom(): void
    {
        $result = $this->service->setTimer([
            'id' => 'c',
            'from' => '2026-03-01',
            'to' => '2026-03-31',
        ]);

        $this->assertEquals('c', $result['id']);
        $this->assertEquals('2026-03-01', $result['from']);
        $this->assertEquals('2026-03-31', $result['to']);
    }

    /**
     * @covers ::setTimer
     */
    public function testSetTimerWithInvalidIdFallsBackToCurrentMonth(): void
    {
        $result = $this->service->setTimer(['id' => 'invalid']);

        $this->assertMatchesRegularExpression('/^t\d{2}$/', $result['id']);
    }

    /**
     * @covers ::timer
     * @deprecated But still supported for backward compatibility
     */
    public function testLegacyTimerMethod(): void
    {
        // Without params - should return current timer
        $result = $this->service->timer();
        $this->assertArrayHasKey('id', $result);

        // With string param - should set timer
        $result = $this->service->timer('q2');
        $this->assertEquals('q2', $result['id']);

        // With array param - should set timer
        $result = $this->service->timer(['id' => 'h1']);
        $this->assertEquals('h1', $result['id']);
    }
}
