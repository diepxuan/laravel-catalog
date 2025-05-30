<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-26 21:22:34
 */

namespace Diepxuan\Catalog\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

// <x-catalog::sys-year>
class SysYear extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): string|View
    {
        $year = \CatalogService::year();

        return <<<HTML
                <span>[ {$year} ]</span>
            HTML;
    }
}
