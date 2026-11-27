<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Catalog\Http\Livewire\Rpt;

use Diepxuan\Catalog\Services\ReportColumnLabels;
use Diepxuan\Catalog\Services\SimbaMenuRouteMetadata;
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
     * Map [ten_cot => label tieng Viet] lay tu sysReportResx.
     *
     * Null = chua resolve. Sau khi resolve, cot nao khong co trong map
     * se hien thi ten cot tho (khong tu dat label).
     *
     * @var null|array<string, string>
     */
    public ?array $columnLabelMap = null;

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
        $this->columnLabelMap = null;
        $this->columns = [] !== $this->rows ? $this->resolveColumns($this->rows[0]) : [];
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

    /**
     * Cot can hien thi.
     *
     * Uu tien 1: sysReportResx.formated_col_list (dinh nghia chinh thuc SimbaERP
     *            — vd menu 10.20.02 chi hien 14/40 cot, an cac cot ky thuat
     *            ma_cty/stt_rec/stt_rec0 va cac cot _nt khi la VND).
     * Uu tien 2: toan bo cot SP tra (neu bao cao khong co dinh nghia cot).
     *
     * Cot dinh nghia trong Resx nhung SP khong tra -> bo qua.
     *
     * @param array<string, mixed> $sampleRow
     *
     * @return list<string>
     */
    protected function resolveColumns(array $sampleRow): array
    {
        $available = array_keys($sampleRow);
        $availableLower = [];

        foreach ($available as $column) {
            $availableLower[mb_strtolower((string) $column)] = $column;
        }

        $configured = $this->configuredColumns();

        if ([] === $configured) {
            return $available;
        }

        $columns = [];

        foreach ($configured as $wanted) {
            if (isset($availableLower[$wanted])) {
                $columns[] = $availableLower[$wanted];
            }
        }

        // Neu cau hinh khong khop cot nao (du lieu loi/thieu) -> tra toan bo
        // de khong hien bang trong vo ly.
        return [] !== $columns ? $columns : $available;
    }

    /**
     * Danh sach cot cau hinh trong sysReportResx cho route hien tai.
     *
     * @return list<string>
     */
    protected function configuredColumns(): array
    {
        [$menuId, $maMau] = $this->reportMenuContext();

        return ReportColumnLabels::visibleColumns($menuId, $maMau);
    }

    /**
     * menuid + ma_mau cua bao cao hien tai, lay tu metadata route.
     *
     * @return array{0: null|string, 1: null|string}
     */
    protected function reportMenuContext(): array
    {
        if (null === $this->slug) {
            return [null, null];
        }

        $routeName = mb_strtolower((string) $this->module) . '.rpt.' . mb_strtolower((string) $this->slug);
        $metadata = app(SimbaMenuRouteMetadata::class)->getReport($routeName);

        if (!\is_array($metadata)) {
            return [null, null];
        }

        $menuId = $metadata['menuid'] ?? null;
        $maMau = $metadata['ma_mau'] ?? null;

        return [\is_string($menuId) ? $menuId : null, \is_string($maMau) ? $maMau : null];
    }

    /**
     * Nhan hien thi cho 1 cot.
     *
     * Thu tu:
     *   1. Khai bao tay qua reportColumnLabels() cua component con
     *   2. sysReportResx.formated_col_list (nguon chinh thuc SimbaERP)
     *   3. Ten cot tho (khong tu dat)
     */
    public function columnLabel(string $column): string
    {
        $manual = $this->reportColumnLabels();

        if (isset($manual[$column])) {
            return $manual[$column];
        }

        $lower = mb_strtolower($column);

        if (isset($manual[$lower])) {
            return $manual[$lower];
        }

        return $this->columnLabelMap()[$lower]
            ?? ReportColumnLabels::allLabels()[$lower]
            ?? $column;
    }

    /**
     * Component con override de khai bao label thu cong (uu tien cao nhat).
     *
     * @return array<string, string>
     */
    protected function reportColumnLabels(): array
    {
        return [];
    }

    /**
     * Map label lay tu sysReportResx theo menuid + ma_mau cua route hien tai.
     *
     * @return array<string, string>
     */
    protected function columnLabelMap(): array
    {
        if (null !== $this->columnLabelMap) {
            return $this->columnLabelMap;
        }

        [$menuId, $maMau] = $this->reportMenuContext();

        return $this->columnLabelMap = ReportColumnLabels::for($menuId, $maMau);
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
