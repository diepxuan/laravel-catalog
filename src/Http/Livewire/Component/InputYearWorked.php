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
use Livewire\Livewire;

/**
 * Input chọn năm làm việc trên header.
 *
 * Danh sách năm mặc định: 2016 -> hiện tại + 1.
 * Không truy vấn bảng dữ liệu mua/bán/xuất/nhập kho.
 */
class InputYearWorked extends Component
{
    public const FIRST_YEAR = 2_016;

    public bool $open = false;

    public int $selectedYear;

    public ?string $statusMessage = null;

    public function mount(): void
    {
        $this->selectedYear = catalog()->year();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function selectYear(int $year): void
    {
        $currentYear = (int) now()->year;
        if ($year < self::FIRST_YEAR || $year > $currentYear + 1) {
            $this->addError('selectedYear', __('Năm làm việc không hợp lệ.'));

            return;
        }

        $this->selectedYear  = catalog()->year($year);
        $this->statusMessage = __('Đã chọn năm làm việc :year.', ['year' => $this->selectedYear]);
        $this->open          = false;
        // request()->url() tro ve /livewire/update trong update request;
        // originalUrl() doc memo.path tu snapshot de lay URL trang hien tai.
        $this->redirect(Livewire::originalUrl(), navigate: true);
    }

    public function render(): View
    {
        return view('catalog::components.input-year-worked', [
            'years' => self::availableYears(),
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
