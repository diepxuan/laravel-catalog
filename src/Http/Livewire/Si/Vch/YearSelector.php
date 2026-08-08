<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-08
 */

namespace Diepxuan\Catalog\Http\Livewire\Si\Vch;

use Diepxuan\Catalog\Http\Livewire\Component\InputYearWorked;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class YearSelector extends Component
{
    public int $selectedYear;

    public ?string $statusMessage = null;

    public function mount(): void
    {
        $this->selectedYear = catalog()->year();
    }

    public function selectYear(int $year): void
    {
        $currentYear = (int) now()->year;
        if ($year < InputYearWorked::FIRST_YEAR || $year > $currentYear + 1) {
            $this->addError('selectedYear', __('Năm làm việc không hợp lệ.'));

            return;
        }

        $this->selectedYear  = catalog()->year($year);
        $this->statusMessage = __('Đã chọn năm làm việc :year.', ['year' => $this->selectedYear]);
    }

    public function render(): View
    {
        /** @var Collection<int, int> $years */
        $years = InputYearWorked::availableYears();

        return view('catalog::si.vch.year-selector', [
            'years' => $years,
        ])->layout('catalog::layouts.app');
    }
}
