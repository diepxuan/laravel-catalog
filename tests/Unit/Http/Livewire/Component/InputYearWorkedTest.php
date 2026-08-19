<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\Component;

use Diepxuan\Catalog\Http\Livewire\Component\InputYearWorked;
use Diepxuan\Catalog\Http\Controllers\YearController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class InputYearWorkedTest extends \Tests\TestCase
{
    public function testRangeStartsAt2016AndEndsAtCurrentYearPlusOne(): void
    {
        $years = InputYearWorked::availableYears(2026);

        self::assertSame(2016, $years->first());
        self::assertSame(2027, $years->last());
        self::assertCount(12, $years);
    }

    public function testRangeAlwaysIncludesCurrentYear(): void
    {
        $years = InputYearWorked::availableYears(2026);

        self::assertContains(2016, $years);
        self::assertContains(2026, $years);
        self::assertContains(2027, $years);
    }

    public function testFirstYearConstantIsFixedAt2016(): void
    {
        self::assertSame(2016, InputYearWorked::FIRST_YEAR);
    }

    public function testYearControllerValidatesAndUpdatesYear(): void
    {
        $request = Request::create('/simba/si/vch/year/select', 'POST', ['year' => 2026]);
        $response = (new YearController())->select($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(2026, $response->getData()->year);
        self::assertSame(2026, catalog()->year());
    }
}
