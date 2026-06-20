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

use Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Illuminate\View\View;

class Nhanvien extends Ardmkh
{
    public function deleteDoiTuong(string $maKh): void
    {
        $nhanVien = \Diepxuan\Catalog\Models\ArDmKh::withoutGlobalScopes()
            ->where('ma_kh', $maKh)
            ->first()
        ;

        if (!$nhanVien) {
            $this->dispatch('error', message: 'Không tìm thấy nhân viên.');

            return;
        }

        if ($nhanVien->hasTransactions()) {
            $this->dispatch('error', message: 'Không thể xóa nhân viên đã có giao dịch.');

            return;
        }

        try {
            \Diepxuan\Simba\StoredProcedures\AsARDelDMKH::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh'  => $maKh,
            ]);
            $this->dispatch('success', message: 'Đã xóa nhân viên ' . $maKh);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể xóa nhân viên: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::cash.danhmuc.nhanvien', [
            'arDmKhs' => $this->getEmployeesPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getEmployeesPaginated()
    {
        $results = AsARGetDMKH::call([
            'pMa_cty' => SModel::CTY,
            'pMa_kh' => '' !== $this->search ? $this->search : null,
            'pStruct' => '0',
            'pModuleId' => 'CA',
        ]);

        $results = $this->normalizeRows($results);

        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        return $this->paginateCollection($results);
    }
}
