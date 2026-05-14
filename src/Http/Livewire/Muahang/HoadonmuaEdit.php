<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-14 16:55:01
 */

namespace Diepxuan\Catalog\Http\Livewire\Muahang;

use Diepxuan\Simba\Models\ArDmKh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
use Diepxuan\Simba\StoredProcedures\AsGetSttRec;
use Diepxuan\Simba\StoredProcedures\AsPOGetPO3;
use Diepxuan\Simba\StoredProcedures\AsPOSavePO3;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Form nhập/sửa hóa đơn mua hàng (PO3).
 */
class HoadonmuaEdit extends Component
{
    const MA_CT = 'PO3';

    // Header
    public ?string $pStt_rec   = null;
    public string $pMode       = 'create';
    public ?string $pNgay_ct   = null;
    public string $pSo_ct      = '';
    public string $pSo_hd      = '';
    public ?string $pNgay_hd   = null;
    public ?string $pMa_kh     = null;
    public string $pTen_kh     = '';
    public string $pNguoi_gd   = '';
    public string $pDia_chi    = '';
    public string $pMa_so_thue = '';
    public string $pDien_giai  = '';
    public ?string $pMa_httt   = null;
    public string $pMa_nt      = 'VND';
    public float $pTy_gia      = 1;
    public string $pTk_pt      = '';
    public string $pTk_thue    = '';

    // Totals
    public float $pT_tien_nt0 = 0;
    public float $pT_tien0    = 0;
    public float $pT_cp_nt    = 0;
    public float $pT_cp       = 0;
    public float $pT_thue_nt  = 0;
    public float $pT_thue     = 0;
    public float $pT_ck_nt    = 0;
    public float $pT_ck       = 0;
    public float $pT_tt_nt    = 0;
    public float $pT_tt       = 0;
    public float $pT_so_luong = 0;

    // Details & Costs
    public array $pChiTiet = [];
    public array $pChiPhi  = [];

    // UI state
    public int $pActiveTab = 0; // 0=Chi tiết, 1=Chi phí, 2=Hạch toán

    public function mount(?string $stt_rec = null): void
    {
        $this->pNgay_ct = now()->format('Y-m-d');
        $this->pNgay_hd = now()->format('Y-m-d');

        if ($stt_rec) {
            $this->pMode = 'edit';
            $this->loadInvoice($stt_rec);
        } else {
            // Sinh số chứng từ mới
            $this->pSo_ct = AsGetSoCt::call([
                'pMa_ct'   => self::MA_CT,
                'pNgay_Ct' => $this->pNgay_ct,
            ]);
        }
    }

    public function loadInvoice(string $stt_rec): void
    {
        $result = AsPOGetPO3::call([
            'pStt_rec' => $stt_rec,
        ]);

        if ($result->isEmpty()) {
            session()->flash('error', 'Không tìm thấy hóa đơn.');
            $this->redirectRoute('muahang.hoadonmua');

            return;
        }

        // Dataset 0: Header
        $header            = $result[0];
        $this->pStt_rec    = $header->stt_rec ?? null;
        $this->pNgay_ct    = optional($header->ngay_ct)->format('Y-m-d');
        $this->pSo_ct      = $header->so_ct ?? '';
        $this->pSo_hd      = $header->so_hd ?? '';
        $this->pNgay_hd    = optional($header->ngay_hd)->format('Y-m-d');
        $this->pMa_kh      = $header->ma_kh;
        $this->pTen_kh     = $header->ten_kh ?? '';
        $this->pNguoi_gd   = $header->nguoi_gd ?? '';
        $this->pDia_chi    = $header->dia_chi ?? '';
        $this->pMa_so_thue = $header->ma_so_thue ?? '';
        $this->pDien_giai  = $header->dien_giai ?? '';
        $this->pMa_httt    = $header->ma_httt;
        $this->pMa_nt      = $header->ma_nt ?? 'VND';
        $this->pTy_gia     = (float) ($header->ty_gia ?? 1);
        $this->pTk_pt      = $header->tk_pt ?? '';
        $this->pTk_thue    = $header->tk_thue ?? '';

        $this->pT_tien_nt0 = (float) ($header->t_tien_nt0 ?? 0);
        $this->pT_tien0    = (float) ($header->t_tien0 ?? 0);
        $this->pT_cp_nt    = (float) ($header->t_cp_nt ?? 0);
        $this->pT_cp       = (float) ($header->t_cp ?? 0);
        $this->pT_thue_nt  = (float) ($header->t_thue_nt ?? 0);
        $this->pT_thue     = (float) ($header->t_thue ?? 0);
        $this->pT_ck_nt    = (float) ($header->t_ck_nt ?? 0);
        $this->pT_ck       = (float) ($header->t_ck ?? 0);
        $this->pT_tt_nt    = (float) ($header->t_tt_nt ?? 0);
        $this->pT_tt       = (float) ($header->t_tt ?? 0);
        $this->pT_so_luong = (float) ($header->t_so_luong ?? 0);

        // Dataset 1: Chi tiết vật tư
        if (isset($result[1])) {
            $this->pChiTiet = collect($result[1])->map(static fn ($ct) => [
                'stt_rec0'     => $ct->stt_rec0 ?? '',
                'ma_vt'        => $ct->ma_vt ?? '',
                'ten_vt'       => $ct->ten_vt ?? '',
                'dvt'          => $ct->dvt ?? '',
                'ma_kho'       => $ct->ma_kho ?? '',
                'so_luong'     => (float) ($ct->so_luong ?? 0),
                'gia_nt0'      => (float) ($ct->gia_nt0 ?? 0),
                'gia0'         => (float) ($ct->gia0 ?? 0),
                'tien_nt0'     => (float) ($ct->tien_nt0 ?? 0),
                'tien0'        => (float) ($ct->tien0 ?? 0),
                'ts_gtgt'      => (float) ($ct->ts_gtgt ?? 0),
                'thue_gtgt_nt' => (float) ($ct->thue_gtgt_nt ?? 0),
                'thue_gtgt'    => (float) ($ct->thue_gtgt ?? 0),
                'tl_ck'        => (float) ($ct->tl_ck ?? 0),
                'ck_nt'        => (float) ($ct->ck_nt ?? 0),
                'ck'           => (float) ($ct->ck ?? 0),
                'tien_cp_nt'   => (float) ($ct->tien_cp_nt ?? 0),
                'tien_cp'      => (float) ($ct->tien_cp ?? 0),
                'tk_vt'        => $ct->tk_vt ?? '',
                'tk_thue'      => $ct->tk_thue ?? '',
                'ma_bp'        => $ct->ma_bp ?? '',
                'ma_phi'       => $ct->ma_phi ?? '',
                'ma_spct'      => $ct->ma_spct ?? '',
            ])->toArray();
        }

        // Dataset 2: Chi phí
        if (isset($result[2])) {
            $this->pChiPhi = collect($result[2])->map(static fn ($cp) => [
                'stt_rec0'     => $cp->stt_rec0 ?? '',
                'ma_cp'        => $cp->ma_cp ?? '',
                'ten_cp'       => $cp->ten_cp ?? '',
                'tt_pb'        => $cp->tt_pb ?? '1',
                'tien_cp_nt'   => (float) ($cp->tien_cp_nt ?? 0),
                'tien_cp'      => (float) ($cp->tien_cp ?? 0),
                'ts_gtgt'      => (float) ($cp->ts_gtgt ?? 0),
                'thue_gtgt_nt' => (float) ($cp->thue_gtgt_nt ?? 0),
                'thue_gtgt'    => (float) ($cp->thue_gtgt ?? 0),
                'tt_nt'        => (float) ($cp->tt_nt ?? 0),
                'tt'           => (float) ($cp->tt ?? 0),
                'ma_bp'        => $cp->ma_bp ?? '',
                'ma_phi'       => $cp->ma_phi ?? '',
                'ma_spct'      => $cp->ma_spct ?? '',
                'ma_lo'        => $cp->ma_lo ?? '',
            ])->toArray();
        }
    }

    public function updatedPMaKh($value): void
    {
        if (empty($value)) {
            $this->pTen_kh     = '';
            $this->pDia_chi    = '';
            $this->pMa_so_thue = '';
            $this->pNguoi_gd   = '';

            return;
        }

        $ncc = ArDmKh::find($value);
        if ($ncc) {
            $this->pTen_kh     = $ncc->ten_kh ?? '';
            $this->pDia_chi    = $ncc->dia_chi ?? '';
            $this->pMa_so_thue = $ncc->ma_so_thue ?? '';
            $this->pNguoi_gd   = $ncc->nguoi_gd ?? '';
            if ($ncc->ma_httt_po) {
                $this->pMa_httt = $ncc->ma_httt_po;
            }
        }
    }

    public function addChiTietRow(): void
    {
        $this->pChiTiet[] = [
            'stt_rec0'     => '',
            'ma_vt'        => '',
            'ten_vt'       => '',
            'dvt'          => '',
            'ma_kho'       => '',
            'so_luong'     => 0,
            'gia_nt0'      => 0,
            'gia0'         => 0,
            'tien_nt0'     => 0,
            'tien0'        => 0,
            'ts_gtgt'      => 0,
            'thue_gtgt_nt' => 0,
            'thue_gtgt'    => 0,
            'tl_ck'        => 0,
            'ck_nt'        => 0,
            'ck'           => 0,
            'tien_cp_nt'   => 0,
            'tien_cp'      => 0,
            'tk_vt'        => '',
            'tk_thue'      => '',
            'ma_bp'        => '',
            'ma_phi'       => '',
            'ma_spct'      => '',
        ];
    }

    public function removeChiTietRow(int $index): void
    {
        unset($this->pChiTiet[$index]);
        $this->pChiTiet = array_values($this->pChiTiet);
        $this->recalculateTotals();
    }

    public function addChiPhiRow(): void
    {
        $this->pChiPhi[] = [
            'stt_rec0'     => '',
            'ma_cp'        => '',
            'ten_cp'       => '',
            'tt_pb'        => '1',
            'tien_cp_nt'   => 0,
            'tien_cp'      => 0,
            'ts_gtgt'      => 0,
            'thue_gtgt_nt' => 0,
            'thue_gtgt'    => 0,
            'tt_nt'        => 0,
            'tt'           => 0,
            'ma_bp'        => '',
            'ma_phi'       => '',
            'ma_spct'      => '',
            'ma_lo'        => '',
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
        if (!isset($this->pChiTiet[$index])) {
            return;
        }

        $row                 = &$this->pChiTiet[$index];
        $row['tien_nt0']     = round($row['so_luong'] * $row['gia_nt0'], 2);
        $row['tien0']        = round($row['so_luong'] * $row['gia0'], 2);
        $row['ck_nt']        = round($row['tien_nt0'] * ($row['tl_ck'] / 100), 2);
        $row['ck']           = round($row['tien0'] * ($row['tl_ck'] / 100), 2);
        $row['thue_gtgt_nt'] = round(($row['tien_nt0'] - $row['ck_nt'] + $row['tien_cp_nt']) * ($row['ts_gtgt'] / 100), 2);
        $row['thue_gtgt']    = round(($row['tien0'] - $row['ck'] + $row['tien_cp']) * ($row['ts_gtgt'] / 100), 2);
        $this->recalculateTotals();
    }

    public function calculateChiPhiRow(int $index): void
    {
        if (!isset($this->pChiPhi[$index])) {
            return;
        }

        $row                 = &$this->pChiPhi[$index];
        $row['thue_gtgt_nt'] = round($row['tien_cp_nt'] * ($row['ts_gtgt'] / 100), 2);
        $row['thue_gtgt']    = round($row['tien_cp'] * ($row['ts_gtgt'] / 100), 2);
        $row['tt_nt']        = round($row['tien_cp_nt'] + $row['thue_gtgt_nt'], 2);
        $row['tt']           = round($row['tien_cp'] + $row['thue_gtgt'], 2);
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        $this->pT_tien_nt0 = round(array_sum(array_column($this->pChiTiet, 'tien_nt0')), 2);
        $this->pT_tien0    = round(array_sum(array_column($this->pChiTiet, 'tien0')), 2);
        $this->pT_ck_nt    = round(array_sum(array_column($this->pChiTiet, 'ck_nt')), 2);
        $this->pT_ck       = round(array_sum(array_column($this->pChiTiet, 'ck')), 2);
        $this->pT_thue_nt  = round(array_sum(array_column($this->pChiTiet, 'thue_gtgt_nt')), 2);
        $this->pT_thue     = round(array_sum(array_column($this->pChiTiet, 'thue_gtgt')), 2);
        $this->pT_cp_nt    = round(array_sum(array_column($this->pChiPhi, 'tien_cp_nt')), 2);
        $this->pT_cp       = round(array_sum(array_column($this->pChiPhi, 'tien_cp')), 2);
        $this->pT_so_luong = round(array_sum(array_column($this->pChiTiet, 'so_luong')), 2);
        $this->pT_tt_nt    = round($this->pT_tien_nt0 - $this->pT_ck_nt + $this->pT_thue_nt + $this->pT_cp_nt, 2);
        $this->pT_tt       = round($this->pT_tien0 - $this->pT_ck + $this->pT_thue + $this->pT_cp, 2);
    }

    public function submit(): void
    {
        $this->validate([
            'pMa_kh'   => 'required|string',
            'pSo_hd'   => 'required|string',
            'pNgay_hd' => 'required|date',
            'pNgay_ct' => 'required|date',
        ], [
            'pMa_kh.required'   => 'Nhà cung cấp không được trống',
            'pSo_hd.required'   => 'Số hóa đơn không được trống',
            'pNgay_hd.required' => 'Ngày hóa đơn không được trống',
            'pNgay_ct.required' => 'Ngày chứng từ không được trống',
        ]);

        try {
            DB::beginTransaction();

            $this->ensureSttRecBeforeSave();

            $result = AsPOSavePO3::call([
                // Header
                'pStt_rec'   => $this->pStt_rec,
                'pMa_ct'     => self::MA_CT,
                'pMa_kh'     => $this->pMa_kh,
                'pDia_chi'   => $this->pDia_chi,
                'pNguoi_gd'  => $this->pNguoi_gd,
                'pDien_giai' => $this->pDien_giai,
                'pSo_hd'     => $this->pSo_hd,
                'pNgay_hd'   => $this->pNgay_hd,
                'pNgay_ct'   => $this->pNgay_ct,
                'pMa_httt'   => $this->pMa_httt,
                'pMa_nt'     => $this->pMa_nt,
                'pTy_gia'    => $this->pTy_gia,
                'pTk_pt'     => $this->pTk_pt,
                'pTk_thue'   => $this->pTk_thue,
                // Totals
                'pT_tien_nt0' => $this->pT_tien_nt0,
                'pT_tien0'    => $this->pT_tien0,
                'pT_cp_nt'    => $this->pT_cp_nt,
                'pT_cp'       => $this->pT_cp,
                'pT_thue_nt'  => $this->pT_thue_nt,
                'pT_thue'     => $this->pT_thue,
                'pT_ck_nt'    => $this->pT_ck_nt,
                'pT_ck'       => $this->pT_ck,
                'pT_tt_nt'    => $this->pT_tt_nt,
                'pT_tt'       => $this->pT_tt,
                'pT_so_luong' => $this->pT_so_luong,
                // Details
                'pChiTiet' => json_encode($this->pChiTiet),
                // Costs
                'pChiPhi' => json_encode($this->pChiPhi),
            ]);

            DB::commit();

            session()->flash('success', 'Đã lưu hóa đơn mua hàng.');
            $this->redirectRoute('muahang.hoadonmua');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi khi lưu hóa đơn: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::muahang.hoadonmua-edit', [
            'mode' => $this->pMode,
        ])->layout('catalog::layouts.app');
    }

    protected function ensureSttRecBeforeSave(): void
    {
        if (!empty($this->pStt_rec)) {
            return;
        }

        $sttRecResult = AsGetSttRec::call([
            'pMa_cty' => SModel::CTY,
            'pMa_ct'  => self::MA_CT,
        ]);

        $this->pStt_rec = $sttRecResult->first()->pStt_rec ?? null;

        if (empty($this->pStt_rec)) {
            throw new \Exception('Không thể sinh stt_rec cho hóa đơn mua hàng PO3. Vui lòng kiểm tra AsGetSttRec.');
        }
    }
}
