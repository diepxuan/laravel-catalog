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

use Diepxuan\Simba\Models\ArDmKh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsGetDMHTTT;
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
use Diepxuan\Simba\StoredProcedures\AsGetSttRec;
use Diepxuan\Simba\StoredProcedures\AsINGetDMKHO;
use Diepxuan\Simba\StoredProcedures\AsINGetDMVT;
use Diepxuan\Simba\StoredProcedures\AsPODeletePO3;
use Diepxuan\Simba\StoredProcedures\AsPOGetPO3;
use Diepxuan\Simba\StoredProcedures\AsPOSavePO3;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Form nhập/sửa hóa đơn mua hàng (PO3).
 *
 * Mapping:
 * - menuID   : `10.10.14` (sysMenu, sysVoucherInfo ma_ct = `PO3`).
 * - DLL      : `POVchPO3.dll` (form `frmPOVchPO3`, 4 tab: Detail / Chi phi / Hach toan / Tain).
 * - SP       : `asPOGetPO3` (load 4 dataset) + `asPOSavePO3` (save full).
 * - Route    : `_simba-source/po/vch/povchpo3/{stt_rec?}/edit` → `Povchpo3Edit::class`.
 *
 * Refactor tu `Muahang\HoadonmuaEdit` (dead code) sang pattern chuan `Po\Vch\Povchpo3Edit`
 * (giong `Po\Rpt\Arrptbccn01Sl`). Bind SP wrappers, port logic tinh toan
 * (auto-calc tien_nt0, ck_nt, thue_gtgt_nt, totals).
 */
class Povchpo3Edit extends Component
{
    const MA_CT = 'PO3';

    // Header
    public ?string $pStt_rec = null;

    public string $pMode = 'create';

    public ?string $pNgay_ct = null;

    public ?string $pNgay_lct = null;

    public string $pSo_ct = '';

    public string $pSo_hd = '';

    public ?string $pNgay_hd = null;

    public ?string $pMa_kh = null;

    public string $pTen_kh = '';

    public string $pNguoi_gd = '';

    public string $pDia_chi = '';

    public string $pMa_so_thue = '';

    public string $pDien_giai = '';

    public ?string $pMa_httt = null;

    public string $pMa_nt = 'VND';

    public float $pTy_gia = 1;

    public string $pTk_pt = '';

    public string $pTk_thue = '';

    // Totals
    public float $pT_tien_nt0 = 0;

    public float $pT_tien0 = 0;

    public float $pT_cp_nt = 0;

    public float $pT_cp = 0;

    public float $pT_thue_nt = 0;

    public float $pT_thue = 0;

    public float $pT_ck_nt = 0;

    public float $pT_ck = 0;

    public float $pT_tt_nt = 0;

    public float $pT_tt = 0;

    public float $pT_so_luong = 0;

    // Details & Costs
    public array $pChiTiet = [];

    public array $pChiPhi = [];

    public array $pHachToan = [];

    // UI state
    public int $pActiveTab = 0; // 0=Header+Chi tiet, 1=Chi phi, 2=Hach toan

    public function mount(?string $stt_rec = null): void
    {
        $this->pNgay_ct = now()->format('Y-m-d');
        $this->pNgay_lct = now()->format('Y-m-d');
        $this->pNgay_hd = now()->format('Y-m-d');

        // Default values theo yeu cau Sep
        $this->pMa_httt = '331';
        $this->pTk_pt = '11217';
        $this->pTk_thue = '1331';

        if ($stt_rec) {
            $this->pMode = 'edit';
            $this->loadInvoice($stt_rec);
        } else {
            // Sinh so chung tu moi qua SP AsGetSoCt
            $this->pSo_ct = AsGetSoCt::call([
                'pMa_ct' => self::MA_CT,
                'pNgay_Ct' => $this->pNgay_ct,
            ]);
        }
    }

    public function loadInvoice(string $stt_rec): void
    {
        $result = AsPOGetPO3::callWithDataSets([
            'pMa_cty' => SModel::CTY,
            'pStt_rec' => $stt_rec,
        ]);

        if ([] === $result || (($result[0] ?? collect())->isEmpty())) {
            session()->flash('error', 'Không tìm thấy hóa đơn.');

            return;
        }

        // Dataset 0: Header
        $header = $result[0]->first();
        if (null === $header) {
            session()->flash('error', 'Không tìm thấy hóa đơn.');

            return;
        }
        $header = \is_array($header) ? $header : (array) $header;
        $this->pStt_rec = $header['stt_rec'] ?? null;
        $this->pNgay_ct = $this->dateValue($header['ngay_ct'] ?? null);
        $this->pNgay_lct = $this->dateValue($header['ngay_lct'] ?? null);
        $this->pSo_ct = (string) ($header['so_ct'] ?? '');
        $this->pSo_hd = (string) ($header['so_hd'] ?? '');
        $this->pNgay_hd = $this->dateValue($header['ngay_hd'] ?? null);
        $this->pMa_kh = (string) ($header['ma_kh'] ?? '');
        $this->pTen_kh = (string) ($header['ten_kh'] ?? '');
        $this->pNguoi_gd = (string) ($header['nguoi_gd'] ?? '');
        $this->pDia_chi = (string) ($header['dia_chi'] ?? '');
        $this->pMa_so_thue = (string) ($header['ma_so_thue'] ?? '');
        $this->pDien_giai = (string) ($header['dien_giai'] ?? '');
        $this->pMa_httt = '' !== (string) ($header['ma_httt'] ?? '') ? (string) $header['ma_httt'] : null;
        $this->pMa_nt = (string) ($header['ma_nt'] ?? 'VND');
        $this->pTy_gia = (float) ($header['ty_gia'] ?? 1);
        $this->pTk_pt = (string) ($header['tk_pt'] ?? '');
        $this->pTk_thue = (string) ($header['tk_thue'] ?? '');

        $this->pT_tien_nt0 = (float) ($header['t_tien_nt0'] ?? 0);
        $this->pT_tien0 = (float) ($header['t_tien0'] ?? 0);
        $this->pT_cp_nt = (float) ($header['t_cp_nt'] ?? 0);
        $this->pT_cp = (float) ($header['t_cp'] ?? 0);
        $this->pT_thue_nt = (float) ($header['t_thue_nt'] ?? 0);
        $this->pT_thue = (float) ($header['t_thue'] ?? 0);
        $this->pT_ck_nt = (float) ($header['t_ck_nt'] ?? 0);
        $this->pT_ck = (float) ($header['t_ck'] ?? 0);
        $this->pT_tt_nt = (float) ($header['t_tt_nt'] ?? 0);
        $this->pT_tt = (float) ($header['t_tt'] ?? 0);
        $this->pT_so_luong = (float) ($header['t_so_luong'] ?? 0);

        // Dataset 1: Chi tiet vat tu
        if (isset($result[1])) {
            $this->pChiTiet = $result[1]->map(static fn ($ct) => [
                'stt_rec0' => (string) ($ct['stt_rec0'] ?? ''),
                'ma_vt' => (string) ($ct['ma_vt'] ?? ''),
                'ten_vt' => (string) ($ct['ten_vt'] ?? ''),
                'dvt' => (string) ($ct['dvt'] ?? ''),
                'ma_kho' => (string) ($ct['ma_kho'] ?? ''),
                'ma_vitri' => (string) ($ct['ma_vitri'] ?? ''),
                'ma_lo' => (string) ($ct['ma_lo'] ?? ''),
                'so_luong' => (float) ($ct['so_luong'] ?? 0),
                'so_luong_qd' => (float) ($ct['so_luong_qd'] ?? 0),
                'gia_nt0' => (float) ($ct['gia_nt0'] ?? 0),
                'gia0' => (float) ($ct['gia0'] ?? 0),
                'tien_nt0' => (float) ($ct['tien_nt0'] ?? 0),
                'tien0' => (float) ($ct['tien0'] ?? 0),
                'ma_thue' => (string) ($ct['ma_thue'] ?? ''),
                'ts_gtgt' => (float) ($ct['ts_gtgt'] ?? 0),
                'thue_gtgt_nt' => (float) ($ct['thue_gtgt_nt'] ?? 0),
                'thue_gtgt' => (float) ($ct['thue_gtgt'] ?? 0),
                'tl_ck' => (float) ($ct['tl_ck'] ?? 0),
                'ck_nt' => (float) ($ct['ck_nt'] ?? 0),
                'ck' => (float) ($ct['ck'] ?? 0),
                'tien_cp_nt' => (float) ($ct['tien_cp_nt'] ?? 0),
                'tien_cp' => (float) ($ct['tien_cp'] ?? 0),
                'tk_vt' => (string) ($ct['tk_vt'] ?? ''),
                'tk_thue' => (string) ($ct['tk_thue'] ?? ''),
                'tk_dt' => (string) ($ct['tk_dt'] ?? ''),
                'tk_gv' => (string) ($ct['tk_gv'] ?? ''),
                'tk_ck' => (string) ($ct['tk_ck'] ?? ''),
                'ma_bp' => (string) ($ct['ma_bp'] ?? ''),
                'ma_phi' => (string) ($ct['ma_phi'] ?? ''),
                'ma_spct' => (string) ($ct['ma_spct'] ?? ''),
            ])->toArray();
        }

        // Dataset 2: Chi phi
        if (isset($result[2])) {
            $this->pChiPhi = $result[2]->map(static fn ($cp) => [
                'stt_rec0' => (string) ($cp['stt_rec0'] ?? ''),
                'ma_cp' => (string) ($cp['ma_cp'] ?? ''),
                'ten_cp' => (string) ($cp['ten_cp'] ?? ''),
                'tt_pb' => (string) ($cp['tt_pb'] ?? '1'),
                'tien_cp_nt' => (float) ($cp['tien_cp_nt'] ?? 0),
                'tien_cp' => (float) ($cp['tien_cp'] ?? 0),
                'ts_gtgt' => (float) ($cp['ts_gtgt'] ?? 0),
                'thue_gtgt_nt' => (float) ($cp['thue_gtgt_nt'] ?? 0),
                'thue_gtgt' => (float) ($cp['thue_gtgt'] ?? 0),
                'tt_nt' => (float) ($cp['tt_nt'] ?? 0),
                'tt' => (float) ($cp['tt'] ?? 0),
                'ma_bp' => (string) ($cp['ma_bp'] ?? ''),
                'ma_phi' => (string) ($cp['ma_phi'] ?? ''),
                'ma_spct' => (string) ($cp['ma_spct'] ?? ''),
                'ma_lo' => (string) ($cp['ma_lo'] ?? ''),
            ])->toArray();
        }
    }

    public function updatedPMaKh($value): void
    {
        if (empty($value)) {
            $this->pTen_kh = '';
            $this->pDia_chi = '';
            $this->pMa_so_thue = '';
            $this->pNguoi_gd = '';
            $this->pMa_httt = null;
            $this->pTk_pt = '';
            $this->pTk_thue = '';

            return;
        }

        $ncc = ArDmKh::find($value);
        if ($ncc) {
            $this->pTen_kh = $ncc->ten_kh ?? '';
            $this->pDia_chi = $ncc->dia_chi ?? '';
            $this->pMa_so_thue = $ncc->ma_so_thue ?? '';
            $this->pNguoi_gd = $ncc->nguoi_gd ?? '';
            if ($ncc->ma_httt_po) {
                $this->pMa_httt = $ncc->ma_httt_po;
                $this->fillTaiKhoanFromHttt($ncc->ma_httt_po);
            }
        }
    }

    /**
     * Auto-fill tk_pt + tk_thue khi `pMa_httt` thay doi (user chon/xoa HTTT
     * hoac `updatedPMaKh` gan tu NCC).
     *
     * Map tu bang SIDMHTTT (qua SP asSIGetDMHTTT):
     * - tk_pt    <- HTTT.tk               (tai khoan phai tra goc cua HTTT)
     * - tk_thue  <- HTTT.tk_thue_gtgt_mua (tai khoan thue GTGT dau vao)
     */
    public function updatedPMaHttt(mixed $value): void
    {
        if ($value === null || trim((string) $value) === '') {
            $this->resetTaiKhoanFromHttt();

            return;
        }

        $this->fillTaiKhoanFromHttt((string) $value);
    }

    protected function fillTaiKhoanFromHttt(string $ma_httt): void
    {
        if (trim($ma_httt) === '') {
            return;
        }

        $rows = AsGetDMHTTT::call([
            'pMa_cty' => SModel::CTY,
            'pMa_httt' => $ma_httt,
            'pModuleid' => 'PO',
            'pStruct' => '0',
        ]);

        if ($rows->isEmpty()) {
            $this->resetTaiKhoanFromHttt();
            session()->flash('warning', 'Không tìm thấy HTTT \''.$ma_httt.'\' trong danh mục SIDMHTTT (module PO). TK phải trả + TK thuế đã được xóa, vui lòng chọn lại.');

            return;
        }

        $httt = $rows->first();

        if (! empty($httt->tk)) {
            $this->pTk_pt = $httt->tk;
        }

        if (! empty($httt->tk_thue_gtgt_mua)) {
            $this->pTk_thue = $httt->tk_thue_gtgt_mua;
        }
    }

    /**
     * Xoa tk_pt + tk_thue khi HTTT khong hop le.
     */
    protected function resetTaiKhoanFromHttt(): void
    {
        $this->pTk_pt = '';
        $this->pTk_thue = '';
    }

    /**
     * Nhan event tu component `input-ngoaite` khi user chon ngoai te.
     *
     * Component `InputNgoaite` dispatch `ngoaite-updated`. pMa_nt da duoc dong bo
     * tu dong qua wire:model (Modelable), nen ham nay chi xu ly auto-fill pTy_gia.
     */
    #[On('ngoaite-updated')]
    public function onNgoaiteUpdated(?string $ma_nt = null, ?string $ten_nt = null, float $ty_gia = 1): void
    {
        $this->pTy_gia = ($ma_nt !== null && trim($ma_nt) !== '' && $ty_gia > 0) ? $ty_gia : 1;
    }

    public function addChiTietRow(): void
    {
        $this->pChiTiet[] = [
            'stt_rec0' => '',
            'ma_vt' => '',
            'ten_vt' => '',
            'dvt' => '',
            'ma_kho' => '',
            'ma_vitri' => '',
            'ma_lo' => '',
            'so_luong' => 0,
            'so_luong_qd' => 0,
            'gia_nt0' => 0,
            'gia0' => 0,
            'tien_nt0' => 0,
            'tien0' => 0,
            'ma_thue' => '',
            'ts_gtgt' => 0,
            'thue_gtgt_nt' => 0,
            'thue_gtgt' => 0,
            'tl_ck' => 0,
            'ck_nt' => 0,
            'ck' => 0,
            'tien_cp_nt' => 0,
            'tien_cp' => 0,
            'tk_vt' => '',
            'tk_thue' => '',
            'tk_dt' => '',
            'tk_gv' => '',
            'tk_ck' => '',
            'ma_bp' => '',
            'ma_phi' => '',
            'ma_spct' => '',
        ];
    }

    public function removeChiTietRow(int $index): void
    {
        unset($this->pChiTiet[$index]);
        $this->pChiTiet = array_values($this->pChiTiet);
        $this->recalculateTotals();
    }

    public function updated($property): void
    {
        if (preg_match('/^pChiTiet\.(\d+)\.ma_vt$/', (string) $property, $matches)) {
            $this->fillVatTu((int) $matches[1]);

            return;
        }

        if (preg_match('/^pChiTiet\.(\d+)\.ma_kho$/', (string) $property, $matches)) {
            $this->fillKho((int) $matches[1]);
        }
    }

    protected function fillVatTu(int $index): void
    {
        if (! isset($this->pChiTiet[$index])) {
            return;
        }

        $maVt = (string) ($this->pChiTiet[$index]['ma_vt'] ?? '');
        if ('' === $maVt) {
            return;
        }

        $rows = AsINGetDMVT::call([
            'pMa_cty'   => SModel::CTY,
            'pMa_vt'    => $maVt,
            'pStruct'   => '0',
            'pLanguage' => 'vi-VN',
        ]);

        $vt = $rows->first();
        if (null === $vt) {
            return;
        }

        $row = &$this->pChiTiet[$index];
        $row['ten_vt'] = (string) ($vt->ten_vt ?? '');
        $row['dvt'] = (string) ($vt->dvt ?? $vt->dvt_mua ?? '');
        $row['ma_thue'] = (string) ($vt->ma_thue ?? $row['ma_thue'] ?? '');
        $row['ts_gtgt'] = (float) ($vt->ts_gtgt ?? $row['ts_gtgt'] ?? 0);
        $row['tk_vt'] = (string) ($vt->tk_vt ?? '');
        $row['tk_gv'] = (string) ($vt->tk_gv ?? '');
        $row['tk_dt'] = (string) ($vt->tk_dt ?? '');
        $row['tk_ck'] = (string) ($vt->tk_ck ?? '');
        $row['gia_nt0'] = (float) ($vt->gia_nt0 ?? $row['gia_nt0'] ?? 0);
        $row['ma_vitri'] = (string) ($vt->ma_vitri ?? '');

        if ('' === (string) ($row['ma_kho'] ?? '') && ! empty($vt->ma_kho)) {
            $row['ma_kho'] = (string) $vt->ma_kho;
            $this->fillKho($index);
        }

        $this->calculateChiTietRow($index);
    }

    protected function fillKho(int $index): void
    {
        if (! isset($this->pChiTiet[$index])) {
            return;
        }

        $maKho = (string) ($this->pChiTiet[$index]['ma_kho'] ?? '');
        if ('' === $maKho) {
            return;
        }

        $rows = AsINGetDMKHO::call([
            'pMa_cty' => SModel::CTY,
            'pMa_kho' => $maKho,
            'pStruct' => '0',
        ]);
        $kho = $rows->first();
        if (null !== $kho) {
            $this->pChiTiet[$index]['ten_kho'] = (string) ($kho->ten_kho ?? '');
        }
    }

    public function addChiPhiRow(): void
    {
        $this->pChiPhi[] = [
            'stt_rec0' => '',
            'ma_cp' => '',
            'ten_cp' => '',
            'tt_pb' => '1',
            'tien_cp_nt' => 0,
            'tien_cp' => 0,
            'ts_gtgt' => 0,
            'thue_gtgt_nt' => 0,
            'thue_gtgt' => 0,
            'tt_nt' => 0,
            'tt' => 0,
            'ma_bp' => '',
            'ma_phi' => '',
            'ma_spct' => '',
            'ma_lo' => '',
        ];
    }

    public function removeChiPhiRow(int $index): void
    {
        unset($this->pChiPhi[$index]);
        $this->pChiPhi = array_values($this->pChiPhi);
        $this->recalculateTotals();
    }

    public function calculateChiTietRow(int $index): void
    {
        if (! isset($this->pChiTiet[$index])) {
            return;
        }

        $row = &$this->pChiTiet[$index];
        $row['tien_nt0'] = round($row['so_luong'] * $row['gia_nt0'], 2);
        $row['tien0'] = round($row['so_luong'] * $row['gia0'], 2);
        $row['ck_nt'] = round($row['tien_nt0'] * ($row['tl_ck'] / 100), 2);
        $row['ck'] = round($row['tien0'] * ($row['tl_ck'] / 100), 2);
        $row['thue_gtgt_nt'] = round(($row['tien_nt0'] - $row['ck_nt'] + $row['tien_cp_nt']) * ($row['ts_gtgt'] / 100), 2);
        $row['thue_gtgt'] = round(($row['tien0'] - $row['ck'] + $row['tien_cp']) * ($row['ts_gtgt'] / 100), 2);
        $this->recalculateTotals();
    }

    public function calculateChiPhiRow(int $index): void
    {
        if (! isset($this->pChiPhi[$index])) {
            return;
        }

        $row = &$this->pChiPhi[$index];
        $row['thue_gtgt_nt'] = round($row['tien_cp_nt'] * ($row['ts_gtgt'] / 100), 2);
        $row['thue_gtgt'] = round($row['tien_cp'] * ($row['ts_gtgt'] / 100), 2);
        $row['tt_nt'] = round($row['tien_cp_nt'] + $row['thue_gtgt_nt'], 2);
        $row['tt'] = round($row['tien_cp'] + $row['thue_gtgt'], 2);
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        $this->pT_tien_nt0 = round(array_sum(array_column($this->pChiTiet, 'tien_nt0')), 2);
        $this->pT_tien0 = round(array_sum(array_column($this->pChiTiet, 'tien0')), 2);
        $this->pT_ck_nt = round(array_sum(array_column($this->pChiTiet, 'ck_nt')), 2);
        $this->pT_ck = round(array_sum(array_column($this->pChiTiet, 'ck')), 2);
        $this->pT_thue_nt = round(array_sum(array_column($this->pChiTiet, 'thue_gtgt_nt')), 2);
        $this->pT_thue = round(array_sum(array_column($this->pChiTiet, 'thue_gtgt')), 2);
        $this->pT_cp_nt = round(array_sum(array_column($this->pChiPhi, 'tien_cp_nt')), 2);
        $this->pT_cp = round(array_sum(array_column($this->pChiPhi, 'tien_cp')), 2);
        $this->pT_so_luong = round(array_sum(array_column($this->pChiTiet, 'so_luong')), 2);
        $this->pT_tt_nt = round($this->pT_tien_nt0 - $this->pT_ck_nt + $this->pT_thue_nt + $this->pT_cp_nt, 2);
        $this->pT_tt = round($this->pT_tien0 - $this->pT_ck + $this->pT_thue + $this->pT_cp, 2);
    }

    public function submit(): void
    {
        $this->validate([
            'pMa_kh' => 'required|string',
            'pSo_hd' => 'nullable|string',
            'pMa_nt' => 'required|string',
            'pNgay_hd' => 'required|date',
            'pNgay_ct' => 'required|date',
            'pTy_gia' => 'required|numeric|gt:0',
        ], [
            'pMa_kh.required' => 'Nhà cung cấp không được trống',
            'pMa_nt.required' => 'Mã ngoại tệ không được để trống',
            'pNgay_hd.required' => 'Ngày hóa đơn không được trống',
            'pNgay_ct.required' => 'Ngày chứng từ không được trống',
            'pTy_gia.gt' => 'Tỷ giá phải lớn hơn 0',
        ]);

        try {
            DB::beginTransaction();

            $this->ensureSttRecBeforeSave();

            $this->recalculateTotals();

            $result = AsPOSavePO3::call([
                // Header
                'pStt_rec' => $this->pStt_rec,
                'pMa_ct' => self::MA_CT,
                'pMa_kh' => $this->pMa_kh,
                'pDia_chi' => $this->pDia_chi,
                'pNguoi_gd' => $this->pNguoi_gd,
                'pDien_giai' => $this->pDien_giai,
                'pSo_hd' => $this->pSo_hd,
                'pNgay_hd' => $this->pNgay_hd,
                'pNgay_ct' => $this->pNgay_ct,
                'pNgay_lct' => $this->pNgay_lct,
                'pMa_httt' => $this->pMa_httt,
                'pMa_nt' => $this->pMa_nt,
                'pTy_gia' => $this->pTy_gia,
                'pTk_pt' => $this->pTk_pt,
                'pTk_thue' => $this->pTk_thue,
                // Totals
                'pT_tien_nt0' => $this->pT_tien_nt0,
                'pT_tien0' => $this->pT_tien0,
                'pT_cp_nt' => $this->pT_cp_nt,
                'pT_cp' => $this->pT_cp,
                'pT_thue_nt' => $this->pT_thue_nt,
                'pT_thue' => $this->pT_thue,
                'pT_ck_nt' => $this->pT_ck_nt,
                'pT_ck' => $this->pT_ck,
                'pT_tt_nt' => $this->pT_tt_nt,
                'pT_tt' => $this->pT_tt,
                'pT_so_luong' => $this->pT_so_luong,
                // Details + Costs
                'pChiTiet' => json_encode($this->pChiTiet),
                'pChiPhi' => json_encode($this->pChiPhi),
            ]);
            $this->assertProcedureSuccess($result);

            DB::commit();

            session()->flash('success', 'Đã lưu hóa đơn mua hàng.');

            $this->redirect(simbaroute('po.vch.povchpo3'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi khi lưu hóa đơn: '.$e->getMessage());
        }
    }

    public function deleteInvoice(): void
    {
        if (empty($this->pStt_rec)) {
            session()->flash('error', 'Chưa có chứng từ để xóa.');

            return;
        }

        DB::beginTransaction();

        try {
            $result = AsPODeletePO3::call([
                'pMa_cty'  => SModel::CTY,
                'pStt_rec' => $this->pStt_rec,
            ]);
            $this->assertProcedureSuccess($result);

            DB::commit();
            session()->flash('success', 'Đã xóa hóa đơn mua hàng.');
            $this->redirect(simbaroute('po.vch.povchpo3'));
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            session()->flash('error', 'Lỗi khi xóa hóa đơn: ' . $exception->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::po.vch.povchpo3-edit', [
            'mode' => $this->pMode,
        ])->layout('catalog::layouts.app');
    }

    protected function ensureSttRecBeforeSave(): void
    {
        if (! empty($this->pStt_rec)) {
            return;
        }

        $sttRecResult = AsGetSttRec::call([
            'pMa_cty' => SModel::CTY,
            'pMa_ct' => self::MA_CT,
        ]);

        $this->pStt_rec = $sttRecResult->first()->pStt_rec ?? null;

        if (empty($this->pStt_rec)) {
            throw new \Exception('Không thể sinh stt_rec cho hóa đơn mua hàng PO3. Vui lòng kiểm tra AsGetSttRec.');
        }
    }

    protected function assertProcedureSuccess(Collection $result): void
    {
        $row = $result->first();
        $pRet = \is_array($row) ? ($row['pRet'] ?? null) : ($row->pRet ?? null);

        if (null !== $pRet && 0 !== (int) $pRet) {
            throw new \RuntimeException('Stored procedure trả về mã lỗi ' . (int) $pRet . '.');
        }
    }

    protected function dateValue(mixed $value): ?string
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        $date = \Illuminate\Support\Carbon::parse($value);

        return $date->year >= 1901 ? $date->format('Y-m-d') : null;
    }
}
