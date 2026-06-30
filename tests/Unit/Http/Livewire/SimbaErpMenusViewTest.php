<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire;

use PHPUnit\Framework\TestCase;

final class SimbaErpMenusViewTest extends TestCase
{
    public function testMainSimbaMenuViewDoesNotRenderOnePartialPerNode(): void
    {
        $view = (string) file_get_contents(dirname(__DIR__, 4) . '/resources/views/system/simba-erp-menus.blade.php');

        self::assertStringNotContainsString('catalog::system.simba-node', $view);
        self::assertStringContainsString('@forelse ($tree as $item)', $view);
        self::assertStringContainsString('x-show="isVisible', $view);
    }
}
