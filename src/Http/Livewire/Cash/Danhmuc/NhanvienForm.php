<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-05 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc;

use Diepxuan\Catalog\Http\Livewire\Muahang\CungcapForm;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Diepxuan\Simba\StoredProcedures\AsARInsDMKH;
use Diepxuan\Simba\StoredProcedures\AsARUpdDMKH;
use Illuminate\View\View;

class NhanvienForm extends CungcapForm
{
    public ?string $home_page = null;
    public ?string $ma_httt = null;
    public ?string $ma_httt_po = null;
    public ?string $ma_ngh = null;
    public ?string $ten_nh = null;
    public ?string $cn_nh = null;
    public ?string $so_tk_nh = null;
    public ?string $tinh_tp_nh = null;
    public ?string $ma_plkh1 = null;
    public ?string $ma_plkh2 = null;
    public ?string $ma_plkh3 = null;
    public ?string $ma_nhkh = null;
    public ?string $ma_tt = null;
    public ?float $gh_no = null;
    public ?float $han_ck = null;
    public ?float $tl_ck = null;
    public ?float $han_tt = null;
    public ?float $ls_qh = null;

    protected $messages = [
        'ma_kh.required' => 'Mã nhân viên không được để trống.',
        'ten_kh.required' => 'Tên nhân viên không được để trống.',
        'email.email' => 'Email không đúng định dạng.',
    ];

    public bool $isKh = false;
    public bool $isNcc = false;
    public bool $isNv = true;
    public bool $tinh_dt_nb = false;
    public bool $ksd = false;

    public function loadDoiTuong(string $maKh): void
    {
        try {
            $result = AsARGetDMKH::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh' => $maKh,
                'pModuleId' => 'CA',
            ]);

            if ($result->isEmpty()) {
                $this->dispatch('error', message: 'Không tìm thấy nhân viên.');
                return;
            }

            $row = $result->first();
            $this->ma_kh = $row->MA_KH ?? $maKh;
            $this->ten_kh = $row->TEN_KH ?? '';
            $this->dia_chi = $row->DIA_CHI ?? '';
            $this->ma_so_thue = $row->MA_SO_THUE ?? '';
            $this->dien_thoai = $row->TEL ?? '';
            $this->fax = $row->FAX ?? '';
            $this->email = $row->EMAIL ?? '';
            $this->home_page = $row->HOME_PAGE ?? null;
            $this->nguoi_gd = $row->NGUOI_GD ?? null;
            $this->ma_httt = $row->MA_HTTT ?? null;
            $this->ma_httt_po = $row->MA_HTTT_PO ?? null;
            $this->gh_no = $this->toNullableFloat($row->GH_NO ?? null);
            $this->han_tt = $this->toNullableFloat($row->HAN_TT ?? null);
            $this->ma_ngh = $row->MA_NGH ?? null;
            $this->ten_nh = $row->TEN_NH ?? null;
            $this->cn_nh = $row->CN_NH ?? null;
            $this->so_tk_nh = $row->SO_TK_NH ?? null;
            $this->tinh_tp_nh = $row->TINH_TP_NH ?? null;
            $this->tk_cn = $row->TK ?? null;
            $this->ma_plkh1 = $row->MA_PLKH1 ?? null;
            $this->ma_plkh2 = $row->MA_PLKH2 ?? null;
            $this->ma_plkh3 = $row->MA_PLKH3 ?? null;
            $this->ma_nhkh = $row->MA_NHKH ?? null;
            $this->ma_tt = $row->MA_TT ?? null;
            $this->han_ck = $this->toNullableFloat($row->HAN_CK ?? null);
            $this->tl_ck = $this->toNullableFloat($row->TL_CK ?? null);
            $this->ls_qh = $this->toNullableFloat($row->LS_QH ?? null);
            $this->ghi_chu = $row->GHI_CHU ?? null;
            $this->isKh = (bool) ($row->ISKH ?? false);
            $this->isNcc = (bool) ($row->ISNCC ?? false);
            $this->isNv = (bool) ($row->ISNV ?? true);
            $this->tinh_dt_nb = (bool) ($row->TINH_DT_NB ?? false);
            $this->ksd = (bool) ($row->KSD ?? false);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể tải nhân viên: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::cash.danhmuc.nhanvien-form')->layout('catalog::layouts.app');
    }

    public function save(): void
    {
        if (!$this->isKh && !$this->isNcc && !$this->isNv) {
            $this->addError('isNv', 'Phải chọn ít nhất một loại đối tượng: khách hàng, nhà cung cấp hoặc nhân viên.');

            return;
        }

        parent::save();
    }

    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'home_page' => 'nullable|string|max:100',
            'ma_httt' => 'nullable|string|max:20',
            'ma_httt_po' => 'nullable|string|max:20',
            'gh_no' => 'nullable|numeric',
            'han_tt' => 'nullable|numeric',
            'ma_ngh' => 'nullable|string|max:20',
            'ten_nh' => 'nullable|string|max:100',
            'cn_nh' => 'nullable|string|max:100',
            'so_tk_nh' => 'nullable|string|max:50',
            'tinh_tp_nh' => 'nullable|string|max:100',
            'ma_plkh1' => 'nullable|string|max:8',
            'ma_plkh2' => 'nullable|string|max:8',
            'ma_plkh3' => 'nullable|string|max:8',
            'ma_nhkh' => 'nullable|string|max:8',
            'ma_tt' => 'nullable|string|max:8',
            'han_ck' => 'nullable|numeric',
            'tl_ck' => 'nullable|numeric',
            'ls_qh' => 'nullable|numeric',
            'isKh' => 'boolean',
            'isNcc' => 'boolean',
            'isNv' => 'boolean',
            'tinh_dt_nb' => 'boolean',
            'ksd' => 'boolean',
        ]);
    }

    protected function persist(string $procedureClass): void
    {
        $maKh = strtoupper(trim((string) $this->ma_kh));
        $user = auth()->user()->name ?? 'system';

        try {
            $procedureClass::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh' => $maKh,
                'pLoai' => 1,
                'pTen_kh' => $this->ten_kh,
                'pMa_so_thue' => $this->ma_so_thue,
                'pDia_chi' => $this->dia_chi,
                'pTel' => $this->dien_thoai,
                'pFax' => $this->fax,
                'pEmail' => $this->email,
                'pHome_page' => $this->home_page,
                'pNguoi_gd' => $this->nguoi_gd,
                'pMa_httt' => $this->ma_httt,
                'pMa_httt_po' => $this->ma_httt_po,
                'pGh_no' => $this->gh_no,
                'pHan_tt' => $this->han_tt,
                'pMa_ngh' => $this->ma_ngh,
                'pTen_nh' => $this->ten_nh,
                'pCn_nh' => $this->cn_nh,
                'pSo_tk_nh' => $this->so_tk_nh,
                'pTinh_tp_nh' => $this->tinh_tp_nh,
                'pTk' => $this->tk_cn,
                'pMa_plkh1' => $this->ma_plkh1,
                'pMa_plkh2' => $this->ma_plkh2,
                'pMa_plkh3' => $this->ma_plkh3,
                'pMa_nhkh' => $this->ma_nhkh,
                'pMa_tt' => $this->ma_tt,
                'pHan_ck' => $this->han_ck,
                'pTl_ck' => $this->tl_ck,
                'pLs_qh' => $this->ls_qh,
                'pGhi_chu' => $this->ghi_chu,
                'pTinh_dt_nb' => $this->tinh_dt_nb ? 1 : 0,
                'pIskh' => $this->isKh ? 1 : 0,
                'pIsncc' => $this->isNcc ? 1 : 0,
                'pIsnv' => $this->isNv ? 1 : 0,
                'pKsd' => $this->ksd ? 1 : 0,
                'pLUser' => $user,
            ]);

            $this->dispatch('success', message: 'Đã lưu nhân viên ' . $maKh);
            $this->redirect(route('ca.nhanvien'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể lưu nhân viên: ' . $e->getMessage());
        }
    }

    private function toNullableFloat(mixed $value): ?float
    {
        if (null === $value || '' === $value) {
            return null;
        }

        return (float) $value;
    }
}
