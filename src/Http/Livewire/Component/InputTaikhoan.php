<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-03 19:14:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\GlDmTk;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Component input chọn tài khoản với search thông minh
 *
 * Features:
 * - Search theo mã tài khoản hoặc tên tài khoản
 * - Hiển thị cả mã và tên tài khoản trong dropdown
 * - Dropdown styling đẹp
 * - Support keyboard navigation
 */
class InputTaikhoan extends Component
{
    #[Modelable]
    public $pTk;

    public $search = '';
    public $showDropdown = false;
    public $selectedIndex = -1;
    public $glDmTks = [];
    public $filteredTks = [];

    /**
     * Mount component
     */
    public function mount(): void
    {
        $this->glDmTks = GlDmTk::select('tk', 'ten_tk')
            ->orderBy('tk')
            ->limit(100)
            ->get()
            ->toArray();

        // Nếu đã có giá trị pTk, set search bằng mã TK
        if (!empty($this->pTk)) {
            $this->search = (string) $this->pTk;
        }
    }

    /**
     * Cập nhật khi search thay đổi
     */
    public function updatedSearch(): void
    {
        $this->filterTks();
        $this->showDropdown = true;
        $this->selectedIndex = -1;
    }

    /**
     * Cập nhật khi pTk thay đổi
     */
    public function updatedPTk(): void
    {
        if (!empty($this->pTk)) {
            $tk = $this->getTkInfo($this->pTk);
            if ($tk) {
                $this->search = $tk['tk'];
            }
        }
    }

    /**
     * Lọc tài khoản theo search
     */
    protected function filterTks(): void
    {
        $search = mb_strtolower($this->search);

        if (empty($search)) {
            $this->filteredTks = array_slice($this->glDmTks, 0, 10);
            return;
        }

        $this->filteredTks = array_filter($this->glDmTks, function ($tk) use ($search) {
            return mb_strtolower($tk['tk']) === $search
                || mb_strtolower($tk['ten_tk']) === $search
                || mb_strtolower($tk['tk']) . ' ' . mb_strtolower($tk['ten_tk']) === $search
                || str_contains(mb_strtolower($tk['tk']), $search)
                || str_contains(mb_strtolower($tk['ten_tk']), $search);
        });

        $this->filteredTks = array_slice(array_values($this->filteredTks), 0, 10);
    }

    /**
     * Chọn tài khoản
     */
    public function selectTk($tk): void
    {
        $this->pTk = $tk['tk'];
        $this->search = $tk['tk'];
        $this->showDropdown = false;
        $this->dispatch('account-selected', account: $tk['tk']);
    }

    /**
     * Lấy thông tin tài khoản theo mã
     */
    protected function getTkInfo($maTk): ?array
    {
        foreach ($this->glDmTks as $tk) {
            if ($tk['tk'] == $maTk) {
                return $tk;
            }
        }
        return null;
    }

    /**
     * Hiển thị dropdown
     */
    public function showDropdown(): void
    {
        $this->filterTks();
        $this->showDropdown = true;
    }

    /**
     * Ẩn dropdown
     */
    public function hideDropdown(): void
    {
        // Delay để click vào item hoạt động
        $this->dispatch('hide-dropdown', delay: 200);
    }

    /**
     * Xử lý keyboard navigation
     */
    public function handleKeydown($key): void
    {
        if (!$this->showDropdown) {
            if (in_array($key, ['ArrowDown', 'ArrowUp'])) {
                $this->showDropdown = true;
                $this->filterTks();
            }
            return;
        }

        $count = count($this->filteredTks);

        switch ($key) {
            case 'ArrowDown':
                $this->selectedIndex = min($this->selectedIndex + 1, $count - 1);
                break;

            case 'ArrowUp':
                $this->selectedIndex = max($this->selectedIndex - 1, -1);
                break;

            case 'Enter':
                if ($this->selectedIndex >= 0 && $this->selectedIndex < $count) {
                    $this->selectTk($this->filteredTks[$this->selectedIndex]);
                }
                break;

            case 'Escape':
                $this->showDropdown = false;
                $this->selectedIndex = -1;
                break;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-taikhoan', [
            'filteredTks' => $this->filteredTks,
        ]);
    }
}
