<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-05 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Simba\StoredProcedures\AsINRptCD02;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Inrptcd02 extends Component
{
    public ?string $module = null;
    public ?string $kind = null;
    public ?string $slug = null;

    public ?string $pMa_vt = null;
    public ?string $pMa_kho = null;
    public ?string $pMa_vitri = null;
    public ?string $pTk_vt = '1561';
    public ?string $pMa_nhvt = null;
    public ?string $pNgay = null;
    public ?string $pDVT = '0';
    public bool $pNgoai_te = false;
    public bool $pDk_Ck = true;
    public ?string $pMa_lo = null;
    public bool $pQuaToiThieu = false;
    public bool $pQuaToiDa = false;
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
        $this->pNgay ??= now()->toDateString();
        $this->rows = [];
    }

    public function submit(): void
    {
        $this->validate([
            'pMa_vt'       => ['nullable', 'string'],
            'pMa_kho'      => ['nullable', 'string'],
            'pMa_vitri'    => ['nullable', 'string'],
            'pTk_vt'       => ['nullable', 'string'],
            'pMa_nhvt'     => ['nullable', 'string'],
            'pNgay'        => ['required', 'date'],
            'pDVT'         => ['required', 'in:0,1,2'],
            'pNgoai_te'    => ['boolean'],
            'pDk_Ck'       => ['boolean'],
            'pMa_lo'       => ['nullable', 'string'],
            'pQuaToiThieu' => ['boolean'],
            'pQuaToiDa'    => ['boolean'],
        ]);

        $this->errorMessage = null;

        try {
            $rows = AsINRptCD02::call($this->procedurePayload());
        } catch (\Throwable $exception) {
            report($exception);
            $this->rows = [];
            $this->columns = [];
            $this->clearSelectedRow();
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP asINRptCD02.';

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
        $filename = 'inrptcd02-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows, $columns): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);

            foreach ($rows as $row) {
                fputcsv($handle, array_map(
                    static fn (string $column): string => Inrptcd02::csvValue($row[$column] ?? null),
                    $columns
                ));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptcd02', [
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
            || \in_array($column, ['dvt', 'tk', 'tk_vt', 'so_ct', 'stt_rec', 'dien_giai'], true)
        ) {
            return false;
        }

        if (\in_array($column, ['iteration', 'so_luong', 'sl_ton', 'tien_fm'], true)) {
            return true;
        }

        return str_contains($column, 'tien')
            || str_contains($column, 'amount')
            || str_contains($column, 'balance')
            || str_starts_with($column, 'sl_')
            || str_starts_with($column, 'ps_')
            || str_starts_with($column, 'du_')
            || str_starts_with($column, 't_')
            || str_ends_with($column, '_nt');
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty'       => (string) \CatalogService::company()->id,
            'pMa_vt'        => trim((string) $this->pMa_vt),
            'pMa_kho'       => trim((string) $this->pMa_kho),
            'pMa_vitri'     => trim((string) $this->pMa_vitri),
            'pTk_vt'        => trim((string) $this->pTk_vt),
            'pMa_nhvt'      => trim((string) $this->pMa_nhvt),
            'pNgay'         => $this->pNgay,
            'pDVT'          => $this->pDVT,
            'pNgoai_te'     => $this->pNgoai_te ? '1' : '0',
            'pDk_Ck'        => $this->pDk_Ck,
            'pMa_lo'        => trim((string) $this->pMa_lo),
            'pQuaToiThieu'  => $this->pQuaToiThieu,
            'pQuaToiDa'     => $this->pQuaToiDa,
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
