<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\In\Dict;

use Diepxuan\Catalog\Http\Livewire\In\Dict\IndmvtList;
use Illuminate\View\View;

final class IndmvtListTest extends \Tests\TestCase
{
    public function testComponentNoLongerExposesServerSideSearchState(): void
    {
        $component = new IndmvtList();

        self::assertFalse(property_exists($component, 'search'), 'Search phai chay client-side bang Alpine, khong duoc la property Livewire.');
        self::assertFalse(method_exists($component, 'filteredRows'), 'filteredRows() server-side phai duoc xoa.');
    }

    public function testRenderPassesAllRowsToView(): void
    {
        $component = new IndmvtList();
        $component->rows = [
            ['ma_vt' => 'VT001', 'ten_vt' => 'Vật tư 1'],
            ['ma_vt' => 'VT002', 'ten_vt' => 'Vật tư 2'],
        ];

        $view = $component->render();

        self::assertInstanceOf(View::class, $view);
        self::assertSame($component->rows, $view->getData()['rows']);
        self::assertArrayNotHasKey('displayRows', $view->getData());
    }

    public function testOpenCreateKeepsSelectedMaVtForCopy(): void
    {
        $component = new IndmvtList();
        $component->selectedMaVt = 'VT001';

        self::assertSame('VT001', $component->selectedMaVt);
    }

}
