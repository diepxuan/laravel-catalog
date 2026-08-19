<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-09 00:28:31
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Input chọn năm làm việc trên header.
 *
 * Danh sách năm mặc định: 2016 -> hiện tại + 1.
 * Không truy vấn bảng dữ liệu mua/bán/xuất/nhập kho.
 */
class InputYearWorked extends Component
{
    public const FIRST_YEAR = 2_016;

    public int $selectedYear;

    public function mount(): void
    {
        $this->selectedYear = catalog()->year();
    }

    public function render(): View
    {
        return view('catalog::components.input-year-worked', [
            'firstYear' => self::FIRST_YEAR,
        ]);
    }

    /**
     * Build the selectable year range: 2016 -> current + 1.
     *
     * @return Collection<int, int>
     */
    public static function availableYears(?int $currentYear = null): Collection
    {
        $currentYear ??= (int) now()->year;

        return collect(range(self::FIRST_YEAR, $currentYear + 1));
    }
}
