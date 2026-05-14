<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-16 14:15:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\ArDmKh;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Input autocomplete đối tượng (khách hàng, nhà cung cấp, nhân viên).
 *
 * Hỗ trợ nhiều mode (single hoặc multi-mode, OR logic):
 * - khachhang: Chỉ khách hàng (isKh = true)
 * - nhacungcap: Chỉ nhà cung cấp (isNcc = true)
 * - nhanvien: Chỉ nhân viên (isNv = true)
 * - all: Tất cả (không lọc)
 * - Multi-mode OR: khachhang,nhacungcap (isKh = true OR isNcc = true)
 *
 * Lưu ý: Cả comma (,) và pipe (|) đều dùng OR logic
 *
 * Usage:
 * <livewire:catalog::component.input-khachhang mode="khachhang" wire:model="pMa_Kh" />
 * <livewire:catalog::component.input-khachhang mode="nhanvien" wire:model="pMa_Nv" />
 * <livewire:catalog::component.input-khachhang mode="khachhang,nhanvien" wire:model="pMa_KhachHoacNhanVien" />
 * <livewire:catalog::component.input-khachhang mode="khachhang|nhanvien" wire:model="pMa_KhachHoacNhanVien" />
 */
class InputKhachhang extends Component
{
    /**
     * Mode lọc đối tượng.
     */
    public string $mode = 'khachhang';

    /**
     * Giá trị selected (mã đối tượng).
     */
    #[Modelable]
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
        $this->value       = $value;
        $this->mode        = $mode;
        $this->placeholder = $this->getPlaceholderByMode();

        // Load tên đối tượng nếu có value
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

        if ($this->value) {
            $this->value = null;
            $this->dispatch('value-updated', null);
        }

        if ('' === $search) {
            $this->results   = [];
            $this->searching = false;

            return;
        }

        // Build query theo mode (OR logic - comma hoặc pipe)
        // Mode: khachhang,nhacungcap,nhanvien (isKh = true OR isNcc = true OR isNv = true)
        $query = ArDmKh::query();

        // Parse mode (hỗ trợ cả comma và pipe, đều là OR logic)
        $modes = array_map('trim', preg_split('/[,.|]/', $this->mode));

        // Dùng nested closure cho OR logic với scopes
        $query->where(static function (Builder $q) use ($modes): void {
            foreach ($modes as $i => $m) {
                if (0 === $i) {
                    // Điều kiện đầu tiên dùng where
                    match ($m) {
                        'khachhang'  => $q->laKhachHang(),
                        'nhacungcap' => $q->laNhaCungCap(),
                        'nhanvien'   => $q->laNhanVien(),
                        default      => null,
                    };
                } else {
                    // Các điều kiện sau dùng orWhere với scope
                    match ($m) {
                        'khachhang'  => $q->orWhere(static fn ($sq) => $sq->laKhachHang()),
                        'nhacungcap' => $q->orWhere(static fn ($sq) => $sq->laNhaCungCap()),
                        'nhanvien'   => $q->orWhere(static fn ($sq) => $sq->laNhanVien()),
                        default      => null,
                    };
                }
            }
        });

        // Tìm kiếm theo mã, tên, địa chỉ, tel
        $this->results = $query
            ->search($search)
            ->limit(20)
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

        // Dispatch event để parent component biết value đã thay đổi
        // Giúp trigger updated* hook trong parent khi dùng #[Modelable]
        $this->dispatch('value-updated', $ma_kh);
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
        $this->dispatch('value-updated', null);
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-khachhang');
    }

    /**
     * Lấy placeholder theo mode.
     */
    protected function getPlaceholderByMode(): string
    {
        // Parse mode để hiển thị placeholder phù hợp
        // Hỗ trợ cả comma và pipe (đều là OR logic)
        $modes = array_map('trim', preg_split('/[,.|]/', $this->mode));

        // Nếu có nhiều mode hoặc all → placeholder chung
        if (\count($modes) > 1 || \in_array('all', $modes, true)) {
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
}
