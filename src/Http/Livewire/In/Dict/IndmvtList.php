<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Dict;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsINGetDMVT;
use Diepxuan\Simba\StoredProcedures\ProcedureCaller;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Sub-component chỉ chứa thanh tìm kiếm và bảng danh sách vật tư.
 *
 * Tách riêng để khi người dùng gõ vào form Sửa/Thêm, chỉ IndmvtForm
 * re-render, không serialize lại toàn bộ bảng ~1000 dòng. Đây là
 * root cause gây 14s delay giữa các lần blur trong form.
 * Search chạy phía client bằng Alpine (giống pattern ARDMKH list), không
 * gọi Livewire mỗi lần gõ.
 *
 * Giao tiếp với các component khác qua Livewire events:
 *  - Phát `indmvt-list.item-selected` khi user click một dòng.
 *  - Phát `indmvt-list.create-clicked` khi user click "Thêm vật tư".
 *  - Lắng nghe `indmvt-list.refresh-row` để cập nhật 1 dòng sau khi lưu.
 *  - Lắng nghe `indmvt-list.refresh-all` để reload toàn bộ.
 *  - Lắng nghe `indmvt-form.saved` / `indmvt-rename.saved` để biết khi nào refresh.
 */
class IndmvtList extends Component
{
    /** @var list<array<string, mixed>> */
    public array $rows = [];

    public ?string $selectedMaVt = null;

    public ?string $deleteMaVt = null;

    public ?string $statusMessage = null;

    public ?string $errorMessage = null;

    public ?string $loadError = null;

    /** @var list<string> */
    protected $listeners = [
        'indmvt-list.refresh-row' => 'refreshRow',
        'indmvt-list.refresh-all' => 'refreshAll',
        'indmvt-form.saved' => 'handleFormSaved',
        'indmvt-rename.saved' => 'handleRenameSaved',
        'indmvt-list.deleted' => 'removeDeletedRow',
    ];

    public function mount(): void
    {
        $this->refreshAll();
    }

    public function refreshAll(): void
    {
        $this->loadError = null;
        try {
            $collection = AsINGetDMVT::call([
                'pMa_cty'   => $this->companyId(),
                'pMa_vt'    => null,
                'pStruct'   => null,
                'pLanguage' => $this->languageName(),
            ]);
            $this->rows = $this->normalizeRows($collection->all());
        } catch (\Throwable $exception) {
            $this->rows = [];
            $this->loadError = $exception->getMessage();
        }
    }

    public function selectItem(string $maVt): void
    {
        $this->selectedMaVt = $maVt;
        $this->deleteMaVt = null;

        $row = null;
        foreach ($this->rows as $existing) {
            if ((string) ($existing['ma_vt'] ?? '') === $maVt) {
                $row = $existing;
                break;
            }
        }

        $this->dispatch('indmvt-list.item-selected', maVt: $maVt, row: $row);
    }

    public function openCreate(): void
    {
        $copyMaVt = $this->selectedMaVt;
        $this->selectedMaVt = null;
        $this->deleteMaVt = null;
        $this->dispatch('indmvt-list.create-clicked', copyMaVt: $copyMaVt);
    }

    public function openEdit(string $maVt): void
    {
        $this->selectedMaVt = $maVt;
        $this->deleteMaVt = null;
        $this->dispatch('indmvt-detail.edit-requested', maVt: $maVt);
    }

    public function openRename(string $maVt): void
    {
        $this->selectedMaVt = $maVt;
        $this->deleteMaVt = null;
        $this->dispatch('indmvt-list.rename-clicked', maVt: $maVt);
    }

    public function confirmDelete(string $maVt): void
    {
        $this->deleteMaVt = $maVt;
        $this->selectedMaVt = $maVt;
        $this->statusMessage = null;
        $this->errorMessage = null;
    }

    public function cancelDelete(): void
    {
        $this->deleteMaVt = null;
    }

    public function deleteItem(): void
    {
        if (null === $this->deleteMaVt || '' === trim($this->deleteMaVt)) {
            return;
        }

        $this->performDelete(trim($this->deleteMaVt));
    }

    /**
     * Xóa từ bảng chi tiết (Alpine đã confirm trước khi gọi).
     */
    public function deleteDetailItem(string $maVt): void
    {
        $maVt = trim($maVt);
        if ('' === $maVt) {
            return;
        }

        $this->performDelete($maVt);
    }

    private function performDelete(string $deletedMaVt): void
    {
        try {
            ProcedureCaller::call('asINDelDMVT', [
                'pMa_cty' => $this->companyId(),
                'pMa_vt'  => $deletedMaVt,
            ], (new SModel())->getConnectionName());

            $this->rows = array_values(array_filter(
                $this->rows,
                static fn (array $row): bool => ($row['ma_vt'] ?? null) !== $deletedMaVt
            ));
            $this->statusMessage = "Đã xóa vật tư {$deletedMaVt}.";
            $this->deleteMaVt = null;
            $this->selectedMaVt = null;
            $this->dispatch('indmvt-list.deleted', maVt: $deletedMaVt);
        } catch (\Throwable $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    /**
     * Refresh chỉ một dòng sau khi form Sửa/Thêm lưu thành công.
     * Tránh gọi refreshAll vì toàn bộ danh sách lớn sẽ mất ~10s.
     */
    public function refreshRow(string $maVt): void
    {
        try {
            $collection = AsINGetDMVT::call([
                'pMa_cty'   => $this->companyId(),
                'pMa_vt'    => $maVt,
                'pStruct'   => null,
                'pLanguage' => $this->languageName(),
            ]);
            $rows = $this->normalizeRows($collection->all());
            if ([] === $rows) {
                return;
            }
            $found = false;
            foreach ($this->rows as $index => $existing) {
                if (($existing['ma_vt'] ?? null) === $maVt) {
                    $this->rows[$index] = $rows[0];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->rows[] = $rows[0];
            }
        } catch (\Throwable) {
            // Không block UI khi refresh một dòng lỗi.
        }
    }

    public function handleFormSaved(string $maVt): void
    {
        $this->refreshRow($maVt);
        $this->selectedMaVt = $maVt;
    }

    public function handleRenameSaved(string $oldMaVt, string $newMaVt): void
    {
        // Đổi mã: cập nhật key ma_vt trong rows, xoá entry cũ.
        foreach ($this->rows as $index => $existing) {
            if (($existing['ma_vt'] ?? null) === $oldMaVt) {
                $existing['ma_vt'] = $newMaVt;
                $this->rows[$index] = $existing;
                break;
            }
        }
        $this->selectedMaVt = $newMaVt;
    }

    public function removeDeletedRow(string $maVt): void
    {
        $this->rows = array_values(array_filter(
            $this->rows,
            static fn (array $row): bool => ($row['ma_vt'] ?? null) !== $maVt
        ));

        if ($this->selectedMaVt === $maVt) {
            $this->selectedMaVt = null;
        }
    }

    public function render(): View
    {
        return view('catalog::in.dict.indmvt-list', ['rows' => $this->rows]);
    }

    private function companyId(): string
    {
        try {
            return (string) (\CatalogService::company()->id ?? SModel::CTY);
        } catch (\Throwable) {
            return SModel::CTY;
        }
    }

    private function languageName(): string
    {
        try {
            return (string) (\CatalogService::language()->name ?? 'vi-VN');
        } catch (\Throwable) {
            return 'vi-VN';
        }
    }

    /**
     * @param array<int, mixed> $rows
     * @return list<array<string, mixed>>
     */
    private function normalizeRows(array $rows): array
    {
        return array_map(function (mixed $row): array {
            $array = is_array($row) ? $row : get_object_vars($row);
            $normalized = [];
            foreach ($array as $key => $value) {
                $normalized[strtolower((string) $key)] = $value;
            }

            return $normalized;
        }, $rows);
    }
}
