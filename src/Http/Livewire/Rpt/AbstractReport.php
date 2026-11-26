<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Catalog\Http\Livewire\Rpt;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Base class cho tất cả màn BÁO CÁO của SimbaERP.
 *
 * Mục đích: mỗi báo cáo mới chỉ cần khai báo
 *   1. $sp (tên SP wrapper, vd AsGLRptBCT01::class)
 *   2. khai báo property lọc + procedurePayload()
 *   3. view tương ứng
 *
 * Toàn bộ phần dùng chung (rows/columns/selectRow/exportCsv/format) nằm ở đây,
 * tránh copy-paste ~200 dòng cho mỗi báo cáo (53 báo cáo Hạng A còn lại).
 *
 * KHÔNG đặt route trong base này — route do convention auto-registration
 * (xem diepxuan/laravel-catalog/routes/web.php) hoặc khai báo tường minh.
 */
abstract class AbstractReport extends Component
{
    public ?string $module = null;

    public ?string $kind = null;

    public ?string $slug = null;

    public ?string $errorMessage = null;

    /** @var list<array<string,mixed>> */
    public array $rows = [];

    /** @var list<string> */
    public array $columns = [];

    public ?int $selectedRowIndex = null;

    /** @var array<string,mixed> */
    public array $selectedRow = [];

    /**
     * Tên class SP wrapper, vd: AsGLRptBCT01::class.
     *
     * @return class-string
     */
    abstract protected function spClass(): string;

    /**
     * Payload gửi SP — tên tham số đúng theo wrapper.
     *
     * @return array<string,mixed>
     */
    abstract public function procedurePayload(): array;

    /**
     * Tên file CSV (không gồm phần mở rộng).
     */
    protected function csvBasename(): string
    {
        $parts = explode('\\', static::class);

        return strtolower((string) end($parts));
    }

    public function mount(?string $module = null, ?string $kind = null, ?string $slug = null): void
    {
        $this->module = $module;
        $this->kind = $kind;
        $this->slug = $slug;

        $this->initialiseFilters();
        $this->rows = [];
    }

    /**
     * Gán giá trị mặc định cho các property lọc (ngày, tài khoản...).
     * Override nếu báo cáo cần mặc định riêng.
     */
    protected function initialiseFilters(): void
    {
        if (property_exists($this, 'pNgay1') && null === $this->pNgay1) {
            $this->pNgay1 = now()->startOfMonth()->toDateString();
        }

        if (property_exists($this, 'pNgay2') && null === $this->pNgay2) {
            $this->pNgay2 = now()->toDateString();
        }

        if (property_exists($this, 'pNgay_Ct1') && null === $this->pNgay_Ct1) {
            $this->pNgay_Ct1 = now()->startOfMonth()->toDateString();
        }

        if (property_exists($this, 'pNgay_Ct2') && null === $this->pNgay_Ct2) {
            $this->pNgay_Ct2 = now()->toDateString();
        }
    }

    public function submit(): void
    {
        $this->validateReport();
        $this->errorMessage = null;

        try {
            $sp = $this->spClass();
            $rows = $sp::call($this->procedurePayload());
        } catch (\Throwable $exception) {
            report($exception);
            $this->rows = [];
            $this->columns = [];
            $this->clearSelectedRow();
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP ' . $this->spShortName() . '.';

            return;
        }

        $this->rows = $rows->map(fn (mixed $row): array => $this->rowToArray($row))->values()->all();
        $this->columns = [] !== $this->rows ? array_keys($this->rows[0]) : [];
        $this->clearSelectedRow();
        $this->dispatch('switch-tab', 'content');
    }

    /**
     * Validate điều kiện lọc. Override nếu cần rule riêng.
     */
    protected function validateReport(): void
    {
        $rules = [];

        foreach (['pNgay1', 'pNgay2', 'pNgay_Ct1', 'pNgay_Ct2'] as $prop) {
            if (property_exists($this, $prop)) {
                $rules[$prop] = ['required', 'date'];
            }
        }

        if ([] !== $rules) {
            $this->validate($rules);
        }
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
        $filename = $this->csvBasename() . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(static function () use ($rows, $columns): void {
            $handle = fopen('php://output', 'wb');
            if (false === $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);

            foreach ($rows as $row) {
                fputcsv($handle, array_map(
                    static fn (string $column): string => AbstractReport::csvValue($row[$column] ?? null),
                    $columns
                ));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
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

        return function_exists('normalize_to_utf8')
            ? (string) normalize_to_utf8($stringValue)
            : $stringValue;
    }

    public function isNumericColumn(string $column): bool
    {
        $column = mb_strtolower($column);

        if (str_starts_with($column, 'ma_')
            || str_starts_with($column, 'ten_')
            || str_starts_with($column, 'ngay')
            || \in_array($column, ['tk', 'tk_du', 'so_ct', 'stt_rec', 'dien_giai', 'chi_tieu', 'tk_me'], true)
        ) {
            return false;
        }

        return str_contains($column, 'tien')
            || str_contains($column, 'amount')
            || str_starts_with($column, 'ps_')
            || str_starts_with($column, 'du_')
            || str_starts_with($column, 'no_')
            || str_starts_with($column, 'co_')
            || str_starts_with($column, 'lk_')
            || str_ends_with($column, '_nt');
    }

    /**
     * @return array<string,mixed>
     */
    protected function rowToArray(mixed $row): array
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

    protected function spShortName(): string
    {
        $parts = explode('\\', $this->spClass());

        return (string) end($parts);
    }
}
