<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-01 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Simba\StoredProcedures\AsSIGetDmSo_ct;
use Diepxuan\Simba\StoredProcedures\AsSORptBK01;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * SO - Bang ke chung tu ban hang (SORptBK01).
 *
 * Mapping:
 * - menuID  : `06.20.02` (sysMenu.moduleid = SO; command = frmSORptBK01).
 * - DLL     : `SORptBK01.dll` (frmSORptBK01, frmReport2DGV).
 * - SP      : `asSORptBK01` (sysReportInfo spname; DataSet: ct then ph).
 * - Route   : `_simba-source/so/rpt/sorptbk01` + `so/rpt/sorptbk01062002`.
 *
 * Task 201 — path theo route, reference pattern task 008/359/370.
 */
class Sorptbk01 extends Component
{
    public ?string $module = null;
    public ?string $kind   = null;
    public ?string $slug   = null;

    public ?string $pNgay1    = null;
    public ?string $pNgay2    = null;
    public ?string $pMa_ct    = '';
    public ?string $pTrang_thai = '';
    public ?string $pSoct1    = '';
    public ?string $pSoct2    = '';
    public ?string $pMa_kh    = '';
    public ?string $pMa_nhkh  = '';
    public ?string $pMa_plkh1 = '';
    public ?string $pMa_plkh2 = '';
    public ?string $pMa_plkh3 = '';
    public ?string $pMa_hd    = '';
    public ?string $pMa_nhhd  = '';
    public ?string $pMa_vt    = '';
    public ?string $pMa_nhvt  = '';
    public ?string $pMa_plvt1 = '';
    public ?string $pMa_plvt2 = '';
    public ?string $pMa_plvt3 = '';
    public ?string $pMa_kho   = '';
    public ?string $pMa_vitri = '';
    public ?string $pMa_lo    = '';
    public ?string $pMa_httt  = '';
    public ?string $pMa_dktt  = '';
    public ?string $pMa_bp    = '';
    public ?string $pMa_nvkd  = '';
    public ?string $pMa_spct  = '';
    public ?string $pMa_nt    = '';
    public ?string $pTieu_de  = 'Bảng kê chứng từ bán hàng';
    public ?string $errorMessage = null;

    /** @var list<array<string,mixed>> */
    public array $phieuRows = [];

    /** @var list<array<string,mixed>> */
    public array $chiTietRows = [];

    /** @var list<array<string,mixed>> */
    public array $chiTietFiltered = [];

    /** @var list<array{ma_ct:string,ten_ct:string}> */
    public array $voucherTypes = [];

    public ?int $selectedPhieuIndex = null;

    /** @var array<string,mixed> */
    public array $selectedPhieu = [];

    public function mount(
        ?string $module = null,
        ?string $kind = null,
        ?string $slug = null
    ): void {
        $this->module = $module;
        $this->kind   = $kind;
        $this->slug   = $slug;
        $this->syncTimer();
        $this->pMa_ct       ??= '';
        $this->pTrang_thai  ??= '';
        $this->pSoct1       ??= '';
        $this->pSoct2       ??= '';
        $this->pMa_nt       ??= '';
        $this->pTieu_de     ??= 'Bảng kê chứng từ bán hàng';
        $this->voucherTypes  = $this->loadVoucherTypes();
        $this->clearData();
    }

    public function submit(): void
    {
        $this->syncTimer();

        $this->validate([
            'pNgay1'     => ['required', 'date'],
            'pNgay2'     => ['required', 'date', 'after_or_equal:pNgay1'],
            'pMa_ct'     => ['nullable', 'string'],
            'pTrang_thai' => ['nullable', 'string'],
            'pSoct1'     => ['nullable', 'string'],
            'pSoct2'     => ['nullable', 'string'],
            'pMa_kh'     => ['nullable', 'string'],
            'pMa_nhkh'   => ['nullable', 'string'],
            'pMa_plkh1'  => ['nullable', 'string'],
            'pMa_plkh2'  => ['nullable', 'string'],
            'pMa_plkh3'  => ['nullable', 'string'],
            'pMa_hd'     => ['nullable', 'string'],
            'pMa_nhhd'   => ['nullable', 'string'],
            'pMa_vt'     => ['nullable', 'string'],
            'pMa_nhvt'   => ['nullable', 'string'],
            'pMa_plvt1'  => ['nullable', 'string'],
            'pMa_plvt2'  => ['nullable', 'string'],
            'pMa_plvt3'  => ['nullable', 'string'],
            'pMa_kho'    => ['nullable', 'string'],
            'pMa_vitri'  => ['nullable', 'string'],
            'pMa_lo'     => ['nullable', 'string'],
            'pMa_httt'   => ['nullable', 'string'],
            'pMa_dktt'   => ['nullable', 'string'],
            'pMa_bp'     => ['nullable', 'string'],
            'pMa_nvkd'   => ['nullable', 'string'],
            'pMa_spct'   => ['nullable', 'string'],
            'pMa_nt'     => ['nullable', 'string'],
        ]);

        $this->errorMessage = null;

        try {
            $data = AsSORptBK01::callWithDataSets($this->procedurePayload());

            $this->phieuRows = $data['ph']
                ->map(fn (mixed $row): array => self::rowToArray($row))
                ->values()
                ->all();
            $this->chiTietRows = $data['ct']
                ->map(fn (mixed $row): array => self::rowToArray($row))
                ->values()
                ->all();
            $this->clearSelectedPhieu();

            if ([] !== $this->phieuRows) {
                $this->selectPhieu(0);
            }

            $this->dispatch('switch-tab', 'content');
        } catch (\Throwable $exception) {
            report($exception);
            $this->clearData();
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP asSORptBK01.';

            return;
        }
    }

    public function selectPhieu(int $index): void
    {
        if (!isset($this->phieuRows[$index])) {
            $this->clearSelectedPhieu();

            return;
        }

        $this->selectedPhieuIndex = $index;
        $this->selectedPhieu      = $this->phieuRows[$index];
        $sttRec = self::rowValue($this->selectedPhieu, ['stt_rec', 'Stt_rec', 'STT_REC']);

        $this->chiTietFiltered = array_values(array_filter(
            $this->chiTietRows,
            static fn (array $row): bool => (string) self::rowValue($row, ['stt_rec', 'Stt_rec', 'STT_REC']) === (string) $sttRec
        ));
    }

    public function clearSelectedPhieu(): void
    {
        $this->selectedPhieuIndex = null;
        $this->selectedPhieu      = [];
        $this->chiTietFiltered    = [];
    }

    public function exportCsv(): ?StreamedResponse
    {
        if ([] === $this->phieuRows) {
            $this->errorMessage = 'Chưa có dữ liệu để xuất.';

            return null;
        }

        $rows    = $this->csvRows();
        $columns = array_keys($rows[0] ?? []);
        $filename = 'sorptbk01-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows, $columns): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);
            foreach ($rows as $row) {
                fputcsv($handle, array_map(
                    static fn (string $column): string => (string) ($row[$column] ?? ''),
                    $columns
                ));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return list<array{key:string,label:string,class:string}>
     */
    public function phieuColumns(): array
    {
        $currency = $this->isForeignCurrency() ? 'NT' : 'VND';

        return [
            ['key' => 'ngay_ct', 'label' => 'Ngày CT', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'so_ct', 'label' => 'Số CT', 'class' => 'text-left whitespace-nowrap font-mono'],
            ['key' => 'ma_kh', 'label' => 'Mã KH', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ten_kh', 'label' => 'Khách hàng', 'class' => 'text-left'],
            ['key' => 't_tien', 'label' => 'Tiền ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 't_thue', 'label' => 'Thuế ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 't_tt', 'label' => 'Thanh toán ' . $currency, 'class' => 'text-right whitespace-nowrap'],
        ];
    }

    /**
     * @return list<array{key:string,label:string,class:string}>
     */
    public function chiTietColumns(): array
    {
        $currency = $this->isForeignCurrency() ? 'NT' : 'VND';

        return [
            ['key' => 'ma_vt', 'label' => 'Mã VT', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ten_vt', 'label' => 'Tên vật tư', 'class' => 'text-left'],
            ['key' => 'dvt', 'label' => 'DVT', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ma_kho', 'label' => 'Kho', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'so_luong', 'label' => 'Số lượng', 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'gia', 'label' => 'Giá ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'tien', 'label' => 'Tiền ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'thue_gtgt', 'label' => 'Thuế ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'ma_nvkd', 'label' => 'NVKD', 'class' => 'text-left whitespace-nowrap'],
        ];
    }

    public function phieuCellValue(array $row, string $column): string
    {
        return match ($column) {
            'ngay_ct' => self::dateValue(self::rowValue($row, ['ngay_ct', 'Ngay_ct'])),
            'so_ct'   => self::csvValue(self::rowValue($row, ['so_ct', 'So_ct'])),
            'ma_kh'   => self::csvValue(self::rowValue($row, ['ma_kh', 'Ma_kh'])),
            'ten_kh'  => self::csvValue(self::rowValue($row, ['ten_kh', 'Ten_kh'])),
            't_tien'  => $this->moneyValue($row, 't_tien'),
            't_thue'  => $this->moneyValue($row, 't_thue'),
            't_tt'    => $this->moneyValue($row, 't_tt'),
            default   => self::csvValue(self::rowValue($row, [$column])),
        };
    }

    public function chiTietCellValue(array $row, string $column): string
    {
        return match ($column) {
            'ma_vt'     => self::csvValue(self::rowValue($row, ['ma_vt', 'Ma_vt'])),
            'ten_vt'    => self::csvValue(self::rowValue($row, ['ten_vt', 'Ten_vt'])),
            'dvt'       => self::csvValue(self::rowValue($row, ['dvt', 'Dvt'])),
            'ma_kho'    => self::csvValue(self::rowValue($row, ['ma_kho', 'Ma_kho'])),
            'so_luong'  => self::numberValue(self::rowValue($row, ['so_luong', 'So_luong']), 4),
            'gia'       => $this->moneyValue($row, 'gia'),
            'tien'      => $this->moneyValue($row, 'tien'),
            'thue_gtgt' => $this->moneyValue($row, 'thue_gtgt'),
            'ma_nvkd'   => self::csvValue(self::rowValue($row, ['ma_nvkd', 'Ma_nvkd'])),
            default     => self::csvValue(self::rowValue($row, [$column])),
        };
    }

    public function displayValue(mixed $value): string
    {
        return self::csvValue($value);
    }

    public static function csvValue(mixed $value): string
    {
        $stringValue = match (\gettype($value)) {
            'boolean' => $value ? '1' : '0',
            'NULL'    => '',
            'array'   => '',
            'object'  => method_exists($value, '__toString') ? (string) $value : '',
            default   => (string) $value,
        };

        return (string) normalize_to_utf8($stringValue);
    }

    public function render(): View
    {
        return view('catalog::so.rpt.sorptbk01', [
            'phieuColumns' => $this->phieuColumns(),
            'chiTietColumns' => $this->chiTietColumns(),
        ]);
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty'   => $this->companyId(),
            'pNgay1'    => $this->pNgay1,
            'pNgay2'    => $this->pNgay2,
            'pMa_ct'    => $this->nullableString($this->pMa_ct),
            'pTrang_thai' => $this->nullableString($this->pTrang_thai),
            'pMa_kh'    => $this->nullableString($this->pMa_kh),
            'pMa_nhkh'  => $this->nullableString($this->pMa_nhkh),
            'pMa_plkh1' => $this->nullableString($this->pMa_plkh1),
            'pMa_plkh2' => $this->nullableString($this->pMa_plkh2),
            'pMa_plkh3' => $this->nullableString($this->pMa_plkh3),
            'pMa_hd'    => $this->nullableString($this->pMa_hd),
            'pMa_nhhd'  => $this->nullableString($this->pMa_nhhd),
            'pMa_vt'    => $this->nullableString($this->pMa_vt),
            'pMa_nhvt'  => $this->nullableString($this->pMa_nhvt),
            'pMa_plvt1' => $this->nullableString($this->pMa_plvt1),
            'pMa_plvt2' => $this->nullableString($this->pMa_plvt2),
            'pMa_plvt3' => $this->nullableString($this->pMa_plvt3),
            'pMa_kho'   => $this->nullableString($this->pMa_kho),
            'pMa_vitri' => $this->nullableString($this->pMa_vitri),
            'pMa_lo'    => $this->nullableString($this->pMa_lo),
            'pMa_httt'  => $this->nullableString($this->pMa_httt),
            'pMa_TT'    => $this->nullableString($this->pMa_dktt),
            'pMa_bp'    => $this->nullableString($this->pMa_bp),
            'pMa_nvkd'  => $this->nullableString($this->pMa_nvkd),
            'pMa_spct'  => $this->nullableString($this->pMa_spct),
            'pMa_nt'    => $this->nullableString($this->pMa_nt),
            'pSoct1'    => $this->nullableString($this->pSoct1 ?? ''),
            'pSoct2'    => $this->nullableString($this->pSoct2 ?? ''),
        ];
    }

    /**
     * @return list<array<string,mixed>>
     */
    private function csvRows(): array
    {
        $rows = [];

        foreach ($this->phieuRows as $phieu) {
            $rows[] = [
                'Loai'         => 'Phiếu',
                'Ngay CT'      => self::dateValue(self::rowValue($phieu, ['ngay_ct', 'Ngay_ct'])),
                'So CT'        => self::csvValue(self::rowValue($phieu, ['so_ct', 'So_ct'])),
                'Khach hang'   => self::csvValue(self::rowValue($phieu, ['ten_kh', 'Ten_kh'])),
                'Tien'         => $this->moneyValue($phieu, 't_tien'),
                'Thue'         => $this->moneyValue($phieu, 't_thue'),
                'Thanh toan'   => $this->moneyValue($phieu, 't_tt'),
            ];
        }

        foreach ($this->chiTietRows as $chiTiet) {
            $rows[] = [
                'Loai'         => 'Chi tiết',
                'Ngay CT'      => '',
                'So CT'        => self::csvValue(self::rowValue($chiTiet, ['stt_rec', 'Stt_rec'])),
                'Khach hang'   => self::csvValue(self::rowValue($chiTiet, ['ten_vt', 'Ten_vt'])),
                'Tien'         => $this->moneyValue($chiTiet, 'tien'),
                'Thue'         => $this->moneyValue($chiTiet, 'thue_gtgt'),
                'Thanh toan'   => '',
            ];
        }

        return $rows;
    }

    private function moneyValue(array $row, string $column): string
    {
        $keys = match ($column) {
            't_tien' => $this->isForeignCurrency()
                ? ['t_tien_nt', 't_tien_nt2', 't_tien', 't_tien2']
                : ['t_tien', 't_tien2', 't_tien_nt', 't_tien_nt2'],
            't_thue' => $this->isForeignCurrency()
                ? ['t_thue_nt', 't_thue', 'thue_gtgt_nt']
                : ['t_thue', 't_thue_nt', 'thue_gtgt'],
            't_tt' => $this->isForeignCurrency()
                ? ['t_tt_nt', 't_tt', 't_tt_nt2']
                : ['t_tt', 't_tt_nt', 't_tt_nt2'],
            'gia' => $this->isForeignCurrency()
                ? ['gia_nt', 'gia_nt2', 'gia', 'gia2']
                : ['gia', 'gia2', 'gia_nt', 'gia_nt2'],
            'tien' => $this->isForeignCurrency()
                ? ['tien_nt', 'tien_nt2', 'tien', 'tien2']
                : ['tien', 'tien2', 'tien_nt', 'tien_nt2'],
            'thue_gtgt' => $this->isForeignCurrency()
                ? ['thue_gtgt_nt', 'thue_gtgt', 't_thue_nt', 't_thue']
                : ['thue_gtgt', 't_thue', 'thue_gtgt_nt', 't_thue_nt'],
            default => [$column],
        };

        return self::numberValue(self::rowValue($row, $keys), $this->isForeignCurrency() ? 4 : 0);
    }

    private function nullableString(?string $value): string
    {
        return trim((string) ($value ?? ''));
    }

    private function companyId(): string
    {
        try {
            return (string) (\CatalogService::company()->id ?? '001');
        } catch (\Throwable) {
            return '001';
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
     * @return list<array{ma_ct:string,ten_ct:string}>
     */
    private function loadVoucherTypes(): array
    {
        try {
            $rows = AsSIGetDmSo_ct::call([
                'pMa_cty'    => $this->companyId(),
                'pPhan_he'   => 'SO',
                'pStruct'    => '0',
                'pLanguageId' => $this->languageName(),
            ]);
        } catch (\Throwable) {
            return [];
        }

        return $rows
            ->map(static function (mixed $row): array {
                $data = \is_array($row) ? $row : (array) $row;

                return [
                    'ma_ct'  => (string) ($data['MA_CT'] ?? $data['ma_ct'] ?? ''),
                    'ten_ct' => (string) ($data['TEN_CT'] ?? $data['ten_ct'] ?? ''),
                ];
            })
            ->filter(static fn (array $item): bool => '' !== $item['ma_ct'])
            ->values()
            ->all();
    }

    private function clearData(): void
    {
        $this->phieuRows = [];
        $this->chiTietRows = [];
        $this->clearSelectedPhieu();
    }

    /**
     * @return array<string,mixed>
     */
    private static function rowToArray(mixed $row): array
    {
        if (\is_array($row)) {
            return $row;
        }

        if ($row instanceof \JsonSerializable) {
            $data = $row->jsonSerialize();

            return \is_array($data) ? $data : [];
        }

        if ($row instanceof \stdClass) {
            return get_object_vars($row);
        }

        return (array) $row;
    }

    /**
     * @param list<string> $keys
     */
    private static function rowValue(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (\array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        $lowerKeyMap = [];
        foreach (array_keys($row) as $key) {
            $lowerKeyMap[mb_strtolower((string) $key)] = $key;
        }

        foreach ($keys as $key) {
            $actualKey = $lowerKeyMap[mb_strtolower($key)] ?? null;
            if (null !== $actualKey) {
                return $row[$actualKey];
            }
        }

        return null;
    }

    private static function dateValue(mixed $value): string
    {
        if (null === $value || '' === trim((string) $value)) {
            return '';
        }

        try {
            $date = $value instanceof \DateTimeInterface
                ? Carbon::instance($value)
                : Carbon::parse((string) $value);
        } catch (\Throwable) {
            return self::csvValue($value);
        }

        if ($date->isSameDay(Carbon::create(1_900, 1, 1))) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    private static function numberValue(mixed $value, int $maxDecimals): string
    {
        if (null === $value || '' === trim((string) $value) || !is_numeric($value)) {
            return '';
        }

        $number = (float) $value;
        if (0.0 === $number) {
            return '';
        }

        $formatted = number_format($number, $maxDecimals, '.', ',');

        return false === str_contains($formatted, '.')
            ? $formatted
            : rtrim(rtrim($formatted, '0'), '.');
    }

    private function isForeignCurrency(): bool
    {
        $currency = mb_strtoupper(trim((string) $this->pMa_nt));

        return '' !== $currency && 'VND' !== $currency;
    }

    private function syncTimer(): void
    {
        $timer = \CatalogService::timer();

        $this->pNgay1 = (string) ($timer['from'] ?? now()->startOfMonth()->toDateString());
        $this->pNgay2 = (string) ($timer['to'] ?? now()->toDateString());
    }
}
