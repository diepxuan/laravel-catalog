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
            $this->ma_kh = $row['MA_KH'] ?? $maKh;
            $this->ten_kh = $row['TEN_KH'] ?? '';
            $this->dia_chi = $row['DIA_CHI'] ?? '';
            $this->ma_so_thue = $row['MA_SO_THUE'] ?? '';
            $this->dien_thoai = $row['TEL'] ?? '';
            $this->fax = $row['FAX'] ?? '';
            $this->email = $row['EMAIL'] ?? '';
            $this->nguoi_gd = $row['NGUOI_GD'] ?? null;
            $this->tk_cn = $row['TK'] ?? null;
            $this->ghi_chu = $row['GHI_CHU'] ?? null;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể tải nhân viên: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::cash.danhmuc.nhanvien-form')->layout('catalog::layouts.app');
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
                'pNguoi_gd' => $this->nguoi_gd,
                'pTk' => $this->tk_cn,
                'pGhi_chu' => $this->ghi_chu,
                'pIskh' => 0,
                'pIsncc' => 0,
                'pIsnv' => 1,
                'pKsd' => 0,
                'pLUser' => $user,
            ]);

            $this->dispatch('success', message: 'Đã lưu nhân viên ' . $maKh);
            $this->redirect(route('ca.nhanvien'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể lưu nhân viên: ' . $e->getMessage());
        }
    }
}
