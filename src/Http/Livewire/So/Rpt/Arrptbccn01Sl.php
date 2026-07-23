<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-23 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Simba\StoredProcedures\AsARRptBCCN01SL;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * SO - Sổ chi tiết công nợ một khách hàng - có số lượng.
 *
 * Mapping:
 * - menuID  : `06.30.38` (sysMenu.moduleid = SO; command = frmARRptBCCN01).
 * - DLL     : `ARRptBCCN01.dll` (form goc chia se giua AR, AP, SO).
 * - SP      : `asARRptBCCN01SL` (report `ARBCCN012.rpt`).
 * - Route   : `_simba-source/so/rpt/arrptbccn01063038` (slug compact theo task 358).
 *
 * Task 370 — Ke thua pattern tu Task 359 (Po\Rpt\Arrptbccn01Sl).
 * Khac biet chinh: module SO, mode khachhang, title.
 */
class Arrptbccn01Sl extends Component
{
    public ?string $module = null;
    public ?string $kind   = null;
    public ?string $slug   = null;

    public ?string $pNgay1       = null;
    public ?string $pNgay2       = null;
    public ?string $pTk          = '131';
    public ?string $pMa_kh       = null;
    public ?string $pMa_nt       = '';
    public ?string $pTieu_de     = 'Sổ chi tiết công nợ một khách hàng - có số lượng';
    public ?string $errorMessage = null;

    /** @var list<array<string,mixed>> */
    public array $rows = [];

    /** @var list<string> */
    public array $columns = [];

    public ?int $selectedRowIndex = null;

    /** @var array<string,mixed> */
    public array $selectedRow = [];

    public function mount(?string $module = null, ?string $kind = null, ?string $slug = null): void
    {
        $this->module = $module;
        $this->kind   = $kind;
        $this->slug   = $slug;
        $this->syncTimer();
        $this->pMa_nt ??= '';
        $this->rows = [];
    }

    public function submit(): void
    {
        $this->syncTimer();

        $this->validate([
            'pNgay1' => ['required', 'date'],
            'pNgay2' => ['required', 'date', 'after_or_equal:pNgay1'],
            'pTk'    => ['required', 'string'],
            'pMa_kh' => ['required', 'string'],
            'pMa_nt' => ['nullable', 'string'],
        ]);

        $this->errorMessage = null;

        try {
            $rows = AsARRptBCCN01SL::call([
                'ma_cty' => (string) \CatalogService::company()->id,
                'Ngay1'  => $this->pNgay1,
                'Ngay2'  => $this->pNgay2,
                'Tk'     => trim((string) $this->pTk),
                'ma_kh'  => trim((string) $this->pMa_kh),
                'ma_nt'  => trim((string) $this->pMa_nt),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
            $this->rows         = [];
            $this->columns      = [];
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP asARRptBCCN01SL.';

            return;
        }

        $this->rows    = $rows->map(fn (mixed $row): array => $this->rowToArray($row))->values()->all();
        $this->columns = [] !== $this->rows ? array_keys($this->rows[0]) : [];
        $this->clearSelectedRow();

        $this->dispatch('switch-tab', 'content');
    }

    public function selectRow(int $index): void
    {
        if (!isset($this->rows[$index])) {
            $this->clearSelectedRow();

            return;
        }

        $this->selectedRowIndex = $index;
        $this->selectedRow      = $this->rows[$index];
    }

    public function clearSelectedRow(): void
    {
        $this->selectedRowIndex = null;
        $this->selectedRow      = [];
    }

    public function exportCsv(): ?StreamedResponse
    {
        $presentationColumns = $this->presentationColumns();

        if ([] === $this->rows || [] === $presentationColumns) {
            $this->errorMessage = 'Chưa có dữ liệu để xuất.';

            return null;
        }

        $rows            = $this->rows;
        $columns         = $presentationColumns;
        $foreignCurrency = $this->isForeignCurrency();
        $filename        = 'arrptbccn01sl-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows, $columns, $foreignCurrency): void {
            $handle = fopen('php://output', 'w');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, array_column($columns, 'label'));

            foreach ($rows as $row) {
                fputcsv($handle, array_map(
                    static fn (array $column): string => Arrptbccn01Sl::csvValueForColumn($row, $column['key'], $foreignCurrency),
                    $columns
                ));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        return view('catalog::so.rpt.arrptbccn01sl', [
            'rows'                => $this->rows,
            'presentationColumns' => $this->presentationColumns(),
        ]);
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

        return self::reportLabel((string) normalize_to_utf8($stringValue));
    }

    public static function reportLabel(string $value): string
    {
        return match ($value) {
            '#ARRptBCCN_DDK' => 'Dư đầu kỳ',
            '#ARRptBCCN_TPS' => 'Tổng phát sinh trong kỳ',
            '#ARRptBCCN_DCK' => 'Dư cuối kỳ',
            default          => $value,
        };
    }

    /**
     * @return list<array{key:string,label:string,headerClass:string,cellClass:string}>
     */
    public function presentationColumns(): array
    {
        if ([] === $this->rows) {
            return [];
        }

        $columns = [
            [
                'key'         => 'document',
                'label'       => 'Chứng từ',
                'headerClass' => 'min-w-[150px] text-left',
                'cellClass'   => 'align-top text-left',
            ],
            [
                'key'         => 'dien_giai',
                'label'       => 'Diễn giải',
                'headerClass' => 'min-w-[280px] text-left',
                'cellClass'   => 'align-top text-left ' . $this->rowLineClassForColumn(),
            ],
        ];

        foreach ([
            'so_luong' => ['Số lượng', ['so_luong', 't_so_luong', 'sl_ton', 'sl']],
            'gia'      => ['Giá', $this->moneyKeys('gia')],
            'tien'     => ['Tiền', $this->moneyKeys('tien')],
            'ps_no'    => ['PS Nợ', $this->moneyKeys('ps_no')],
            'ps_co'    => ['PS Có', $this->moneyKeys('ps_co')],
        ] as $key => [$label, $sourceKeys]) {
            if ($this->hasAnyRowValue($sourceKeys)) {
                $columns[] = [
                    'key'         => $key,
                    'label'       => $label,
                    'headerClass' => 'min-w-[104px] text-right',
                    'cellClass'   => 'align-top text-right font-mono tabular-nums',
                ];
            }
        }

        return $columns;
    }

    public function documentSoCt(array $row): string
    {
        return self::csvValue(self::rowValue($row, ['so_ct', 'So_ct']));
    }

    public function documentDate(array $row): string
    {
        return self::dateValue(self::rowValue($row, ['ngay_ct', 'Ngay_ct']));
    }

    public function documentSttRec(array $row): string
    {
        return self::csvValue(self::rowValue($row, ['stt_rec', 'Stt_rec']));
    }

    public function itemCode(array $row): string
    {
        return self::csvValue(self::rowValue($row, ['ma_vt', 'Ma_vt']));
    }

    public function itemName(array $row): string
    {
        return self::csvValue(self::rowValue($row, ['ten_vt', 'Ten_vt']));
    }

    public function itemUnit(array $row): string
    {
        return self::csvValue(self::rowValue($row, ['dvt', 'Dvt']));
    }

    public function itemSummary(array $row): string
    {
        $itemCode = $this->itemCode($row);
        $itemName = $this->itemName($row);
        $itemUnit = $this->itemUnit($row);

        if ('' === $itemCode && '' === $itemName && '' === $itemUnit) {
            return '';
        }

        $item = trim(implode(' - ', array_filter([$itemCode, $itemName], static fn (string $value): bool => '' !== $value)));

        return '' === $itemUnit ? $item : trim($item . ' (' . $itemUnit . ')');
    }

    public function rowIsProductLine(array $row): bool
    {
        return '' !== trim((string) (data_get($row, 'ma_vt') ?? data_get($row, 'Ma_vt') ?? ''));
    }

    public function rowIsSummaryLine(array $row): bool
    {
        $raw = (string) (data_get($row, 'dien_giai') ?? data_get($row, 'Dien_giai') ?? '');

        if ($this->rowIsProductLine($row)) {
            return false;
        }

        return str_starts_with($raw, '#ARRptBCCN_DDK')
            || str_starts_with($raw, '#ARRptBCCN_TPS')
            || str_starts_with($raw, '#ARRptBCCN_DCK');
    }

    public function rowLineClass(array $row): string
    {
        return $this->rowIsProductLine($row) ? 'ps-4' : '';
    }

    public function rowLineClassForColumn(): string
    {
        return 'transition-colors';
    }

    public function cellValue(array $row, string $column): string
    {
        return self::csvValueForColumn($row, $column, $this->isForeignCurrency());
    }

    public static function csvValueForColumn(array $row, string $column, ?bool $foreignCurrency = null): string
    {
        $foreignCurrency ??= false;

        return match ($column) {
            'document' => trim(implode(' ', array_filter([
                self::dateValue(self::rowValue($row, ['ngay_ct', 'Ngay_ct'])),
                self::csvValue(self::rowValue($row, ['so_ct', 'So_ct'])),
                self::csvValue(self::rowValue($row, ['stt_rec', 'Stt_rec'])),
            ], static fn (string $value): bool => '' !== $value))),
            'dien_giai' => self::csvDienGiaiWithIndent($row),
            'so_luong'  => self::numberValue(self::rowValue($row, ['so_luong', 't_so_luong', 'sl_ton', 'sl']), 4),
            'gia'      => self::numberValue(self::rowValue($row, self::staticMoneyKeys('gia', $foreignCurrency)), $foreignCurrency ? 4 : 0),
            'tien'     => self::numberValue(self::rowValue($row, self::staticMoneyKeys('tien', $foreignCurrency)), $foreignCurrency ? 4 : 0),
            'ps_no'    => self::numberValue(self::rowValue($row, self::staticMoneyKeys('ps_no', $foreignCurrency)), $foreignCurrency ? 4 : 0),
            'ps_co'    => self::numberValue(self::rowValue($row, self::staticMoneyKeys('ps_co', $foreignCurrency)), $foreignCurrency ? 4 : 0),
            default     => self::csvValue(self::rowValue($row, [$column])),
        };
    }

    public function isNumericColumn(string $column): bool
    {
        $column = mb_strtolower($column);

        if (str_starts_with($column, 'ma_')
            || str_starts_with($column, 'ten_')
            || str_starts_with($column, 'ngay')
            || \in_array($column, ['tk', 'so_ct', 'stt_rec', 'dien_giai'], true)
        ) {
            return false;
        }

        if (\in_array($column, ['iteration', 'so_luong', 'tien_fm'], true)) {
            return true;
        }

        return str_contains($column, 'tien')
            || str_contains($column, 'amount')
            || str_contains($column, 'balance')
            || str_starts_with($column, 'ps_')
            || str_starts_with($column, 'du_')
            || str_starts_with($column, 't_')
            || str_ends_with($column, '_nt');
    }

    private static function csvDienGiaiWithIndent(array $row): string
    {
        $value = trim(implode(' | ', array_filter([
            self::csvValue(self::rowValue($row, ['dien_giai', 'Dien_giai'])),
            self::csvItemSummary($row),
        ], static fn (string $value): bool => '' !== $value)));

        if ('' === $value) {
            return '';
        }

        $itemCode = self::csvValue(self::rowValue($row, ['ma_vt', 'Ma_vt']));
        if ('' === trim($itemCode)) {
            return $value;
        }

        return "\t" . $value;
    }

    private static function csvItemSummary(array $row): string
    {
        $itemCode = self::csvValue(self::rowValue($row, ['ma_vt', 'Ma_vt']));
        $itemName = self::csvValue(self::rowValue($row, ['ten_vt', 'Ten_vt']));
        $itemUnit = self::csvValue(self::rowValue($row, ['dvt', 'Dvt']));

        if ('' === $itemCode && '' === $itemName && '' === $itemUnit) {
            return '';
        }

        $item = trim(implode(' - ', array_filter([$itemCode, $itemName], static fn (string $value): bool => '' !== $value)));

        return '' === $itemUnit ? $item : trim($item . ' (' . $itemUnit . ')');
    }

    /**
     * @return array<string,mixed>
     */
    private function rowToArray(mixed $row): array
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
    private function hasAnyRowValue(array $keys): bool
    {
        foreach ($this->rows as $row) {
            $value = self::rowValue($row, $keys);

            if (null !== $value && '' !== trim((string) $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private function moneyKeys(string $column): array
    {
        return self::staticMoneyKeys($column, $this->isForeignCurrency());
    }

    /**
     * @return list<string>
     */
    private static function staticMoneyKeys(string $column, bool $foreignCurrency): array
    {
        return match ($column) {
            'gia' => $foreignCurrency
                ? ['gia_nt', 'don_gia_nt', 'gia', 'don_gia']
                : ['gia', 'don_gia', 'gia_nt', 'don_gia_nt'],
            'tien' => $foreignCurrency
                ? ['tien_nt', 't_tien_nt', 'tien', 't_tien']
                : ['tien', 't_tien', 'tien_nt', 't_tien_nt'],
            'ps_no' => $foreignCurrency
                ? ['ps_no_nt', 'du_no_nt', 'ps_no', 'du_no']
                : ['ps_no', 'du_no', 'ps_no_nt', 'du_no_nt'],
            'ps_co' => $foreignCurrency
                ? ['ps_co_nt', 'du_co_nt', 'ps_co', 'du_co']
                : ['ps_co', 'du_co', 'ps_co_nt', 'du_co_nt'],
            default => [$column],
        };
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
