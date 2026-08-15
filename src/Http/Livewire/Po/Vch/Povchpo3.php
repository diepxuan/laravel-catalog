<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-18
 */

namespace Diepxuan\Catalog\Http\Livewire\Po\Vch;

use Diepxuan\Simba\StoredProcedures\AsPOFilt3;
use Diepxuan\Simba\StoredProcedures\AsPODeletePO3;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Danh sách hóa đơn mua hàng (PO3).
 *
 * Mapping:
 * - menuID   : `10.10.14` (sysMenu).
 * - DLL      : `POVchPO3.dll` (form `frmPOVchPO3`).
 * - SP       : `asPOFilt3` (danh sách header + chi tiết) + `asPOGetPO3` (mở edit).
 * - Route    : `_simba-source/po/vch/povchpo3` (slug khong suffix menuid vi PO3 la 1-1 sysMenu).
 *
 * Refactor tu `Muahang\Hoadonmua` (dead code) sang pattern chuan `Po\Vch\Povchpo3`
 * (giong `So\Vch\Sovchso3`). Bind SP `asPOFilt3` thay vi Eloquent `PoPh3`.
 */
class Povchpo3 extends Component
{
    public const MA_CT = 'PO3';

    public string $pSearch = '';

    public ?string $pMa_kh = null;

    public int $pPageIndex = 1;

    public int $pPageSize = 50;

    public int $timerKey = 0;

    /** @var list<array<string, mixed>> */
    public array $phieuRows = [];

    /** @var list<array<string, mixed>> */
    public array $chiTietRows = [];

    /** @var list<array<string, mixed>> */
    public array $chiTietFiltered = [];

    public ?int $selectedPhieuIndex = null;

    /** @var array<string, mixed> */
    public array $selectedPhieu = [];

    public function mount(): void
    {
        $this->clearSelection();
    }

    public function updated($property): void
    {
        if (str_starts_with((string) $property, 'p')) {
            $this->pPageIndex = 1;
        }
    }

    public function submit(): void
    {
        $maCty = (string) \CatalogService::company()->id;
        $sets = AsPOFilt3::callWithDataSets([
            'pKeyPh' => AsPOFilt3::keyPh(
                $maCty,
                self::MA_CT,
                \CatalogService::timerFrom(),
                \CatalogService::timerTo(),
                $this->pMa_kh,
                $this->pSearch
            ),
            'pKeyCt' => AsPOFilt3::keyCt($maCty),
        ]);

        $this->phieuRows = $sets['ph']
            ->map(static fn (mixed $row): array => (array) $row)
            ->values()
            ->all();
        $this->chiTietRows = $sets['ct']
            ->map(static fn (mixed $row): array => self::normalizePoDetail($row))
            ->values()
            ->all();
        $this->clearSelection();

        if ([] !== $this->phieuRows) {
            $this->dispatch('switch-tab', 'content');
        }
    }

    public function resetFilters(): void
    {
        $this->pSearch    = '';
        $this->pMa_kh     = null;
        $this->pPageIndex = 1;

        \CatalogService::timer(['id' => 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT)]);
        $this->timerKey++;

        $this->phieuRows = [];
        $this->chiTietRows = [];
        $this->clearSelection();
    }

    public function selectPhieu(int $index): void
    {
        if (!isset($this->phieuRows[$index])) {
            $this->clearSelection();

            return;
        }

        if ($index === $this->selectedPhieuIndex) {
            $this->clearSelection();

            return;
        }

        $this->selectedPhieuIndex = $index;
        $this->selectedPhieu = $this->phieuRows[$index];
        $sttRec = (string) ($this->selectedPhieu['stt_rec'] ?? '');
        $this->chiTietFiltered = array_values(array_filter(
            $this->chiTietRows,
            static fn (array $row): bool => (string) ($row['stt_rec'] ?? '') === $sttRec
        ));
    }

    public function clearSelection(): void
    {
        $this->selectedPhieuIndex = null;
        $this->selectedPhieu = [];
        $this->chiTietFiltered = [];
    }

    public function deleteInvoice(string $sttRec): void
    {
        DB::beginTransaction();

        try {
            $result = AsPODeletePO3::call([
                'pMa_cty' => (string) \CatalogService::company()->id,
                'pStt_rec' => $sttRec,
            ]);
            $row = $result->first();
            $pRet = \is_array($row) ? ($row['pRet'] ?? null) : ($row->pRet ?? null);

            if (null !== $pRet && 0 !== (int) $pRet) {
                throw new \RuntimeException('Stored procedure trả về mã lỗi ' . (int) $pRet . '.');
            }

            $this->phieuRows = array_values(array_filter(
                $this->phieuRows,
                static fn (array $phieu): bool => (string) ($phieu['stt_rec'] ?? '') !== $sttRec
            ));
            $this->chiTietRows = array_values(array_filter(
                $this->chiTietRows,
                static fn (array $row): bool => (string) ($row['stt_rec'] ?? '') !== $sttRec
            ));
            $this->clearSelection();

            DB::commit();
            session()->flash('success', 'Đã xóa hóa đơn mua hàng.');
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            session()->flash('error', 'Lỗi khi xóa hóa đơn: ' . $exception->getMessage());
        }
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $rows = $this->phieuRows;
        $filename = 'po3-hoa-don-mua-hang-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Số CT', 'Số HĐ', 'Ngày CT', 'Ngày HĐ', 'Mã NCC', 'Diễn giải', 'Tổng tiền']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    (string) ($row['so_ct'] ?? ''),
                    (string) ($row['so_hd'] ?? ''),
                    (string) ($row['ngay_ct'] ?? ''),
                    (string) ($row['ngay_hd'] ?? ''),
                    (string) ($row['ma_kh'] ?? ''),
                    (string) ($row['dien_giai'] ?? ''),
                    (string) ($row['t_tt'] ?? 0),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        return view('catalog::po.vch.povchpo3', [
            'phieuRows' => $this->phieuRows,
            'chiTietFiltered' => $this->chiTietFiltered,
            'selectedPhieuIndex' => $this->selectedPhieuIndex,
            'selectedPhieu' => $this->selectedPhieu,
        ])->layout('catalog::layouts.app');
    }

    /**
     * @return array<string, mixed>
     */
    private static function normalizePoDetail(mixed $row): array
    {
        $row = \is_array($row) ? $row : (array) $row;

        return [
            'stt_rec' => (string) ($row['stt_rec'] ?? ''),
            'stt_rec0' => (string) ($row['stt_rec0'] ?? ''),
            'ma_vt' => (string) ($row['ma_vt'] ?? ''),
            'ten_vt' => (string) ($row['ten_vt'] ?? ''),
            'dvt' => (string) ($row['dvt'] ?? ''),
            'ma_kho' => (string) ($row['ma_kho'] ?? ''),
            'so_luong' => (float) ($row['so_luong'] ?? 0),
            'gia_nt0' => (float) ($row['gia_nt0'] ?? 0),
            'tien_nt0' => (float) ($row['tien_nt0'] ?? 0),
            'thue_gtgt_nt' => (float) ($row['thue_gtgt_nt'] ?? 0),
            'tt_nt' => (float) ($row['tt_nt'] ?? 0),
        ];
    }
}
