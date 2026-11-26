<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTb02;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Báo cáo tài chính chi tiết B02 (BCTCCDT).
 *
 * Port từ GLRptBCTCCDTB02.dll. Gọi SP `asGLRptBCTCCDTb02` qua wrapper
 * `AsGLRptBCTCCDTb02`. Tham số SP (theo simba-docs/procedures/GL/procedures.md):
 *   - pMa_cty  nvarchar(3)     Mã công ty
 *   - Ngay_ct1 smalldatetime   Từ ngày
 *   - Ngay_ct2 smalldatetime   Đến ngày
 *   - pTk       nvarchar(20)   Tài khoản
 */
class Glrptbctccdtb02 extends Component
{
    public ?string $module = null;

    public ?string $kind = null;

    public ?string $slug = null;

    public ?string $pNgay_ct1 = null;

    public ?string $pNgay_ct2 = null;

    public ?string $pTk = '441';

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

        // Mặc định kỳ hiện tại: đầu tháng → hôm nay.
        $this->pNgay_ct1 ??= now()->startOfMonth()->toDateString();
        $this->pNgay_ct2 ??= now()->toDateString();
        $this->rows = [];
    }

    public function submit(): void
    {
        $this->validate([
            'pNgay_ct1' => ['required', 'date'],
            'pNgay_ct2' => ['required', 'date', 'after_or_equal:pNgay_ct1'],
            'pTk' => ['nullable', 'string', 'max:20'],
        ], [
            'pNgay_ct1.required' => 'Phải nhập từ ngày.',
            'pNgay_ct2.required' => 'Phải nhập đến ngày.',
            'pNgay_ct2.after_or_equal' => 'Đến ngày phải lớn hơn hoặc bằng từ ngày.',
        ]);

        $this->errorMessage = null;

        try {
            $rows = AsGLRptBCTCCDTb02::call($this->procedurePayload());
        } catch (\Throwable $exception) {
            report($exception);
            $this->rows = [];
            $this->columns = [];
            $this->clearSelectedRow();
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP asGLRptBCTCCDTb02.';

            return;
        }

        $this->rows = $rows->map(fn (mixed $row): array => $this->rowToArray($row))->values()->all();
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
        $filename = 'glrptbctccdtb02-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows, $columns): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);

            foreach ($rows as $row) {
                fputcsv($handle, array_map(
                    static fn (string $column): string => self::csvValue($row[$column] ?? null),
                    $columns
                ));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtb02', [
            'rows' => $this->rows,
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
            'NULL' => '',
            'array' => '',
            'object' => method_exists($value, '__toString') ? (string) $value : '',
            default => (string) $value,
        };

        return (string) normalize_to_utf8($stringValue);
    }

    public function isNumericColumn(string $column): bool
    {
        $column = mb_strtolower($column);

        if (str_starts_with($column, 'ma_')
            || str_starts_with($column, 'ten_')
            || str_starts_with($column, 'ngay')
            || \in_array($column, ['tk', 'so_ct', 'stt_rec', 'dien_giai', 'tk_du'], true)
        ) {
            return false;
        }

        return str_contains($column, 'tien')
            || str_contains($column, 'amount')
            || str_contains($column, 'balance')
            || str_starts_with($column, 'ps_')
            || str_starts_with($column, 'du_')
            || str_starts_with($column, 'no_')
            || str_starts_with($column, 'co_')
            || str_ends_with($column, '_nt');
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'Ngay_ct1' => $this->pNgay_ct1,
            'Ngay_ct2' => $this->pNgay_ct2,
            'pTk' => trim((string) $this->pTk),
        ];
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
}
