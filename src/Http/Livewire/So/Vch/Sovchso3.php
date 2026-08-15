<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-09
 */

namespace Diepxuan\Catalog\Http\Livewire\So\Vch;

use Diepxuan\Simba\StoredProcedures\AsSoFilt3;
use Diepxuan\Simba\StoredProcedures\AsSODelPH3;
use Diepxuan\Simba\StoredProcedures\AsSOGetCT3;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Danh sách hóa đơn bán hàng (SO3).
 *
 * Mapping:
 * - menuID : `06.10.08` (sysMenu, sysVoucherInfo ma_ct = `SO3`).
 * - DLL    : `SOVchSO3.dll` (form `frmSoVchSO3`).
 * - SP     : `asSoFilt3` (danh sách header + chi tiết) + `asSOGetPH3`/`asSOGetCT3` (mở edit).
 * - Route  : `_simba-source/so/vch/sovchso3`.
 */
class Sovchso3 extends Component
{
    public const MA_CT = 'SO3';

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
        $sets  = AsSoFilt3::callWithDataSets([
            'pKeyPh' => AsSoFilt3::keyPh(
                $maCty,
                self::MA_CT,
                \CatalogService::timerFrom(),
                \CatalogService::timerTo(),
                $this->pMa_kh,
                $this->pSearch
            ),
            'pKeyCt' => AsSoFilt3::keyCt($maCty),
        ]);

        $this->phieuRows = $sets['ph']
            ->map(static fn (mixed $row): array => AsSoFilt3::normalizePh($row))
            ->values()
            ->all();
        $this->chiTietRows = $sets['ct']
            ->map(static fn (mixed $row): array => AsSOGetCT3::normalizeDetail($row))
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
            $result = AsSODelPH3::call([
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
            session()->flash('success', 'Đã xóa hóa đơn bán hàng.');
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            session()->flash('error', 'Lỗi khi xóa hóa đơn: ' . $exception->getMessage());
        }
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $rows = $this->phieuRows;
        $filename = 'so3-hoa-don-ban-hang-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Số CT', 'Số seri', 'Ngày CT', 'Mã KH', 'Tên KH', 'Diễn giải', 'Tổng tiền']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    (string) ($row['so_ct'] ?? ''),
                    (string) ($row['so_seri'] ?? ''),
                    (string) ($row['ngay_ct'] ?? ''),
                    (string) ($row['ma_kh'] ?? ''),
                    (string) ($row['ten_kh'] ?? ''),
                    (string) ($row['dien_giai'] ?? ''),
                    (string) ($row['t_tt'] ?? 0),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        return view('catalog::so.vch.sovchso3', [
            'phieuRows' => $this->phieuRows,
            'chiTietFiltered' => $this->chiTietFiltered,
            'selectedPhieuIndex' => $this->selectedPhieuIndex,
            'selectedPhieu' => $this->selectedPhieu,
        ])->layout('catalog::layouts.app');
    }
}
