<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:06
 */

namespace Diepxuan\Catalog\Http\Livewire\AR\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMPLKH;
use Diepxuan\Simba\StoredProcedures\AsARInsDMPLKH;
use Diepxuan\Simba\StoredProcedures\AsARUpdDMPLKH;
use Illuminate\View\View;
use Livewire\Component;

class PhanloaikhachhangEdit extends Component
{
    public ?string $pMa_Plkh = null;
    public string $pTen_Plkh = '';
    public int $pLoai        = 1;
    public string $pGhi_Chu  = '';
    public bool $pKsd        = true;
    public string $pMode     = 'create';

    public function mount(?string $maPlkh = null): void
    {
        if ($maPlkh) {
            $this->pMode    = 'edit';
            $this->pMa_Plkh = $maPlkh;
            $this->loadItem();
        }
    }

    public function loadItem(): void
    {
        $result = AsARGetDMPLKH::call([
            'pMa_cty'  => SModel::CTY,
            'pMa_plkh' => $this->pMa_Plkh,
        ]);
        if ($result->isEmpty()) {
            session()->flash('error', 'Không tìm thấy.');
            $this->redirectRoute('ar.phanloaikhachhang');

            return;
        }
        $row             = $result->first();
        $this->pMa_Plkh  = $row->ma_plkh ?? $row->MA_PLKH ?? $this->pMa_Plkh;
        $this->pTen_Plkh = $row->ten_plkh ?? $row->TEN_PLKH ?? '';
        $this->pLoai     = (int) ($row->loai ?? $row->LOAI ?? 1);
        $this->pGhi_Chu  = $row->ghi_chu ?? $row->GHI_CHU ?? '';
        $this->pKsd      = (bool) ($row->ksd ?? $row->KSD ?? true);
    }

    public function updatedPMaPlkh($value): void
    {
        if ('create' === $this->pMode && !empty($value)) {
            $this->pMa_Plkh = strtoupper($value);
        }
    }

    public function submit(): void
    {
        $this->validate();

        try {
            $params = [
                'pMa_cty'   => SModel::CTY,
                'pMa_plkh'  => $this->pMa_Plkh,
                'pTen_plkh' => $this->pTen_Plkh,
                'pLoai'     => $this->pLoai,
                'pGhi_chu'  => '' !== $this->pGhi_Chu ? $this->pGhi_Chu : null,
                'pKsd'      => $this->pKsd ? 1 : 0,
                'pLUser'    => auth()->id() ?? 'SYSTEM',
            ];
            if ('create' === $this->pMode) {
                AsARInsDMPLKH::call($params);
                session()->flash('success', 'Đã thêm ' . $this->pMa_Plkh);
            } else {
                AsARUpdDMPLKH::call($params);
                session()->flash('success', 'Đã cập nhật ' . $this->pMa_Plkh);
            }
            $this->redirectRoute('ar.phanloaikhachhang');
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::ar.danhmuc.phanloaikhachhang-edit', ['mode' => $this->pMode])
            ->layout('catalog::layouts.app')
        ;
    }

    protected function rules(): array
    {
        return [
            'pMa_Plkh'  => 'required|string|max:50',
            'pTen_Plkh' => 'required|string|max:200',
            'pLoai'     => 'required|integer|in:1,2,3',
            'pGhi_Chu'  => 'nullable|string|max:500',
        ];
    }

    protected function messages(): array
    {
        return [
            'pMa_Plkh.required'  => 'Mã phân loại không được trống',
            'pTen_Plkh.required' => 'Tên phân loại không được trống',
        ];
    }
}
