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
use Diepxuan\Simba\StoredProcedures\AsSODelPH3;
use Diepxuan\Simba\StoredProcedures\AsSORptBK01;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

    /** @var list<array{ma_ct:string,ten_ct:string}> */
    public array $voucherTypes = [];

    /** Co du lieu bao cao da load (bật nút Xuất Excel ở tab lọc). */
    public bool $hasData = false;

    /**
     * Du lieu bao cao KHONG nam trong snapshot Livewire: chi giu phia server
     * (session) de export CSV / xoa chung tu, con hien thi do Alpine render
     * client-side tu payload dispatch mot lan khi submit.
     *
     * @var list<array<string,mixed>>
     */
    private array $phieuRows = [];

    /** @var list<array<string,mixed>> */
    private array $chiTietRows = [];

    /** @var null|list<array{key:string,label:string,class:string}> */
    private ?array $phieuColumns = null;

    /** @var null|list<array{key:string,label:string,class:string}> */
    private ?array $chiTietColumns = null;

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

            $phieuRows   = $data['ph']->map(fn (mixed $row): array => self::rowToArray($row))->values()->all();
            $chiTietRows = $data['ct']->map(fn (mixed $row): array => self::rowToArray($row))->values()->all();

            $this->phieuRows    = $phieuRows;
            $this->chiTietRows  = $chiTietRows;
            $this->phieuColumns = $this->buildPhieuColumns($phieuRows);
            $this->chiTietColumns = $this->buildChiTietColumns($chiTietRows);
            $this->hasData      = [] !== $phieuRows;

            // Cat du lieu tho + cot vao session de export CSV / xoa chung tu
            // (khong giu trong snapshot Livewire).
            session([$this->reportSessionKey() => [
                'phieu'   => $phieuRows,
                'chitiet' => $chiTietRows,
            ]]);

            $this->dispatch(
                'sorptbk01-report-loaded',
                phieuColumns: $this->phieuColumns,
                chiTietColumns: $this->chiTietColumns,
                phieu: $this->clientRows($phieuRows, $this->phieuColumns, 'phieuCellValue'),
                chiTiet: $this->clientRows($chiTietRows, $this->chiTietColumns, 'chiTietCellValue'),
            );
            $this->dispatch('switch-tab', 'content');
        } catch (\Throwable $exception) {
            report($exception);
            $this->clearData();
            $this->errorMessage = 'Không tải được dữ liệu báo cáo từ SP asSORptBK01.';

            return;
        }
    }

    /**
     * Xoa chung tu SO3 theo stt_rec (Alpine goi sau khi user chon phieu va
     * xac nhan). Thanh cong: dispatch event de Alpine loai dong khoi bang
     * client-side, khong can goi lai SP.
     */
    public function deleteVoucher(string $sttRec): void
    {
        $sttRec = trim($sttRec);
        if ('' === $sttRec) {
            session()->flash('error', 'Chứng từ không hợp lệ.');

            return;
        }

        $stored = $this->storedReport();
        $phieu  = null;
        foreach ($stored['phieu'] as $row) {
            if ((string) self::rowValue($row, ['stt_rec', 'Stt_rec', 'STT_REC']) === $sttRec) {
                $phieu = $row;

                break;
            }
        }

        if (null === $phieu || 'SO3' !== $this->voucherTypeCode($phieu)) {
            session()->flash('error', 'Chứng từ đang chọn không hỗ trợ xóa từ bảng kê này.');

            return;
        }

        DB::beginTransaction();

        try {
            $result = AsSODelPH3::call([
                'pMa_cty' => $this->companyId(),
                'pStt_rec' => $sttRec,
            ]);
            $row = $result->first();
            $pRet = \is_array($row) ? ($row['pRet'] ?? null) : ($row->pRet ?? null);

            if (null !== $pRet && 0 !== (int) $pRet) {
                throw new \RuntimeException('Stored procedure trả về mã lỗi ' . (int) $pRet . '.');
            }

            $stored['phieu'] = array_values(array_filter(
                $stored['phieu'],
                static fn (array $item): bool => (string) self::rowValue($item, ['stt_rec', 'Stt_rec', 'STT_REC']) !== $sttRec
            ));
            $stored['chitiet'] = array_values(array_filter(
                $stored['chitiet'],
                static fn (array $item): bool => (string) self::rowValue($item, ['stt_rec', 'Stt_rec', 'STT_REC']) !== $sttRec
            ));
            session([$this->reportSessionKey() => $stored]);
            $this->hasData = [] !== $stored['phieu'];

            DB::commit();
            session()->flash('success', 'Đã xóa hóa đơn bán hàng.');
            $this->dispatch('sorptbk01-voucher-deleted', sttRec: $sttRec);
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            session()->flash('error', 'Lỗi khi xóa hóa đơn: ' . $exception->getMessage());
        }
    }

    public function exportCsv(): ?StreamedResponse
    {
        $stored = $this->storedReport();

        if ([] === $stored['phieu']) {
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
     * Danh sách cột bảng phiếu: các cột quen thuộc (với nhãn tiếng Việt, canh
     * lề, xử lý VND/NT) đứng trước, sau đó tự bổ sung MỌI cột còn lại mà SP
     * asSORptBK01 trả về — giống DataGridView của SimbaERP tự sinh cột từ
     * DataTable (ReportGridviewBrowseDynamic), đảm bảo bảng hiển thị đầy đủ
     * dữ liệu cần thiết như bản gốc. Các cột raw không có nhãn hiển thị sẽ
     * được lọc trong appendDynamicColumns().
     *
     * @param list<array<string,mixed>> $rows
     *
     * @return list<array{key:string,label:string,class:string}>
     */
    private function buildPhieuColumns(array $rows): array
    {
        $currency = $this->isForeignCurrency() ? 'NT' : 'VND';

        $columns = [
            ['key' => 'ma_ct', 'label' => 'Loại phiếu', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ngay_ct', 'label' => 'Ngày CT', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'so_ct', 'label' => 'Số CT', 'class' => 'text-left whitespace-nowrap font-mono'],
            ['key' => 'ma_kh', 'label' => 'Mã KH', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ten_kh', 'label' => 'Khách hàng', 'class' => 'text-left'],
            ['key' => 'tien2', 'label' => 'Tiền ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'thue_gtgt', 'label' => 'Thuế ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'tt', 'label' => 'Thanh toán ' . $currency, 'class' => 'text-right whitespace-nowrap'],
        ];

        return $this->appendDynamicColumns($columns, $rows);
    }

    /**
     * Danh sách cột bảng chi tiết: tương tự phieuColumns(), các cột quen thuộc
     * trước rồi tự bổ sung mọi cột còn lại của result set CT.
     *
     * @param list<array<string,mixed>> $rows
     *
     * @return list<array{key:string,label:string,class:string}>
     */
    private function buildChiTietColumns(array $rows): array
    {
        $currency = $this->isForeignCurrency() ? 'NT' : 'VND';

        $columns = [
            ['key' => 'ma_vt', 'label' => 'Mã VT', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ten_vt', 'label' => 'Tên vật tư', 'class' => 'text-left'],
            ['key' => 'dvt', 'label' => 'DVT', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'ma_kho', 'label' => 'Kho', 'class' => 'text-left whitespace-nowrap'],
            ['key' => 'so_luong', 'label' => 'Số lượng', 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'gia2', 'label' => 'Giá ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'tien2', 'label' => 'Tiền ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'thue_gtgt', 'label' => 'Thuế ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'tt', 'label' => 'Thanh toán ' . $currency, 'class' => 'text-right whitespace-nowrap'],
            ['key' => 'ma_nvkd', 'label' => 'NVKD', 'class' => 'text-left whitespace-nowrap'],
        ];

        return $this->appendDynamicColumns($columns, $rows);
    }

    /**
     * @param bool $forExport true khi xuất CSV: ô tiền trống trả về '' thay vì
     *                        placeholder '—' (giống quy ước cũ của csvRows()).
     */
    public function phieuCellValue(array $row, string $column, bool $forExport = false): string
    {
        return match ($column) {
            'ma_ct'   => self::csvValue($this->voucherTypeName(self::rowValue($row, ['ma_ct', 'Ma_ct', 'MA_CT', 'loai_ct', 'Loai_ct', 'LOAI_CT']))),
            'ngay_ct' => self::dateValue(self::rowValue($row, ['ngay_ct', 'Ngay_ct'])),
            'so_ct'   => self::csvValue(self::rowValue($row, ['so_ct', 'So_ct'])),
            'ma_kh'   => self::csvValue(self::rowValue($row, ['ma_kh', 'Ma_kh'])),
            'ten_kh'  => self::csvValue(self::rowValue($row, ['ten_kh', 'Ten_kh'])),
            'tien2'   => $this->moneyCell($row, 'tien2', $forExport),
            'thue_gtgt' => $this->moneyCell($row, 'thue_gtgt', $forExport),
            'tt'      => $this->moneyCell($row, 'tt', $forExport),
            default   => $this->dynamicCellValue($row, $column, $forExport),
        };
    }

    /**
     * @param bool $forExport true khi xuất CSV: ô tiền/số lượng trống trả về
     *                        '' thay vì placeholder '—'.
     */
    public function chiTietCellValue(array $row, string $column, bool $forExport = false): string
    {
        return match ($column) {
            'ma_vt'     => self::csvValue(self::rowValue($row, ['ma_vt', 'Ma_vt'])),
            'ten_vt'    => self::csvValue(self::rowValue($row, ['ten_vt', 'Ten_vt'])),
            'dvt'       => self::csvValue(self::rowValue($row, ['dvt', 'Dvt'])),
            'ma_kho'    => self::csvValue(self::rowValue($row, ['ma_kho', 'Ma_kho'])),
            'so_luong'  => $this->quantityCell($row, 'so_luong', $forExport),
            'gia2'      => $this->moneyCell($row, 'gia2', $forExport),
            'tien2'     => $this->moneyCell($row, 'tien2', $forExport),
            'thue_gtgt' => $this->moneyCell($row, 'thue_gtgt', $forExport),
            'tt'        => $this->moneyCell($row, 'tt', $forExport),
            'ma_nvkd'   => self::csvValue(self::rowValue($row, ['ma_nvkd', 'Ma_nvkd'])),
            default     => $this->dynamicCellValue($row, $column, $forExport),
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
            'editUrlTemplate' => simbaroute('so.vch.sovchso3.edit', ['id' => '__STT_REC__']),
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
        $stored = $this->storedReport();
        $phieuColumns   = $this->buildPhieuColumns($stored['phieu']);
        $chiTietColumns = $this->buildChiTietColumns($stored['chitiet']);

        $rows = [];

        foreach ($stored['phieu'] as $phieu) {
            $row = ['Loai' => 'Phiếu'];
            foreach ($phieuColumns as $column) {
                $row[$column['label']] = $this->phieuCellValue($phieu, $column['key'], true);
            }
            $rows[] = $row;
        }

        foreach ($stored['chitiet'] as $chiTiet) {
            $row = ['Loai' => 'Chi tiết'];
            foreach ($chiTietColumns as $column) {
                $row[$column['label']] = $this->chiTietCellValue($chiTiet, $column['key'], true);
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Ghep them vao danh sach cot cac cot con lai ma SP tra ve (ngoai nhung
     * cot da khai bao cu the). Giong SimbaERP: DataGridView tu sinh cot tu
     * DataTable, nen bang phai hien thi day du cac truong co nhan hien thi
     * cua result set.
     *
     * Bo qua: cot lien ket noi bo (stt_rec), cac bien the tien te khong dung
     * (VND/NT theo pMa_nt) de tranh trung lap voi cot tien chinh.
     *
     * @param list<array{key:string,label:string,class:string}> $columns
     * @param list<array<string,mixed>>                          $rows
     *
     * @return list<array{key:string,label:string,class:string}>
     */
    private function appendDynamicColumns(array $columns, array $rows): array
    {
        $seen = [];
        foreach ($columns as $column) {
            $seen[mb_strtolower($column['key'])] = true;
            foreach ($this->columnKeys($column['key']) as $variant) {
                $seen[mb_strtolower($variant)] = true;
            }
        }
        $seen['stt_rec'] = true;

        foreach ($rows as $row) {
            foreach (array_keys($row) as $key) {
                $lower = mb_strtolower((string) $key);
                if (isset($seen[$lower])
                    || $this->isCurrencyVariantToSkip($key)
                    || $this->isUnlabeledColumnToSkip($key)) {
                    continue;
                }
                $seen[$lower] = true;
                $columns[] = [
                    'key'   => (string) $key,
                    'label' => self::extraColumnLabel($key),
                    'class' => $this->extraColumnClass($key),
                ];
            }
        }

        return $columns;
    }

    /**
     * SP asSORptBK01 tra ve them mot so truong thuc su chi dung cho viec tinh
     * toan/hien thi o phieu hoac cac cot raw khong co nhan hien thi trong
     * DataGridView goc. Khong them cac cot chua co nhan tieng Viet vao bang de
     * tranh hien nhung ten cot ky thuat khong can thiet.
     */
    private function isUnlabeledColumnToSkip(string $key): bool
    {
        if (in_array(mb_strtolower($key), [
            'stt_rec0', 'stt_rec0_dh', 'ma_lo', 'ma_vitri', 'tk_pt', 'tk_thue', 'tk_ck_ds',
        ], true)) {
            return true;
        }

        return mb_strtolower(self::extraColumnLabel($key)) === mb_strtolower(self::csvValue($key));
    }

    /**
     * Bien the tien te khong dung theo lua chon pMa_nt thi khong them cot
     * rieng (vi cot tien chinh da xu ly VND/NT): vd dang chon VND thi bo
     * qua t_tien_nt / t_tien_nt2; dang chon NT thi bo qua t_tien / t_tien2.
     */
    private function isCurrencyVariantToSkip(string $key): bool
    {
        $lower = mb_strtolower($key);
        $isNt  = str_contains($lower, '_nt') || str_ends_with($lower, '_nt2');
        $isMoney = $this->isMoneyColumn($key);

        if (!$isMoney) {
            return false;
        }

        return $this->isForeignCurrency() ? !$isNt : $isNt;
    }

    /**
     * Gia tri hien thi cho cot dong (SP tra ve them, khong nam trong match):
     * cot tien -> dinh dang tien (co '—' khi trong), cot ngay -> dinh dang
     * ngay, con lai -> chuoi thuan.
     */
    private function dynamicCellValue(array $row, string $column, bool $forExport = false): string
    {
        $lower = mb_strtolower($column);

        if (str_starts_with($lower, 'ngay')) {
            return self::dateValue(self::rowValue($row, [$column]));
        }

        if ($this->isQuantityColumn($column)) {
            return $this->quantityCell($row, $column, $forExport);
        }

        if ($this->isMoneyColumn($column)) {
            return $this->moneyCell($row, $column, $forExport);
        }

        return self::csvValue(self::rowValue($row, [$column]));
    }

    /**
     * Cot so luong (so_luong_qd, sl_xuat, sl_xuat_qd...) — dinh dang 4 chu so
     * thap phan khong phu thuoc vao VND/NT.
     */
    private function isQuantityColumn(string $column): bool
    {
        $lower = mb_strtolower($column);

        return in_array($lower, ['so_luong', 'so_luong_qd', 'sl_xuat', 'sl_xuat_qd'], true)
            || (bool) preg_match('/(^|_)(so_luong|sl_xuat)(_|$)/', $lower);
    }

    /**
     * Cell tien: dinh dang so tien; khi rong hien thi '—' tren UI, CSV van ''.
     */
    private function moneyCell(array $row, string $column, bool $forExport): string
    {
        $value = $this->moneyValue($row, $column);

        return $forExport ? $value : self::moneyDisplay($value);
    }

    /**
     * Cell so luong: 4 chu so thap phan; khi rong hien thi '—' tren UI.
     */
    private function quantityCell(array $row, string $column, bool $forExport): string
    {
        $value = self::numberValue(self::rowValue($row, [$column]), 4);

        return $forExport ? $value : self::moneyDisplay($value);
    }

    /**
     * Cot tien/so luong (canh phai, danh dau am/SO4): gom cac cot quen thuoc
     * + cac cot dong co ten chua tien/thue/gia/ck/so_luong.
     */
    private function isMoneyColumn(string $column): bool
    {
        $lower = mb_strtolower($column);

        if (in_array($lower, [
            't_tien', 't_thue', 't_tt', 't_ck', 't_ck_ds', 't_gg', 't_cp', 't_tien_hh', 't_thue_hh', 't_tt_cp',
            'so_luong', 'so_luong_qd', 'sl_xuat', 'sl_xuat_qd',
            'gia', 'tien', 'gia2', 'tien2', 'tt', 'tt_nt',
            'thue_gtgt', 'thue_gtgt_nt', 'tien_ck', 'tien_ck_nt', 'ck_ds', 'ck_ds_nt',
            'ty_gia',
        ], true)) {
            return true;
        }

        return (bool) preg_match('/(^|_)(t_tien|t_thue|t_tt|t_ck|t_gg|t_cp|tien|thue|gia|so_luong)(_|$)/', $lower);
    }

    /**
     * Nhan tieng Viet cho cac cot phu hay gap cua SP (PH/CT SO3-SO5); neu
     * chua co thi hien thi chinh ten cot.
     */
    private static function extraColumnLabel(string $key): string
    {
        $labels = [
            'ma_nt'         => 'Mã NT',
            'ty_gia'        => 'Tỷ giá',
            'dien_giai'     => 'Diễn giải',
            'nguoi_gd'      => 'Người giao dịch',
            'so_seri'       => 'Số seri',
            'so_seri_mhd'   => 'Mẫu HĐ',
            'so_hd'         => 'Số HĐ',
            'ngay_hd'       => 'Ngày HĐ',
            'ngay_lct'      => 'Ngày lập',
            'ngay_px'       => 'Ngày PX',
            'so_px'         => 'Số PX',
            'ngay_pn'       => 'Ngày PN',
            'so_pn'         => 'Số PN',
            'ma_so_thue'    => 'MST',
            'ten_kh_vat'    => 'Tên KH (HĐ)',
            'dia_chi_vat'   => 'Địa chỉ (HĐ)',
            'ma_httt'       => 'HTTT',
            'httt'          => 'Tên HTTT',
            'ma_tt'         => 'ĐKTT',
            'trang_thai'    => 'Trạng thái',
            'ma_hd'         => 'Mã HĐ',
            'ten_hd'        => 'Tên HĐ',
            'ma_bp'         => 'Bộ phận',
            'ten_nvkd'      => 'Tên NVKD',
            'ma_gd'         => 'Mã GD',
            'tk_pt'         => 'TK phải thu',
            'tk_thue'       => 'TK thuế',
            'tk_ck_ds'      => 'TK chiết khấu doanh số',
            'ma_lo'         => 'Mã lô',
            'ma_vitri'      => 'Vị trí',
            'ma_spct'       => 'SPCT',
            'ma_phi'        => 'Mã phí',
            'ma_thue'       => 'Mã thuế',
            'ts_gtgt'       => 'Thuế suất',
            'ma_kho'        => 'Mã kho',
            'ten_vt'        => 'Tên vật tư',
            'dvt'           => 'DVT',
            'stt_rec0'      => 'STT',
            'so_luong_qd'   => 'SL quy đổi',
            'sl_xuat'       => 'SL xuất',
            'sl_xuat_qd'    => 'SL xuất QĐ',
            't_ck'          => 'Chiết khấu',
            't_ck_nt'       => 'Chiết khấu NT',
            't_ck_ds'       => 'Chiết khấu doanh số',
            't_ck_ds_nt'    => 'Chiết khấu doanh số NT',
            't_gg'          => 'Giảm giá',
            't_gg_nt'       => 'Giảm giá NT',
            't_cp'          => 'Chi phí',
            't_cp_nt'       => 'Chi phí NT',
            't_tien_hh'     => 'Tiền hàng hóa',
            't_tien_hh_nt'  => 'Tiền HH NT',
            't_thue_hh'     => 'Thuế HH',
            't_thue_hh_nt'  => 'Thuế HH NT',
            't_tt_cp'       => 'TT chi phí',
            't_tt_cp_nt'    => 'TT chi phí NT',
            't_so_luong'    => 'Tổng SL',
            'tien_ck'       => 'Tiền chiết khấu',
            'tien_ck_nt'    => 'Tiền chiết khấu NT',
            'ck_ds'         => 'Chiết khấu doanh số',
            'ck_ds_nt'      => 'Chiết khấu doanh số NT',
            'gia_nt'        => 'Giá NT',
            'gia_nt2'       => 'Giá NT',
            'tien_nt'       => 'Tiền NT',
            'tien_nt2'      => 'Tiền NT',
            'thue_gtgt_nt'  => 'Thuế NT',
            't_tien_nt'     => 'Tiền NT',
            't_tien_nt2'    => 'Tiền NT',
            't_thue_nt'     => 'Thuế NT',
            't_tt_nt'       => 'TT NT',
            't_tt_nt2'      => 'TT NT',
            'ma_nvkd'       => 'NVKD',
            'ma_bp'         => 'Bộ phận',
            'nguoi_lap'     => 'Người lập',
            'post2gl'       => 'Đã post GL',
            'post2in'       => 'Đã post IN',
            'so_dh'         => 'Số ĐH',
            'ngay_dh'       => 'Ngày ĐH',
            'ma_nhkh'       => 'Nhóm KH',
            'ma_nhvt'       => 'Nhóm VT',
            'ma_plkh1'      => 'PL KH 1',
            'ma_plkh2'      => 'PL KH 2',
            'ma_plkh3'      => 'PL KH 3',
            'ma_plvt1'      => 'PL VT 1',
            'ma_plvt2'      => 'PL VT 2',
            'ma_plvt3'      => 'PL VT 3',
            'ma_hd'         => 'Số HĐ',
            'nguoi_nhan'    => 'Người nhận',
            'cty_nhan'      => 'Cty nhận',
            'dc_nhan'       => 'Địa chỉ nhận',
            'tel_nhan'      => 'ĐT nhận',
            'ten_kho'       => 'Tên kho',
            'ten_nv'       => 'Tên NV',
        ];

        $lower = mb_strtolower($key);

        return $labels[$lower] ?? self::csvValue($key);
    }

    /**
     * Class canh le cho cot dong: cot tien/so luong canh phai; cot ngay/cot
     * ma canh trai; cot dien giai/dia chi/ten cho phep xuong dong.
     */
    private function extraColumnClass(string $key): string
    {
        $lower = mb_strtolower($key);

        if ($this->isMoneyColumn($key)) {
            return 'text-right whitespace-nowrap';
        }

        if (str_contains($lower, 'dien_giai') || str_contains($lower, 'dia_chi') || str_contains($lower, 'ten_') || str_contains($lower, 'httt')) {
            return 'text-left';
        }

        return 'text-left whitespace-nowrap';
    }

    private function moneyValue(array $row, string $column): string
    {
        return self::numberValue(
            self::rowValue($row, $this->columnKeys($column)),
            $this->isForeignCurrency() ? 4 : 0
        );
    }

    /**
     * Cell tiền cua phiếu tra lai (SO4) hoặc gia tri am duoc danh mau do nhat.
     *
     * SO4 luu so tien duong trong SoPh4, dau am chi ap dung khi post GL
     * (asPostSoPh4_glct) — nen phai nhan dien theo ma_ct chu khong phai
     * dau am, neu khong phiếu tra hang khong bao gio co mau.
     */
    private function isReturnCell(array $row, string $column): bool
    {
        if ($this->isNegative($row, $column)) {
            return true;
        }

        return 'SO4' === $this->voucherTypeCode($row);
    }

    /**
     * Gia tri am nghia la phiếu tra lai (so tien / so luong bi tru di).
     */
    private function isNegative(array $row, string $column): bool
    {
        $value = self::rowValue($row, $this->columnKeys($column));

        return is_numeric($value) && (float) $value < 0;
    }

    /**
     * Ma chung tu (ma_ct) cua mot row PH/CT, chuan hoa hoa — chi tiet ct
     * khong co ma_ct nen lay tu phieu cha dang chon khi can.
     */
    private function voucherTypeCode(array $row): string
    {
        return mb_strtoupper(trim((string) self::rowValue($row, ['ma_ct', 'Ma_ct', 'MA_CT', 'loai_ct', 'Loai_ct', 'LOAI_CT'])));
    }

    /**
     * @return list<string>
     */
    private function columnKeys(string $column): array
    {
        return match ($column) {
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
            'gia2' => $this->isForeignCurrency()
                ? ['gia_nt2', 'gia_nt', 'gia2', 'gia']
                : ['gia2', 'gia', 'gia_nt2', 'gia_nt'],
            'tien' => $this->isForeignCurrency()
                ? ['tien_nt', 'tien_nt2', 'tien', 'tien2']
                : ['tien', 'tien2', 'tien_nt', 'tien_nt2'],
            'tien2' => $this->isForeignCurrency()
                ? ['tien_nt2', 'tien_nt', 'tien2', 'tien']
                : ['tien2', 'tien', 'tien_nt2', 'tien_nt'],
            'thue_gtgt' => $this->isForeignCurrency()
                ? ['thue_gtgt_nt', 'thue_gtgt', 't_thue_nt', 't_thue']
                : ['thue_gtgt', 't_thue', 'thue_gtgt_nt', 't_thue_nt'],
            'tt' => $this->isForeignCurrency()
                ? ['tt_nt', 'tt', 't_tt_nt', 't_tt']
                : ['tt', 't_tt', 'tt_nt', 't_tt_nt'],
            'so_luong' => ['so_luong', 'So_luong'],
            default => [$column],
        };
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
        $this->phieuColumns = null;
        $this->chiTietColumns = null;
        $this->hasData = false;
        session()->forget($this->reportSessionKey());
    }

    private function reportSessionKey(): string
    {
        return 'sorptbk01.report.' . $this->getId();
    }

    /**
     * Du lieu tho da cat trong session o submit() — dung cho export CSV va
     * xoa chung tu.
     *
     * @return array{phieu: list<array<string,mixed>>, chitiet: list<array<string,mixed>>}
     */
    private function storedReport(): array
    {
        $stored = session($this->reportSessionKey());

        return [
            'phieu'   => $stored['phieu'] ?? [],
            'chitiet' => $stored['chitiet'] ?? [],
        ];
    }

    /**
     * Pre-format rows cho Alpine: moi row giu stt_rec/ma_ct/so_ct kem cac
     * cell da dinh dang san (gia tri + class mau) theo danh sach cot, de
     * bang render client-side khong can goi lai server.
     *
     * @param list<array<string,mixed>>                          $rows
     * @param list<array{key:string,label:string,class:string}> $columns
     *
     * @return list<array{stt_rec:string,ma_ct:string,so_ct:string,cells:list<array{v:string,c:string}>}>
     */
    private function clientRows(array $rows, array $columns, string $valueMethod): array
    {
        // Chi tiet ct khong co ma_ct — nhan dien phieu cha (SO3/SO4...) theo stt_rec.
        $maCtBySttRec = [];
        foreach ($this->phieuRows as $phieu) {
            $maCtBySttRec[(string) self::rowValue($phieu, ['stt_rec', 'Stt_rec', 'STT_REC'])] = $this->voucherTypeCode($phieu);
        }

        return array_map(function (array $row) use ($columns, $valueMethod, $maCtBySttRec): array {
            $sttRec = (string) self::rowValue($row, ['stt_rec', 'Stt_rec', 'STT_REC']);
            $maCt   = $this->voucherTypeCode($row) ?: ($maCtBySttRec[$sttRec] ?? '');

            $cells = [];
            foreach ($columns as $column) {
                $isRed = $this->isMoneyColumn($column['key'])
                    && ('SO4' === $maCt || $this->isNegative($row, $column['key']));
                $cells[] = [
                    'v' => $this->{$valueMethod}($row, $column['key']),
                    'c' => $isRed ? 'text-red-500' : 'text-gray-700',
                ];
            }

            return [
                'stt_rec' => $sttRec,
                'ma_ct'   => $maCt,
                'so_ct'   => self::csvValue(self::rowValue($row, ['so_ct', 'So_ct'])),
                'cells'   => $cells,
            ];
        }, $rows);
    }

    /**
     * Tra ve ten loai phieu (ten_ct) tu ma chung tu (ma_ct) trong danh muc
     * asSIGetDmSo_ct; fallback la chinh ma_ct neu chua co trong danh muc.
     *
     * SORptBK01 gop nhieu loai phieu SO1-SO5 nen can cot Loai phieu de phan
     * biet phieu ban hang (SO3) voi phieu nhap hang ban bi tra lai (SO4).
     */
    private function voucherTypeName(mixed $value): string
    {
        $maCt = trim((string) $value);
        if ('' === $maCt) {
            return '';
        }

        foreach ($this->voucherTypes as $voucherType) {
            if (($voucherType['ma_ct'] ?? '') === $maCt) {
                return (string) ($voucherType['ten_ct'] ?? $maCt);
            }
        }

        return $maCt;
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

    /**
     * O rong trong cot tien/thue/thanh toan duoc hien thi la '—' de phan biet
     * voi cell du lieu thieu (khong co gia tri khong phai loi). CSV van xuat ''.
     */
    private static function moneyDisplay(string $value): string
    {
        return '' === $value ? '—' : $value;
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
