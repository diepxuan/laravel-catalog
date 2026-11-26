<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptBK01;
use Illuminate\Contracts\View\View;

/**
 * Thông tin tính giá NTXT — bảng kê chứng từ tồn kho (INRptBK01).
 *
 * Port từ INRptBK01.dll (menu 14.20.26). SP `asINRptBK01` (5 tham số):
 *   pma_cty, pNam, pThang, pMa_vt, pMa_kho
 *
 * Menu này có 1 menu duy nhất trong sysMenu → route KHÔNG có suffix menuid.
 */
class Inrptbk01 extends AbstractReport
{
    public ?int $pNam = null;

    public int $pThang = 1;

    public string $pMa_vt = '';

    public string $pMa_kho = '';

    protected function initialiseFilters(): void
    {
        $this->pNam ??= (int) now()->year;
        $this->pThang = (int) now()->month;
    }

    protected function validateReport(): void
    {
        $this->validate([
            'pNam' => ['required', 'integer', 'min:1990', 'max:2100'],
            'pThang' => ['required', 'integer', 'min:1', 'max:12'],
        ], [
            'pNam.required' => 'Phải nhập năm.',
            'pThang.required' => 'Phải chọn tháng.',
        ]);
    }

    protected function spClass(): string
    {
        return AsINRptBK01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pma_cty' => (string) \CatalogService::company()->id,
            'pNam' => $this->pNam,
            'pThang' => $this->pThang,
            'pMa_vt' => trim($this->pMa_vt),
            'pMa_kho' => trim($this->pMa_kho),
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptbk01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
