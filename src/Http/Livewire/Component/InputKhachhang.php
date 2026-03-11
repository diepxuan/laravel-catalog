<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-11 15:52:31
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\ArDmKh;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Input autocomplete khách hàng.
 *
 * Hỗ trợ:
 * - Tìm kiếm theo mã khách hàng
 * - Tìm kiếm theo tên khách hàng
 * - Dropdown với danh sách gợi ý
 */
class InputKhachhang extends Component
{
    /**
     * Giá trị selected (mã khách hàng).
     */
    public ?string $value = null;

    /**
     * Text hiển thị (tên khách hàng).
     */
    public string $search = '';

    /**
     * Danh sách kết quả tìm kiếm.
     */
    public array $results = [];

    /**
     * Có đang tìm kiếm không.
     */
    public bool $searching = false;

    /**
     * Có đang hiển thị dropdown không.
     */
    public bool $showDropdown = false;

    /**
     * Placeholder text.
     */
    public string $placeholder = 'Chọn khách hàng...';

    /**
     * Mount component.
     *
     * @param null|string $value Giá trị khởi tạo
     */
    public function mount(?string $value = null): void
    {
        $this->value = $value;

        // Load tên khách hàng nếu có value
        if ($value) {
            $kh = ArDmKh::find($value);
            if ($kh) {
                $this->search = $kh->ten_kh ?? '';
            }
        }
    }

    /**
     * Xử lý khi search input thay đổi.
     */
    public function updatedSearch(): void
    {
        $this->searching = true;

        $search = trim($this->search);

        if ('' === $search) {
            $this->results   = [];
            $this->searching = false;

            return;
        }

        // Tìm kiếm theo mã hoặc tên
        $this->results = ArDmKh::where('isKh', true)
            ->where(static function ($query) use ($search): void {
                $query->where('ma_kh', 'like', "%{$search}%")
                    ->orWhere('ten_kh', 'like', "%{$search}%")
                    ->orWhere('dia_chi', 'like', "%{$search}%")
                    ->orWhere('tel', 'like', "%{$search}%")
                ;
            })
            ->limit(10)
            ->get()
            ->map(static fn ($kh) => [
                'ma_kh'   => $kh->ma_kh,
                'ten_kh'  => $kh->ten_kh,
                'dia_chi' => $kh->dia_chi ?? '',
                'tel'     => $kh->tel ?? '',
            ])
            ->toArray()
        ;

        $this->searching    = false;
        $this->showDropdown = true;
    }

    /**
     * Chọn khách hàng từ dropdown.
     */
    public function selectCustomer(string $ma_kh, string $ten_kh): void
    {
        $this->value        = $ma_kh;
        $this->search       = $ten_kh;
        $this->showDropdown = false;
        $this->results      = [];

        // Emit event để parent component biết
        $this->dispatch('customer-selected', ma_kh: $ma_kh);
    }

    /**
     * Đóng dropdown.
     */
    public function blur(): void
    {
        // Delay đóng dropdown để click vào item hoạt động
        $this->dispatch('close-dropdown');
    }

    /**
     * Xóa selection.
     */
    public function clear(): void
    {
        $this->value        = null;
        $this->search       = '';
        $this->results      = [];
        $this->showDropdown = false;
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-khachhang');
    }
}
