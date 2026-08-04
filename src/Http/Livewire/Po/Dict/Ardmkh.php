<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-03 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Po\Dict;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Base cho 3 danh sách ARDMKH (PO/SO/CA).
 *
 * Load toàn bộ rows một lần qua SP `asARGetDMKH` (pModuleId thay đổi theo module);
 * search chạy phía client bằng Alpine trong view, page không dùng pagination.
 */
class Ardmkh extends Component
{
    /** Mã công ty hiện tại (truyền cho view để render, không dùng trong search). */
    public string $maCty;

    /** Toàn bộ rows sau khi normalize; view dùng Alpine filter, không dùng pagination. */
    public array $rows = [];

    public function mount(): void
    {
        $this->maCty = SModel::CTY;
        $this->rows  = $this->loadAllRows();
    }

    public function deleteDoiTuong(string $maKh): void
    {
        $doiTuong = \Diepxuan\Catalog\Models\Simba\ArDmKh::withoutGlobalScopes()
            ->where('ma_kh', $maKh)
            ->first()
        ;

        if (!$doiTuong) {
            $this->dispatch('error', message: $this->notFoundMessage());

            return;
        }

        if ($doiTuong->hasTransactions()) {
            $this->dispatch('error', message: $this->hasTransactionsMessage());

            return;
        }

        try {
            \Diepxuan\Simba\StoredProcedures\AsARDelDMKH::call([
                'pMa_cty' => $this->maCty,
                'pMa_kh'  => $maKh,
            ]);

            $this->dispatch('success', message: $this->deletedMessage($maKh));
            // Reload để đồng bộ sau khi xóa.
            $this->rows = $this->loadAllRows();
        } catch (\Exception $e) {
            $this->dispatch('error', message: $this->deleteFailedMessage($maKh, $e->getMessage()));
        }
    }

    public function render(): View
    {
        return view($this->listView(), [
            'rows'   => $this->rows,
            'maCty'  => $this->maCty,
            'module' => $this->moduleLabel(),
        ])->layout('catalog::layouts.app');
    }

    /**
     * Subclass override để chọn mode (AR/AP/CA) + view blade + label.
     */
    protected function spModuleId(): string
    {
        return 'AP';
    }

    protected function listView(): string
    {
        return 'catalog::po.dict.ardmkh';
    }

    protected function moduleLabel(): string
    {
        return 'PO';
    }

    protected function notFoundMessage(): string
    {
        return 'Không tìm thấy nhà cung cấp.';
    }

    protected function hasTransactionsMessage(): string
    {
        return 'Không thể xóa nhà cung cấp đã có giao dịch.';
    }

    protected function deletedMessage(string $maKh): string
    {
        return 'Đã xóa nhà cung cấp ' . $maKh;
    }

    protected function deleteFailedMessage(string $maKh, string $reason): string
    {
        return 'Không thể xóa nhà cung cấp ' . $maKh . ': ' . $reason;
    }

    /**
     * Load toàn bộ rows từ SP. Không truyền `search` để SP trả về hết; Alpine phía client lo filter.
     *
     * @return array<int, object>
     */
    protected function loadAllRows(): array
    {
        $raw = AsARGetDMKH::call([
            'pMa_cty'   => $this->maCty,
            'pMa_kh'    => null,
            'pStruct'   => '0',
            'pModuleId' => $this->spModuleId(),
        ]);

        return $this->normalizeRows($raw)->values()->all();
    }

    /**
     * Chuẩn hóa row về object với field lowercase; render Blade đọc thẳng.
     *
     * @return Collection<int, object>
     */
    protected function normalizeRows($results): Collection
    {
        return new Collection(collect($results)->map(static fn ($item) => (object) [
            'ma_kh'      => $item->ma_kh ?? $item->MA_KH ?? '',
            'ten_kh'     => $item->ten_kh ?? $item->TEN_KH ?? '',
            'dia_chi'    => $item->dia_chi ?? $item->DIA_CHI ?? '',
            'tel'        => $item->tel ?? $item->TEL ?? '',
            'nguoi_gd'   => $item->nguoi_gd ?? $item->NGUOI_GD ?? '',
            'ma_httt_po' => $item->ma_httt_po ?? $item->MA_HTTT_PO ?? '',
            'ma_so_thue' => $item->ma_so_thue ?? $item->MA_SO_THUE ?? '',
            'ma_nhkh'    => $item->ma_nhkh ?? $item->MA_NHKH ?? '',
        ]));
    }
}
