<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-11 18:45:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\ArDmKh;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Input autocomplete đối tượng (khách hàng, nhà cung cấp, nhân viên).
 *
 * Hỗ trợ nhiều mode (single hoặc multi-mode):
 * - khachhang: Chỉ khách hàng (isKh = true)
 * - nhacungcap: Chỉ nhà cung cấp (isNcc = true)
 * - nhanvien: Chỉ nhân viên (isNv = true)
 * - all: Tất cả (không lọc)
 * - Multi-mode: khachhang,nhacungcap | khachhang,nhanvien | nhacungcap,nhanvien
 *
 * Usage:
 * <livewire:catalog::component.input-khachhang mode="khachhang" wire:model="pMa_Kh" />
 * <livewire:catalog::component.input-khachhang mode="nhanvien" wire:model="pMa_Nv" />
 * <livewire:catalog::component.input-khachhang mode="khachhang,nhacungcap" wire:model="pMa_DoiTuong" />
 */
class InputKhachhang extends Component
{
    /**
     * Mode lọc đối tượng.
     *
     * @var string
     */
    public string $mode = 'khachhang';

    /**
     * Giá trị selected (mã đối tượng).
     */
    public ?string $value = null;

    /**
     * Text hiển thị (tên đối tượng).
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
    public string $placeholder = '';

    /**
     * Mount component.
     *
     * @param null|string $value Giá trị khởi tạo
     * @param string      $mode  Mode lọc (khachhang|nhacungcap|nhanvien|khachhang-va-nhacungcap|all)
     */
    public function mount(?string $value = null, string $mode = 'khachhang'): void
    {
        $this->value = $value;
        $this->mode  = $mode;
        $this->placeholder = $this->getPlaceholderByMode();

        // Load tên đối tượng nếu có value
        if ($value) {
            $kh = ArDmKh::withoutGlobalScope('ksd')->find($value);
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

        // Build query theo mode (hỗ trợ multi-mode: khachhang,nhacungcap)
        $query = ArDmKh::query();

        // Parse mode (comma-separated)
        $modes = array_map('trim', explode(',', $this->mode));

        foreach ($modes as $m) {
            match ($m) {
                'khachhang'  => $query->laKhachHang(),
                'nhacungcap' => $query->laNhaCungCap(),
                'nhanvien'   => $query->laNhanVien(),
                default      => null, // all: không lọc
            };
        }

        // Tìm kiếm theo mã, tên, địa chỉ, tel
        $this->results = $query
            ->search($search)
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
     * Chọn đối tượng từ dropdown.
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
     * Lấy placeholder theo mode.
     */
    protected function getPlaceholderByMode(): string
    {
        // Parse mode để hiển thị placeholder phù hợp
        $modes = array_map('trim', explode(',', $this->mode));

        // Nếu có nhiều mode hoặc all → placeholder chung
        if (count($modes) > 1 || in_array('all', $modes, true)) {
            return 'Chọn đối tượng...';
        }

        // Single mode
        return match ($modes[0]) {
            'khachhang'  => 'Chọn khách hàng...',
            'nhacungcap' => 'Chọn nhà cung cấp...',
            'nhanvien'   => 'Chọn nhân viên...',
            default      => 'Chọn đối tượng...',
        };
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-khachhang');
    }
}
