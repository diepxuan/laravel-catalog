<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-09
 */

namespace Diepxuan\Catalog\Http\Livewire\So\Vch;

use Diepxuan\Simba\Models\ArDmKh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsGetArkhInfo_SO;
use Diepxuan\Simba\StoredProcedures\AsGetDMHTTT;
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
use Diepxuan\Simba\StoredProcedures\AsGetSttRec;
use Diepxuan\Simba\StoredProcedures\AsINGetDMKHO;
use Diepxuan\Simba\StoredProcedures\AsINGetDMVT;
use Diepxuan\Simba\StoredProcedures\AsSODelCT3;
use Diepxuan\Simba\StoredProcedures\AsSODelPH3;
use Diepxuan\Simba\StoredProcedures\AsSOGetCT3;
use Diepxuan\Simba\StoredProcedures\AsSOGetPH3;
use Diepxuan\Simba\StoredProcedures\AsSOInsCT3;
use Diepxuan\Simba\StoredProcedures\AsSOInsPH3;
use Diepxuan\Simba\StoredProcedures\AsSOUpdPH3;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Form nhập/sửa hóa đơn bán hàng (SO3).
 *
 * Mapping:
 * - menuID : `06.10.08` (sysMenu, sysVoucherInfo ma_ct = `SO3`).
 * - DLL    : `SOVchSO3.dll` (form `frmSoVchSO3`).
 * - SP     : `asSOGetPH3` + `asSOGetCT3` (load); `asSOInsPH3`/`asSOUpdPH3`,
 *            `asSOInsCT3`/`asSODelCT3` (save); `asSODelPH3` (xóa).
 * - Route  : `_simba-source/so/vch/sovchso3/{stt_rec?}/edit`.
 *
 * Phân hệ SO3 không có SP save gộp như PO3. Khi sửa, framework .NET xóa toàn
 * bộ chi tiết theo `stt_rec` rồi insert lại nên Portal giữ đúng cơ chế đó.
 */
class Sovchso3Edit extends Component
{
    public const MA_CT = 'SO3';

    public ?string $pStt_rec = null;

    public string $pMode = 'create';

    public ?string $pNgay_ct = null;

    public ?string $pNgay_lct = null;

    public string $pSo_ct = '';

    public string $pSo_seri = '';

    public string $pMa_kh = '';

    public string $pTen_kh = '';

    public string $pTen_kh_vat = '';

    public string $pDia_chi_vat = '';

    public string $pMa_so_thue = '';

    public string $pNguoi_gd = '';

    public string $pDien_giai = '';

    public string $pMa_nt = 'VND';

    public float $pTy_gia = 1;

    public string $pTk_pt = '';

    public string $pTk_thue = '';

    public string $pTk_ck_ds = '';

    public ?string $pMa_httt = null;

    public string $pHttt = '';

    public string $pSo_px = '';

    public ?string $pNgay_px = null;

    public float $pGh_no = 0;

    public float $pDu13 = 0;

    public bool $pGia_dd = false;

    public bool $pCk_st = false;

    public bool $pGia_st = false;

    // Totals
    public float $pT_tien_nt2 = 0;

    public float $pT_tien2 = 0;

    public float $pT_ck_nt = 0;

    public float $pT_ck = 0;

    public float $pT_ck_ds_nt = 0;

    public float $pT_ck_ds = 0;

    public float $pT_thue_nt = 0;

    public float $pT_thue = 0;

    public float $pT_tt_nt = 0;

    public float $pT_tt = 0;

    public float $pT_so_luong = 0;

    public float $pTl_ck_ds = 0;

    /** @var list<array<string, mixed>> */
    public array $pChiTiet = [];

    /** @var list<string> */
    protected array $existingDetailKeys = [];

    public function mount(?string $id = null): void
    {
        $this->pNgay_ct  = now()->format('Y-m-d');
        $this->pNgay_lct = now()->format('Y-m-d');

        if ($id) {
            $this->pMode    = 'edit';
            $this->loadInvoice($id);

            return;
        }

        $this->pSo_ct = AsGetSoCt::call([
            'pMa_ct'   => self::MA_CT,
            'pNgay_Ct' => $this->pNgay_ct,
        ]);
    }

    public function loadInvoice(string $stt_rec): void
    {
        $header = AsSOGetPH3::call([
            'pMa_cty'  => SModel::CTY,
            'pStt_rec' => $stt_rec,
            'pMa_ct'   => self::MA_CT,
        ])->first();

        if (null === $header) {
            session()->flash('error', 'Không tìm thấy hóa đơn bán hàng.');

            return;
        }

        $h = AsSOGetPH3::normalizeHeader($header);
        $this->pStt_rec       = $h['stt_rec'];
        $this->pNgay_ct       = $this->dateValue($h['ngay_ct']);
        $this->pNgay_lct      = $this->dateValue($h['ngay_lct']);
        $this->pSo_ct         = $h['so_ct'];
        $this->pSo_seri       = $h['so_seri'];
        $this->pMa_kh         = $h['ma_kh'];
        $this->pTen_kh        = $h['ten_kh'];
        $this->pTen_kh_vat    = $h['ten_kh_vat'];
        $this->pDia_chi_vat   = $h['dia_chi_vat'];
        $this->pMa_so_thue    = $h['ma_so_thue'];
        $this->pNguoi_gd      = $h['nguoi_gd'];
        $this->pDien_giai     = $h['dien_giai'];
        $this->pMa_nt         = $h['ma_nt'] ?: 'VND';
        $this->pTy_gia        = $h['ty_gia'] > 0 ? $h['ty_gia'] : 1;
        $this->pTk_pt         = $h['tk_pt'];
        $this->pTk_thue       = $h['tk_thue'];
        $this->pTk_ck_ds      = $h['tk_ck_ds'];
        $this->pMa_httt       = '' !== $h['ma_httt'] ? $h['ma_httt'] : null;
        $this->pHttt          = $h['httt'];
        $this->pSo_px         = $h['so_px'];
        $this->pNgay_px       = $this->normalizeOptionalDate($h['ngay_px']);
        $this->pGia_dd        = (bool) ($h['gia_dd'] ?? false);
        $this->pCk_st         = (bool) ($h['ck_st'] ?? false);
        $this->pGia_st        = (bool) ($h['gia_st'] ?? false);
        $this->pT_tien_nt2    = $h['t_tien_nt2'];
        $this->pT_tien2       = $h['t_tien2'];
        $this->pT_ck_nt       = $h['t_ck_nt'];
        $this->pT_ck          = $h['t_ck'];
        $this->pT_ck_ds_nt    = $h['t_ck_ds_nt'];
        $this->pT_ck_ds       = $h['t_ck_ds'];
        $this->pT_thue_nt     = $h['t_thue_nt'];
        $this->pT_thue        = $h['t_thue'];
        $this->pT_tt_nt       = $h['t_tt_nt'];
        $this->pT_tt          = $h['t_tt'];
        $this->pT_so_luong    = $h['t_so_luong'];
        $this->pTl_ck_ds      = $h['tl_ck_ds'];

        $this->pChiTiet = AsSOGetCT3::call([
            'pMa_cty'  => SModel::CTY,
            'pStt_rec' => $stt_rec,
            'pStruct'  => '0',
        ])->map(static fn (mixed $row): array => AsSOGetCT3::normalizeDetail($row))
            ->filter(static fn (array $row): bool => '' !== (string) ($row['stt_rec0'] ?? ''))
            ->values()
            ->all();

        $this->existingDetailKeys = array_values(array_filter(
            array_column($this->pChiTiet, 'stt_rec0'),
            static fn (mixed $value): bool => '' !== (string) $value
        ));

        $this->loadCustomerBalance();
    }

    public function updatedPMaKh(mixed $value): void
    {
        if (null === $value || '' === trim((string) $value)) {
            $this->resetCustomerInfo();

            return;
        }

        if ($value === $this->pMa_kh && '' !== $this->pTen_kh) {
            return;
        }

        $customer = ArDmKh::find((string) $value);
        if (null === $customer) {
            $this->resetCustomerInfo();
            session()->flash('warning', 'Không tìm thấy khách hàng trong danh mục ARDMKH.');

            return;
        }

        $this->pTen_kh      = (string) ($customer->ten_kh ?? '');
        $this->pTen_kh_vat  = $this->pTen_kh;
        $this->pDia_chi_vat = (string) ($customer->dia_chi ?? '');
        $this->pMa_so_thue  = (string) ($customer->ma_so_thue ?? '');
        $this->pNguoi_gd    = (string) ($customer->nguoi_gd ?? '');
        $this->pGh_no       = (float) ($customer->gh_no ?? 0);

        if (!empty($customer->ma_httt)) {
            $this->pMa_httt = (string) $customer->ma_httt;
            $this->fillTaiKhoanFromHttt((string) $customer->ma_httt);
        }

        $this->loadCustomerBalance();
    }

    public function updatedPMaHttt(mixed $value): void
    {
        if (null === $value || '' === trim((string) $value)) {
            $this->resetTaiKhoanFromHttt();

            return;
        }

        $this->fillTaiKhoanFromHttt((string) $value);
    }

    #[On('ngoaite-updated')]
    public function onNgoaiteUpdated(?string $ma_nt = null, ?string $ten_nt = null, float $ty_gia = 1): void
    {
        $this->pMa_nt = (string) ($ma_nt ?? 'VND');
        $this->pTy_gia = $ty_gia > 0 ? $ty_gia : 1;
        $this->recalculateAll();
    }

    public function updatedPTyGia(): void
    {
        if ($this->pTy_gia <= 0) {
            $this->pTy_gia = 1;
        }

        $this->recalculateAll();
    }

    public function updated($property, $value): void
    {
        if (preg_match('/^pChiTiet\.(\d+)\.ma_vt$/', (string) $property, $matches)) {
            $this->fillVatTu((int) $matches[1]);

            return;
        }

        if (preg_match('/^pChiTiet\.(\d+)\.ma_kho$/', (string) $property, $matches)) {
            $this->fillKho((int) $matches[1]);
        }
    }

    public function updatedPTlCkDs(): void
    {
        $this->recalculateAll();
        $this->recalculateCkDs();
        $this->recalculateAll();
    }

    public function addChiTietRow(): void
    {
        $this->pChiTiet[] = $this->emptyDetailRow();
    }

    public function removeChiTietRow(int $index): void
    {
        unset($this->pChiTiet[$index]);
        $this->pChiTiet = array_values($this->pChiTiet);
        $this->recalculateTotals();
    }

    public function calculateChiTietRow(int $index): void
    {
        if (!isset($this->pChiTiet[$index])) {
            return;
        }

        $row = &$this->pChiTiet[$index];
        $row['gia2']      = round((float) $row['gia_nt2'] * $this->pTy_gia, 2);
        $row['tien_nt2']  = round((float) $row['so_luong'] * (float) $row['gia_nt2'], 2);
        $row['tien2']     = round((float) $row['so_luong'] * (float) $row['gia2'], 2);
        $row['tien_ck_nt'] = round((float) $row['tien_nt2'] * ((float) $row['tl_ck'] / 100), 2);
        $row['tien_ck']    = round((float) $row['tien2'] * ((float) $row['tl_ck'] / 100), 2);
        $row['thue_gtgt_nt'] = round(((float) $row['tien_nt2'] - (float) $row['tien_ck_nt'] - (float) $row['ck_ds_nt']) * ((float) $row['ts_gtgt'] / 100), 2);
        $row['thue_gtgt']    = round(((float) $row['tien2'] - (float) $row['tien_ck'] - (float) $row['ck_ds']) * ((float) $row['ts_gtgt'] / 100), 2);
        $row['tt_nt']        = round((float) $row['tien_nt2'] - (float) $row['tien_ck_nt'] - (float) $row['ck_ds_nt'] + (float) $row['thue_gtgt_nt'], 2);
        $row['tt']           = round((float) $row['tien2'] - (float) $row['tien_ck'] - (float) $row['ck_ds'] + (float) $row['thue_gtgt'], 2);

        $this->recalculateTotals();
    }

    public function fillVatTu(int $index): void
    {
        if (!isset($this->pChiTiet[$index])) {
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
        $row['ten_vt']   = (string) ($vt->ten_vt ?? '');
        $row['dvt']      = (string) ($vt->dvt ?? '');
        $row['ma_thue']  = (string) ($vt->ma_thue ?? '');
        $row['ts_gtgt']  = (float) ($vt->ts_gtgt ?? $row['ts_gtgt']);
        $row['tk_vt']    = (string) ($vt->tk_vt ?? '');
        $row['tk_dt']    = (string) ($vt->tk_dt ?? '');
        $row['tk_gv']    = (string) ($vt->tk_gv ?? '');
        $row['tk_ck']    = (string) ($vt->tk_ck ?? '');
        $row['gia_nt2']  = (float) ($vt->gia_nt2 ?? $row['gia_nt2']);
        $row['ton_kho']  = (float) ($vt->ton_kho ?? 0);
        $this->calculateChiTietRow($index);
    }

    public function fillKho(int $index): void
    {
        if (!isset($this->pChiTiet[$index])) {
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

    public function submit(): void
    {
        $this->validate([
            'pMa_kh'    => ['required', 'string'],
            'pSo_ct'    => ['required', 'string'],
            'pMa_nt'    => ['required', 'string'],
            'pNgay_ct'  => ['required', 'date'],
            'pNgay_lct' => ['required', 'date'],
            'pTy_gia'   => ['required', 'numeric', 'gt:0'],
        ], [
            'pMa_kh.required'   => 'Khách hàng không được để trống.',
            'pSo_ct.required'   => 'Số hóa đơn không được để trống.',
            'pMa_nt.required'   => 'Mã ngoại tệ không được để trống.',
            'pNgay_ct.required' => 'Ngày hóa đơn không được để trống.',
            'pNgay_lct.required' => 'Ngày lập chứng từ không được để trống.',
            'pTy_gia.gt'        => 'Tỷ giá phải lớn hơn 0.',
        ]);

        DB::beginTransaction();

        try {
            $this->ensureSttRecBeforeSave();
            $this->recalculateAll();

            $header = $this->headerPayload();
            $result = 'create' === $this->pMode
                ? AsSOInsPH3::call($header)
                : AsSOUpdPH3::call($header);
            $this->assertProcedureSuccess($result);

            $this->saveDetails();

            DB::commit();
            session()->flash('success', 'Đã lưu hóa đơn bán hàng.');
            $this->redirect(route('so.vch.sovchso3'));
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            session()->flash('error', 'Lỗi khi lưu hóa đơn: ' . $exception->getMessage());
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
            $result = AsSODelPH3::call([
                'pMa_cty'  => SModel::CTY,
                'pStt_rec' => $this->pStt_rec,
            ]);
            $this->assertProcedureSuccess($result);

            DB::commit();
            session()->flash('success', 'Đã xóa hóa đơn bán hàng.');
            $this->redirect(route('so.vch.sovchso3'));
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            session()->flash('error', 'Lỗi khi xóa hóa đơn: ' . $exception->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::so.vch.sovchso3-edit', [
            'mode' => $this->pMode,
        ])->layout('catalog::layouts.app');
    }

    /**
     * @return array<string, mixed>
     */
    protected function headerPayload(): array
    {
        $user = auth()->user()->name ?? 'system';

        return [
            'pMa_cty'     => SModel::CTY,
            'pStt_rec'    => $this->pStt_rec,
            'pMa_ct'      => self::MA_CT,
            'pMa_gd'      => '',
            'pSo_seri'    => $this->pSo_seri,
            'pSo_ct'      => $this->pSo_ct,
            'pNgay_ct'    => $this->pNgay_ct,
            'pNgay_lct'   => $this->pNgay_lct,
            'pMa_kh'      => $this->pMa_kh,
            'pTen_kh_vat' => $this->stringValue($this->pTen_kh_vat),
            'pDia_chi_vat' => $this->stringValue($this->pDia_chi_vat),
            'pMa_so_thue' => $this->stringValue($this->pMa_so_thue),
            'pNguoi_gd'   => $this->stringValue($this->pNguoi_gd),
            'pDien_giai'  => $this->stringValue($this->pDien_giai),
            'pMa_nt'      => $this->pMa_nt,
            'pTy_gia'     => $this->pTy_gia,
            'pTk_pt'      => $this->pTk_pt,
            'pTk_thue'    => $this->pTk_thue,
            'pTk_ck_ds'   => $this->pTk_ck_ds,
            'pMa_httt'    => $this->pMa_httt,
            'pHttt'       => $this->pHttt,
            'pGia_dd'     => $this->pGia_dd ? 1 : 0,
            'pCk_st'      => $this->pCk_st ? 1 : 0,
            'pGia_st'     => $this->pGia_st ? 1 : 0,
            'pSo_px'      => $this->pSo_px,
            'pNgay_px'    => $this->pNgay_px,
            'pT_tien'     => $this->pT_tien2,
            'pT_tien_nt'  => $this->pT_tien_nt2,
            'pT_tien2'    => $this->pT_tien2,
            'pT_tien_nt2' => $this->pT_tien_nt2,
            'pT_thue'     => $this->pT_thue,
            'pT_thue_nt'  => $this->pT_thue_nt,
            'pTl_ck_ds'   => $this->pTl_ck_ds,
            'pT_ck'       => $this->pT_ck,
            'pT_ck_nt'    => $this->pT_ck_nt,
            'pT_ck_ds'    => $this->pT_ck_ds,
            'pT_ck_ds_nt' => $this->pT_ck_ds_nt,
            'pT_tt'       => $this->pT_tt,
            'pT_tt_nt'    => $this->pT_tt_nt,
            'pt_so_luong' => $this->pT_so_luong,
            'pTao_tu_px'  => $this->pGia_dd ? 1 : 0,
            'pTrang_thai' => '',
            'pPost2gl'    => '',
            'pPost2in'    => '',
            'pLUser'      => $user,
        ];
    }

    protected function saveDetails(): void
    {
        if ('edit' === $this->pMode && ! empty($this->pStt_rec)) {
            $result = AsSODelCT3::call([
                'pMa_cty'  => SModel::CTY,
                'pStt_rec' => $this->pStt_rec,
            ]);
            $this->assertProcedureSuccess($result);
            $this->existingDetailKeys = [];
        }

        $nextSttRec0 = 1;
        foreach ($this->pChiTiet as $index => $row) {
            while (\in_array((string) $nextSttRec0, $this->existingDetailKeys, true)) {
                $nextSttRec0++;
            }

            $sttRec0 = (string) $nextSttRec0;
            $this->pChiTiet[$index]['stt_rec0'] = $sttRec0;
            $result = AsSOInsCT3::call($this->detailPayload($row, $sttRec0));
            $this->assertProcedureSuccess($result);
            $nextSttRec0++;
        }
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    protected function detailPayload(array $row, string $sttRec0): array
    {
        return [
            'pMa_cty'      => SModel::CTY,
            'pStt_rec'     => $this->pStt_rec,
            'pStt_rec0'    => $sttRec0,
            'pStt_rec_dh'  => (string) ($row['stt_rec_dh'] ?? ''),
            'pStt_rec0_dh' => (string) ($row['stt_rec0_dh'] ?? ''),
            'pStt_rec_px'  => (string) ($row['stt_rec_px'] ?? ''),
            'pStt_rec0_px' => (string) ($row['stt_rec0_px'] ?? ''),
            'pSo_dh'       => (string) ($row['so_dh'] ?? ''),
            'pSo_px'       => (string) ($row['so_px'] ?? ''),
            'pMa_vt'       => (string) ($row['ma_vt'] ?? ''),
            'pTen_vt'      => (string) ($row['ten_vt'] ?? ''),
            'pDvt'         => (string) ($row['dvt'] ?? ''),
            'pSo_luong'    => (float) ($row['so_luong'] ?? 0),
            'pSo_luong_qd' => (float) ($row['so_luong_qd'] ?? 0),
            'pGia_nt2'     => (float) ($row['gia_nt2'] ?? 0),
            'pGia2'        => (float) ($row['gia2'] ?? 0),
            'pTien_nt2'    => (float) ($row['tien_nt2'] ?? 0),
            'pTien2'       => (float) ($row['tien2'] ?? 0),
            'pTl_ck'       => (float) ($row['tl_ck'] ?? 0),
            'pTien_ck_nt'  => (float) ($row['tien_ck_nt'] ?? 0),
            'pTien_ck'     => (float) ($row['tien_ck'] ?? 0),
            'pCk_ds'       => (float) ($row['ck_ds'] ?? 0),
            'pCk_ds_nt'    => (float) ($row['ck_ds_nt'] ?? 0),
            'pMa_thue'     => (string) ($row['ma_thue'] ?? ''),
            'pTs_gtgt'     => (float) ($row['ts_gtgt'] ?? 0),
            'pThue_gtgt_nt' => (float) ($row['thue_gtgt_nt'] ?? 0),
            'pThue_gtgt'   => (float) ($row['thue_gtgt'] ?? 0),
            'pTt_nt'       => (float) ($row['tt_nt'] ?? 0),
            'pTt'          => (float) ($row['tt'] ?? 0),
            'pTk_pt'       => (string) ($row['tk_pt'] ?? ''),
            'pTk_thue'     => (string) ($row['tk_thue'] ?? ''),
            'pTk_dt'       => (string) ($row['tk_dt'] ?? ''),
            'pTk_gv'       => (string) ($row['tk_gv'] ?? ''),
            'pTk_vt'       => (string) ($row['tk_vt'] ?? ''),
            'pTk_ck'       => (string) ($row['tk_ck'] ?? ''),
            'pGia_nt'      => (float) ($row['gia_nt'] ?? 0),
            'pGia'         => (float) ($row['gia'] ?? 0),
            'pTien_nt'     => (float) ($row['tien_nt'] ?? 0),
            'pTien'        => (float) ($row['tien'] ?? 0),
            'pKhuyen_mai'  => (bool) ($row['khuyen_mai'] ?? false),
            'pTra_ck'      => (bool) ($row['tra_ck'] ?? false),
            'pSl_xuat'     => (float) ($row['sl_xuat'] ?? 0),
            'pSl_xuat_qd'  => (float) ($row['sl_xuat_qd'] ?? 0),
            'pMa_kho'      => (string) ($row['ma_kho'] ?? ''),
            'pMa_vitri'    => (string) ($row['ma_vitri'] ?? ''),
            'pMa_lo'       => (string) ($row['ma_lo'] ?? ''),
            'pMa_bp'       => (string) ($row['ma_bp'] ?? ''),
            'pMa_hd'       => (string) ($row['ma_hd'] ?? ''),
            'pMa_phi'      => (string) ($row['ma_phi'] ?? ''),
            'pMa_spct'     => (string) ($row['ma_spct'] ?? ''),
            'pMa_nvkd'     => (string) ($row['ma_nvkd'] ?? ''),
        ];
    }

    protected function recalculateAll(): void
    {
        foreach (array_keys($this->pChiTiet) as $index) {
            $this->calculateChiTietRow((int) $index);
        }

        $this->recalculateTotals();
    }

    protected function recalculateCkDs(): void
    {
        foreach ($this->pChiTiet as $index => $row) {
            $this->pChiTiet[$index]['ck_ds_nt'] = round((float) $row['tien_nt2'] * ($this->pTl_ck_ds / 100), 2);
            $this->pChiTiet[$index]['ck_ds']    = round((float) $row['tien2'] * ($this->pTl_ck_ds / 100), 2);
        }
    }

    protected function recalculateTotals(): void
    {
        $this->pT_tien_nt2 = $this->sumDetail('tien_nt2');
        $this->pT_tien2    = $this->sumDetail('tien2');
        $this->pT_ck_nt    = $this->sumDetail('tien_ck_nt');
        $this->pT_ck       = $this->sumDetail('tien_ck');
        $this->pT_ck_ds_nt = $this->sumDetail('ck_ds_nt');
        $this->pT_ck_ds    = $this->sumDetail('ck_ds');
        $this->pT_thue_nt  = $this->sumDetail('thue_gtgt_nt');
        $this->pT_thue     = $this->sumDetail('thue_gtgt');
        $this->pT_so_luong = $this->sumDetail('so_luong');
        $this->pT_tt_nt    = round($this->pT_tien_nt2 - $this->pT_ck_nt - $this->pT_ck_ds_nt + $this->pT_thue_nt, 2);
        $this->pT_tt       = round($this->pT_tien2 - $this->pT_ck - $this->pT_ck_ds + $this->pT_thue, 2);
    }

    protected function sumDetail(string $key): float
    {
        return round(array_sum(array_column($this->pChiTiet, $key)), 2);
    }

    protected function fillTaiKhoanFromHttt(string $maHttt): void
    {
        $rows = AsGetDMHTTT::call([
            'pMa_cty'   => SModel::CTY,
            'pMa_httt'  => $maHttt,
            'pModuleid' => 'SO',
            'pStruct'   => '0',
        ]);

        $httt = $rows->first();
        if (null === $httt) {
            $this->resetTaiKhoanFromHttt();

            return;
        }

        $this->pHttt = (string) ($httt->ten_httt ?? '');
        $this->pTk_pt = (string) ($httt->tk ?? '');
        $this->pTk_thue = (string) ($httt->tk_thue_gtgt_ban ?? '');
        $this->pTk_ck_ds = (string) ($httt->tk_ck ?? '');
    }

    protected function resetTaiKhoanFromHttt(): void
    {
        $this->pHttt = '';
        $this->pTk_pt = '';
        $this->pTk_thue = '';
        $this->pTk_ck_ds = '';
    }

    protected function resetCustomerInfo(): void
    {
        $this->pTen_kh = '';
        $this->pTen_kh_vat = '';
        $this->pDia_chi_vat = '';
        $this->pMa_so_thue = '';
        $this->pNguoi_gd = '';
        $this->pGh_no = 0;
        $this->pDu13 = 0;
        $this->pMa_httt = null;
        $this->resetTaiKhoanFromHttt();
    }

    protected function loadCustomerBalance(): void
    {
        if ('' === $this->pMa_kh) {
            $this->pDu13 = 0;

            return;
        }

        $rows = AsGetArkhInfo_SO::call([
            'pMa_cty' => SModel::CTY,
            'pMa_kh'  => $this->pMa_kh,
            'pThang'  => (int) now()->month,
            'pNam'    => (int) now()->year,
        ]);

        $duht = $rows->first(static fn (mixed $row): bool => 'DUHT' === (string) ($row->ma ?? ($row['ma'] ?? '')));
        $this->pDu13 = (float) ($duht->tien ?? 0);
    }

    protected function ensureSttRecBeforeSave(): void
    {
        if (!empty($this->pStt_rec)) {
            return;
        }

        $result = AsGetSttRec::call([
            'pMa_cty' => SModel::CTY,
            'pMa_ct'  => self::MA_CT,
        ]);
        $this->pStt_rec = $result->first()->pStt_rec ?? null;

        if (empty($this->pStt_rec)) {
            throw new \RuntimeException('Không thể sinh stt_rec cho hóa đơn bán hàng SO3. Vui lòng kiểm tra AsGetSttRec.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function emptyDetailRow(): array
    {
        return [
            'stt_rec0' => '', 'stt_rec_dh' => '', 'stt_rec0_dh' => '', 'stt_rec_px' => '', 'stt_rec0_px' => '',
            'so_dh' => '', 'so_px' => '', 'ma_vt' => '', 'ten_vt' => '', 'dvt' => '', 'ton_kho' => 0,
            'ma_kho' => '', 'ten_kho' => '', 'so_luong' => 0, 'so_luong_qd' => 0, 'gia_nt2' => 0,
            'gia2' => 0, 'tien_nt2' => 0, 'tien2' => 0, 'tl_ck' => 0, 'tien_ck_nt' => 0, 'tien_ck' => 0,
            'ck_ds_nt' => 0, 'ck_ds' => 0, 'ma_thue' => '', 'ts_gtgt' => 0, 'thue_gtgt_nt' => 0,
            'thue_gtgt' => 0, 'tt_nt' => 0, 'tt' => 0, 'tk_pt' => '', 'tk_thue' => '', 'tk_dt' => '',
            'tk_gv' => '', 'tk_vt' => '', 'tk_ck' => '', 'tk_tl' => '', 'tk_km' => '', 'gia_nt' => 0,
            'gia' => 0, 'tien_nt' => 0, 'tien' => 0, 'khuyen_mai' => false, 'tra_ck' => false,
            'sl_xuat' => 0, 'sl_xuat_qd' => 0, 'ma_nvkd' => '', 'ma_vitri' => '', 'ma_lo' => '',
            'ma_bp' => '', 'ma_hd' => '', 'ma_phi' => '', 'ma_spct' => '',
        ];
    }

    protected function dateValue(mixed $value): ?string
    {
        if (null === $value || '' === $value) {
            return null;
        }

        return $this->normalizeOptionalDate($value);
    }

    protected function normalizeOptionalDate(mixed $value): ?string
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

    protected function stringValue(?string $value): string
    {
        return (string) ($value ?? '');
    }

    protected function assertProcedureSuccess(Collection $result): void
    {
        $row = $result->first();
        $pRet = \is_array($row) ? ($row['pRet'] ?? null) : ($row->pRet ?? null);

        if (null !== $pRet && 0 !== (int) $pRet) {
            throw new \RuntimeException('Stored procedure trả về mã lỗi ' . (int) $pRet . '.');
        }
    }
}
