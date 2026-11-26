<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Ca\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsCARptTMNH02;
use Illuminate\Contracts\View\View;

/**
 * Sổ tiền gửi ngân hàng (CARptTMNH02).
 *
 * 5 tham số theo wrapper: pMa_Cty, pTk, pNgay_Ct1, pNgay_ct2, pMa_Nt
 * (docs ghi pNgay1/pNgay2/pMaNt — wrapper là nguồn chính xác hơn).
 */
class Carpttmnh02 extends AbstractReport
{
    public string $pTk = '';

    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pMa_Nt = 'VND';

    protected function spClass(): string
    {
        return AsCARptTMNH02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pTk' => trim($this->pTk),
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pMaNt' => trim($this->pMa_Nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::ca.rpt.carpttmnh02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
