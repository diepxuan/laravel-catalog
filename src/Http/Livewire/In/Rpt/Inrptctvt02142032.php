<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptCTVT02;
use Illuminate\Contracts\View\View;

/**
 * Thẻ kho / Sổ chi tiết vật tư — in tất cả (INRptCTVT02, menu 14.20.32).
 *
 * Port từ INRptCTVT02.dll. SP `asINRptCTVT02` (13 tham số).
 *
 * Route có suffix `142032` vì sysMenu có 2 menu active cùng DLL
 * (14.20.05 và 14.20.32) → SimbaMenuRouteMetadata::menuIdSuffix() nối menuid.
 */
class Inrptctvt02142032 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pMa_vt = '';

    public string $pTk_vt = '1561';

    public string $pMa_nhvt = '';

    public string $pMa_kho = '';

    public string $pMa_vitri = '';

    /** Đơn vị tính: 0 = gốc, 1 = nhỏ nhất, 2 = lớn nhất. */
    public string $pDVT = '0';

    public string $pPSDC = '';

    /** Ngoại tệ: 0 = VND, 1 = ngoại tệ. */
    public string $pNgoai_te = '0';

    public int $pMau_bc = 0;

    public string $pMa_nt = 'VND';

    protected function spClass(): string
    {
        return AsINRptCTVT02::class;
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
            'pMa_vt' => trim($this->pMa_vt),
            'pTk_vt' => trim($this->pTk_vt),
            'pMa_nhvt' => trim($this->pMa_nhvt),
            'pMa_kho' => trim($this->pMa_kho),
            'pMa_vitri' => trim($this->pMa_vitri),
            'pDVT' => $this->pDVT,
            'pPSDC' => trim($this->pPSDC),
            'pNgoai_te' => $this->pNgoai_te,
            'pMau_bc' => $this->pMau_bc,
            'pMa_nt' => trim($this->pMa_nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptctvt02142032', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
