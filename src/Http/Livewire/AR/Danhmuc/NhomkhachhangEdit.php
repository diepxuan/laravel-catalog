<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:53
 */

namespace Diepxuan\Catalog\Http\Livewire\AR\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMNHKH;
use Diepxuan\Simba\StoredProcedures\AsARInsDMNHKH;
use Diepxuan\Simba\StoredProcedures\AsARUpdDMNHKH;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Component NhomkhachhangEdit - Thêm/Sửa nhóm khách hàng.
 */
class NhomkhachhangEdit extends Component
{
    public ?string $pMa_Nhkh = null;
    public string $pTen_Nhkh = '';
    public string $pGhi_Chu  = '';
    public bool $pKsd        = true;
    public string $pMode     = 'create';

    public function mount(?string $maNhkh = null): void
    {
        if ($maNhkh) {
            $this->pMode    = 'edit';
            $this->pMa_Nhkh = $maNhkh;
            $this->loadNhomKH();
        }
    }

    public function loadNhomKH(): void
    {
        $result = AsARGetDMNHKH::call([
            'pMa_cty'  => SModel::CTY,
            'pMa_nhkh' => $this->pMa_Nhkh,
        ]);

        if ($result->isEmpty()) {
            session()->flash('error', 'Không tìm thấy nhóm khách hàng.');
            $this->redirectRoute('ar.nhomkhachhang');

            return;
        }

        $row             = $result->first();
        $this->pMa_Nhkh  = $row->ma_nhkh ?? $row->MA_NHKH ?? $this->pMa_Nhkh;
        $this->pTen_Nhkh = $row->ten_nhkh ?? $row->TEN_NHKH ?? '';
        $this->pGhi_Chu  = $row->ghi_chu ?? $row->GHI_CHU ?? '';
        $this->pKsd      = (bool) ($row->ksd ?? $row->KSD ?? true);
    }

    public function updatedPMaNhkh($value): void
    {
        if ('create' === $this->pMode && !empty($value)) {
            $this->pMa_Nhkh = strtoupper($value);
        }
    }

    public function submit(): void
    {
        $this->validate();

        $maCty = SModel::CTY;

        try {
            if ('create' === $this->pMode) {
                AsARInsDMNHKH::call([
                    'pMa_cty'   => $maCty,
                    'pMa_nhkh'  => $this->pMa_Nhkh,
                    'pTen_nhkh' => $this->pTen_Nhkh,
                    'pGhi_chu'  => '' !== $this->pGhi_Chu ? $this->pGhi_Chu : null,
                    'pKsd'      => $this->pKsd ? 1 : 0,
                    'pLUser'    => auth()->id() ?? 'SYSTEM',
                ]);

                session()->flash('success', 'Đã thêm nhóm khách hàng ' . $this->pMa_Nhkh);
            } else {
                AsARUpdDMNHKH::call([
                    'pMa_cty'   => $maCty,
                    'pMa_nhkh'  => $this->pMa_Nhkh,
                    'pTen_nhkh' => $this->pTen_Nhkh,
                    'pGhi_chu'  => '' !== $this->pGhi_Chu ? $this->pGhi_Chu : null,
                    'pKsd'      => $this->pKsd ? 1 : 0,
                    'pLUser'    => auth()->id() ?? 'SYSTEM',
                ]);

                session()->flash('success', 'Đã cập nhật nhóm khách hàng ' . $this->pMa_Nhkh);
            }

            $this->redirectRoute('ar.nhomkhachhang');
        } catch (\Exception $e) {
            $msg = 'create' === $this->pMode ? 'thêm' : 'cập nhật';
            session()->flash('error', 'Lỗi khi ' . $msg . ' nhóm khách hàng: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::ar.danhmuc.nhomkhachhang-edit', [
            'mode' => $this->pMode,
        ])->layout('catalog::layouts.app');
    }

    protected function rules(): array
    {
        return [
            'pMa_Nhkh'  => 'required|string|max:50',
            'pTen_Nhkh' => 'required|string|max:200',
            'pGhi_Chu'  => 'nullable|string|max:500',
        ];
    }

    protected function messages(): array
    {
        return [
            'pMa_Nhkh.required'  => 'Mã nhóm khách hàng không được trống',
            'pMa_Nhkh.max'       => 'Mã nhóm khách hàng tối đa 50 ký tự',
            'pTen_Nhkh.required' => 'Tên nhóm khách hàng không được trống',
            'pTen_Nhkh.max'      => 'Tên nhóm khách hàng tối đa 200 ký tự',
            'pGhi_Chu.max'       => 'Ghi chú tối đa 500 ký tự',
        ];
    }
}
