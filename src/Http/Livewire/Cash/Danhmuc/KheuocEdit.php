<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:07
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsCAGetDMKU;
use Diepxuan\Simba\StoredProcedures\AsCAInsDMKU;
use Diepxuan\Simba\StoredProcedures\AsCAUpdDMKU;
use Illuminate\View\View;
use Livewire\Component;

class KheuocEdit extends Component
{
    public ?string $pMa_ku      = null;
    public string $pTen_ku      = '';
    public string $pSo_ku       = '';
    public ?string $pNgay_vay   = null;
    public ?string $pNgay_ky    = null;
    public int $pThoi_han       = 0;
    public string $pTinh_theo   = '1';
    public string $pPhuong_phap = '1';
    public string $pKy_tt_goc   = '1';
    public string $pKy_tt_lai   = '1';
    public ?string $pNgay_dh    = null;
    public string $pMa_hd       = '';
    public string $pMa_nt       = 'VND';
    public float $pTien_nt      = 0;
    public float $pTien         = 0;
    public float $pLai_suat     = 0;
    public float $pLs_qh        = 0;
    public string $pTk_vay      = '';
    public string $pTk_cp       = '';
    public string $pTk_cp_pt    = '';
    public string $pGhi_chu     = '';
    public bool $pKsd           = true;
    public string $pMode        = 'create';

    public function mount(?string $maKu = null): void
    {
        $this->pNgay_vay = now()->format('Y-m-d');
        $this->pNgay_ky  = now()->format('Y-m-d');

        if ($maKu) {
            $this->pMode  = 'edit';
            $this->pMa_ku = $maKu;
            $this->loadItem();
        }
    }

    public function loadItem(): void
    {
        $row = AsCAGetDMKU::call([])->first(fn ($item) => ($item->ma_ku ?? $item->MA_KU ?? null) === $this->pMa_ku);

        if (!$row) {
            session()->flash('error', 'Không tìm thấy khế ước.');
            $this->redirectRoute('ca.kheuoc');

            return;
        }

        foreach ([
            'Ma_ku'     => 'ma_ku', 'Ten_ku' => 'ten_ku', 'So_ku' => 'so_ku', 'Ngay_vay' => 'ngay_vay', 'Ngay_ky' => 'ngay_ky',
            'Thoi_han'  => 'thoi_han', 'Tinh_theo' => 'tinh_theo', 'Phuong_phap' => 'phuong_phap', 'Ky_tt_goc' => 'ky_tt_goc',
            'Ky_tt_lai' => 'ky_tt_lai', 'Ngay_dh' => 'ngay_dh', 'Ma_hd' => 'ma_hd', 'Ma_nt' => 'ma_nt', 'Tien_nt' => 'tien_nt',
            'Tien'      => 'tien', 'Lai_suat' => 'lai_suat', 'Ls_qh' => 'ls_qh', 'Tk_vay' => 'tk_vay', 'Tk_cp' => 'tk_cp',
            'Tk_cp_pt'  => 'tk_cp_pt', 'Ghi_chu' => 'ghi_chu', 'Ksd' => 'ksd',
        ] as $studly => $field) {
            $prop          = 'p' . $studly;
            $this->{$prop} = $row->{$field} ?? $row->{strtoupper($field)} ?? $this->{$prop};
        }
    }

    public function submit(): void
    {
        $this->validate();
        $params = [
            'pMa_cty'    => SModel::CTY, 'pMa_ku' => $this->pMa_ku, 'pTen_ku' => $this->pTen_ku, 'pSo_ku' => $this->pSo_ku,
            'pNgay_vay'  => $this->pNgay_vay, 'pNgay_ky' => $this->pNgay_ky, 'pThoi_han' => $this->pThoi_han,
            'pTinh_theo' => $this->pTinh_theo, 'pPhuong_phap' => $this->pPhuong_phap, 'pKy_tt_goc' => $this->pKy_tt_goc,
            'pKy_tt_lai' => $this->pKy_tt_lai, 'pNgay_dh' => $this->pNgay_dh, 'pMa_hd' => $this->pMa_hd, 'pMa_nt' => $this->pMa_nt,
            'pTien_nt'   => $this->pTien_nt, 'pTien' => $this->pTien, 'pLai_suat' => $this->pLai_suat, 'pLs_qh' => $this->pLs_qh,
            'pTk_vay'    => $this->pTk_vay, 'pTk_cp' => $this->pTk_cp, 'pTk_cp_pt' => $this->pTk_cp_pt,
            'pGhi_chu'   => $this->pGhi_chu, 'pKsd' => $this->pKsd ? 1 : 0, 'pLUser' => auth()->id() ?? 'SYSTEM',
        ];

        try {
            'create' === $this->pMode ? AsCAInsDMKU::call($params) : AsCAUpdDMKU::call($params);
            session()->flash('success', 'Đã lưu khế ước ' . $this->pMa_ku);
            $this->redirectRoute('ca.kheuoc');
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi lưu khế ước: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::cash.danhmuc.kheuoc-edit', ['mode' => $this->pMode])->layout('catalog::layouts.app');
    }

    protected function rules(): array
    {
        return [
            'pMa_ku'    => 'required|string|max:20',
            'pTen_ku'   => 'required|string|max:50',
            'pSo_ku'    => 'required|string|max:20',
            'pNgay_vay' => 'required|date',
            'pNgay_ky'  => 'nullable|date',
            'pNgay_dh'  => 'nullable|date',
        ];
    }
}
