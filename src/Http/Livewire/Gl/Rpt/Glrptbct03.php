<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCT03;
use Illuminate\Contracts\View\View;

/**
 * Tờ khai giá trị gia tăng (GTGT) — mẫu BCT03.
 *
 * Port từ GLRptBCT03.dll. SP `asGLRptBCT03` (3 tham số: pMa_cty, pNgay1, pNgay2).
 * Form gốc frmGLRptBCT03.LoadData() truyền đúng CompanyID + txtNgay1 + txtNgay2.
 */
class Glrptbct03 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    protected function spClass(): string
    {
        return AsGLRptBCT03::class;
    }

    protected function validateReport(): void
    {
        $this->validate([
            'pNgay1' => ['required', 'date'],
            'pNgay2' => ['required', 'date', 'after_or_equal:pNgay1'],
        ], [
            'pNgay1.required' => 'Phải nhập từ ngày.',
            'pNgay2.required' => 'Phải nhập đến ngày.',
            'pNgay2.after_or_equal' => 'Đến ngày phải lớn hơn hoặc bằng từ ngày.',
        ]);
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbct03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
