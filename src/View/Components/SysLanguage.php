<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-20 10:30:00
 */

namespace Diepxuan\Catalog\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

// <x-catalog::sys-language>
class SysLanguage extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): string|View
    {
        $language = \Diepxuan\Catalog\Models\SysLanguage::current()->first();
        // dd($language);

        return <<<HTML
                <div class="text-xs text-gray-500">
                    <span>{$language->Name} | {$language->Description}</span>
                </div>
            HTML;
    }
}
