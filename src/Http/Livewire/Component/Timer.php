<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-09 19:25:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Config\TimerConfig;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Timer component for report date range selection.
 *
 * Supports:
 * - Months (t01-t12)
 * - Quarters (q1-q4)
 * - Half years (h1, h2)
 * - Full year (y)
 * - Custom date range (c)
 */
class Timer extends Component
{
    /**
     * Timer period identifier (t01-t12, q1-q4, h1, h2, y, c).
     */
    public string $timerId;

    /**
     * Start date (YYYY-MM-DD).
     */
    public string $timerFrom;

    /**
     * End date (YYYY-MM-DD).
     */
    public string $timerTo;

    /**
     * Available timer options.
     *
     * @var array<string, string>
     */
    public array $options;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->options = TimerConfig::options();

        // Load current timer settings from session
        $timer           = \CatalogService::timer();
        $this->timerId   = $timer['id'];
        $this->timerFrom = $timer['from'];
        $this->timerTo   = $timer['to'];
    }

    /**
     * Handle timer ID change.
     */
    public function updatedTimerId(): void
    {
        // Validate timer ID
        if (!TimerConfig::isValid($this->timerId)) {
            $this->timerId = 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);
        }

        // Update timer settings
        $result = \CatalogService::timer([
            'id'   => $this->timerId,
            'from' => $this->timerFrom,
            'to'   => $this->timerTo,
        ]);

        $this->timerFrom = $result['from'];
        $this->timerTo   = $result['to'];
    }

    /**
     * Handle date change (custom mode only).
     */
    public function updatedTimerFrom(): void
    {
        if (TimerConfig::isCustom($this->timerId)) {
            \CatalogService::timer([
                'id'   => $this->timerId,
                'from' => $this->timerFrom,
                'to'   => $this->timerTo,
            ]);
        }
    }

    /**
     * Handle date change (custom mode only).
     */
    public function updatedTimerTo(): void
    {
        if (TimerConfig::isCustom($this->timerId)) {
            \CatalogService::timer([
                'id'   => $this->timerId,
                'from' => $this->timerFrom,
                'to'   => $this->timerTo,
            ]);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.timer');
    }
}
