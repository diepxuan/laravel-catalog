<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\Component;

use Diepxuan\Catalog\Http\Livewire\Component\InputYearWorked;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

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

    public function testSelectYearUpdatesSessionAndRedirectsToOriginalPageUrl(): void
    {
        Session::start();

        $testable = Livewire::test(InputYearWorked::class)
            ->call('selectYear', 2026)
            ->assertSet('selectedYear', 2026)
        ;

        // Redirect phai tro ve URL trang goc (memo.path trong snapshot),
        // KHONG duoc tro ve /livewire/update (request()->url() trong update request).
        $testable->assertRedirectContains('livewire-unit-test-endpoint');

        self::assertStringNotContainsString('livewire/update', $testable->effects['redirect']);

        self::assertSame(2026, Session::get('year'));
    }
}
