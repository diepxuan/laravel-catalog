<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-30 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Simba\StoredProcedures\AsARRptBCCN01;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Arrptbccn01 extends Component
{
    public ?string $module = null;
    public ?string $kind = null;
    public ?string $slug = null;

    public ?string $pNgay1 = null;
    public ?string $pNgay2 = null;
    public ?string $pTk = null;
    public ?string $pMa_kh = null;
    public ?string $pMa_nt = '';
    public ?string $pTieu_de = 'Sổ chi tiết công nợ một khách hàng';
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
        $this->kind = $kind;
        $this->slug = $slug;
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
            $rows = AsARRptBCCN01::call([
                'ma_cty' => (string) \CatalogService::company()->id,
                'Ngay1'  => $this->pNgay1,
                'Ngay2'  => $this->pNgay2,
                'Tk'     => trim((string) $this->pTk),
                'ma_kh'  => trim((string) $this->pMa_kh),
                'ma_nt'  => trim((string) $this->pMa_nt),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
            $this->rows = [];
            $this->columns = [];
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP asARRptBCCN01.';

            return;
        }

        $this->rows = $rows->map(fn (mixed $row): array => $this->rowToArray($row))->values()->all();
        $this->columns = [] !== $this->rows ? array_keys($this->rows[0]) : [];
        $this->clearSelectedRow();

        // Chuyển sang tab "Kết quả" sau khi submit() thành công.
        // Khi validation fail, Livewire throw ValidationException và tab giữ nguyên ở "filter"
        // để user nhìn thấy <x-input-error .../> cho từng trường.
        $this->dispatch('switch-tab', 'content');
    }

    public function selectRow(int $index): void
    {
        if (!isset($this->rows[$index])) {
            $this->clearSelectedRow();

            return;
        }

        $this->selectedRowIndex = $index;
        $this->selectedRow = $this->rows[$index];
    }

    public function clearSelectedRow(): void
    {
        $this->selectedRowIndex = null;
        $this->selectedRow = [];
    }

    public function exportCsv(): ?StreamedResponse
    {
        if ([] === $this->rows || [] === $this->columns) {
            $this->errorMessage = 'Chưa có dữ liệu để xuất.';

            return null;
        }

        $rows = $this->rows;
        $columns = $this->columns;
        $filename = 'arrptbccn01-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows, $columns): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);

            foreach ($rows as $row) {
                fputcsv($handle, array_map(
                    static fn (string $column): string => Arrptbccn01::csvValue($row[$column] ?? null),
                    $columns
                ));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        return view('catalog::so.rpt.arrptbccn01', [
            'rows'    => $this->rows,
            'columns' => $this->columns,
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

        return (string) normalize_to_utf8($stringValue);
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

    private function syncTimer(): void
    {
        $timer = \CatalogService::timer();

        $this->pNgay1 = (string) ($timer['from'] ?? now()->startOfMonth()->toDateString());
        $this->pNgay2 = (string) ($timer['to'] ?? now()->toDateString());
    }
}
