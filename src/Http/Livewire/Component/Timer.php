<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-27 10:02:04
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Timer extends Component
{
    public $timer;
    public $options;

    public function boot(): void
    {
        $this->options = collect(range(1, 12))
            ->mapWithKeys(static function ($month) {
                $strMonth = str_pad("{$month}", 2, '0', STR_PAD_LEFT);

                return ["t{$strMonth}" => "Tháng {$strMonth}"];
            })
            ->merge(
                collect(range(1, 4))->mapWithKeys(static fn ($quarter) => ["q{$quarter}" => "Quý {$quarter}"]),
            )
            ->merge(
                collect([
                    'h1' => '6 tháng đầu năm',
                    'h2' => '6 tháng cuối năm',
                    'y'  => 'Cả năm',
                    'c'  => 'Từ ... đến ...',
                ]),
            )
        ;
    }

    public function mount(): void
    {
        $this->timer = \CatalogService::timer();
        // \Debugbar::info($this->timer);
    }

    public function changeTimer(): void
    {
        $this->timer = \CatalogService::timer($this->timer);
        // \Debugbar::info($this->timer);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.timer');
    }
}
