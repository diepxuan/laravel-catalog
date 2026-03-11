<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-11 23:16:49
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Input autocomplete đơn vị (kho, vật tư, bộ phận, ngoại tệ, ...).
 *
 * Component generic cho các danh mục với search autocomplete.
 *
 * Usage:
 * <livewire:catalog::component.input-donvi
 *     model="InDmKho"
 *     field="ma_kho"
 *     label="ten_kho"
 *     wire:model="pMa_Kho"
 * />
 *
 * @property string $model       Model class name (e.g., InDmKho, InDmVt, SiDmBp, SiDmNt)
 * @property string $field       Field name for value (e.g., ma_kho, ma_vt, ma_bp, ma_nt)
 * @property string $label       Field name for display (e.g., ten_kho, ten_vt, ten_bp, ten_nt)
 * @property string $placeholder Placeholder text
 */
class InputDonVi extends Component
{
    /**
     * Model class name.
     */
    public string $model;

    /**
     * Field name for value (primary key).
     */
    public string $field;

    /**
     * Field name for display label.
     */
    public string $label;

    /**
     * Giá trị selected.
     */
    public ?string $value = null;

    /**
     * Text hiển thị.
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
    public string $placeholder = 'Chọn...';

    /**
     * Mount component.
     *
     * @param string      $model       Model class name
     * @param string      $field       Field name for value
     * @param string      $label       Field name for display
     * @param null|string $value       Initial value
     * @param string      $placeholder Placeholder text
     */
    public function mount(
        string $model,
        string $field,
        string $label,
        ?string $value = null,
        string $placeholder = 'Chọn...'
    ): void {
        $this->model       = $model;
        $this->field       = $field;
        $this->label       = $label;
        $this->value       = $value;
        $this->placeholder = $placeholder;

        // Load label nếu có value
        if ($value) {
            $modelClass = $this->getModelClass();
            $item       = $modelClass::withoutGlobalScopes()->find($value);
            if ($item) {
                $this->search = $item->{$this->label} ?? '';
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

        $modelClass = $this->getModelClass();
        $field      = $this->field;
        $label      = $this->label;

        // Tìm kiếm theo field hoặc label
        $this->results = $modelClass::query()
            ->where(static function (Builder $q) use ($search, $field, $label): void {
                $q->where($field, 'like', "%{$search}%")
                    ->orWhere($label, 'like', "%{$search}%")
                ;
            })
            ->limit(10)
            ->get()
            ->map(static fn ($item) => [
                'value' => $item->{$field},
                'label' => $item->{$label},
            ])
            ->toArray()
        ;

        $this->searching    = false;
        $this->showDropdown = true;
    }

    /**
     * Chọn item từ dropdown.
     */
    public function selectItem(string $value, string $label): void
    {
        $this->value        = $value;
        $this->search       = $label;
        $this->showDropdown = false;
        $this->results      = [];

        // Emit event để parent component biết
        $this->dispatch('item-selected', field: $this->field, value: $value);
    }

    /**
     * Đóng dropdown.
     */
    public function blur(): void
    {
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
        return view('catalog::components.input-donvi');
    }

    /**
     * Get model class name.
     */
    protected function getModelClass(): string
    {
        // Nếu đã là full namespace
        if (class_exists($this->model)) {
            return $this->model;
        }

        // Try common namespaces
        $namespaces = [
            "Diepxuan\\Catalog\\Models\\{$this->model}",
            "Diepxuan\\Simba\\Models\\{$this->model}",
            "Diepxuan\\Simba\\SModel\\{$this->model}Model",
        ];

        foreach ($namespaces as $namespace) {
            if (class_exists($namespace)) {
                return $namespace;
            }
        }

        // Default: return as is (will fail if not found)
        return $this->model;
    }
}
