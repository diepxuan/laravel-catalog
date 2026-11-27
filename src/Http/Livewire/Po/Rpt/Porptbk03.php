<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Po\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsPORptBK03;
use Illuminate\Contracts\View\View;

/**
 * PORptBK03 — 27 tham so (da bo system params).
 */
class Porptbk03 extends AbstractReport
{

    /*
     * LUU Y QUAN TRONG ve gia tri mac dinh:
     *
     * SP asPORptBK01/02/03 dung SQL dong voi pattern:
     *   case @pX when '' then '' else ' AND col LIKE @pX + percent' end
     *
     * Trong SQL Server: NULL = '' -> UNKNOWN (khong phai TRUE) -> roi vao
     * nhanh else -> sinh dieu kien LIKE NULL -> loc sach 0 dong.
     *
     * Vi vay cac tham so sau BAT BUOC mac dinh '' (khong duoc null):
     *   pTrang_thai, pMa_nt, pMa_kho, pMa_lo, pMa_httt, pMa_bp,
     *   pMa_spct, pMa_Phi, pGroupType
     *
     * Cac tham so con lai (pMa_kh, pMa_vt, pMa_nhvt, pMa_hd, pMa_plkh1..3,
     * pMa_nhhd) an toan voi ca null va '' (SP dung LIKE tren cot khac).
     */
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nhkh = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_nhvt = null;

    public ?string $pMa_kho = '';

    public ?string $pMa_bp = '';


    /**
     * Ma ngoai te mac dinh = VND.
     *
     * Bang chung tu Framework.dll (base class frmReport):
     *   optVND.Checked = true;   // dong 59828
     *   optVND.Text = "VND";
     *   optNt.Text = "Ngoai te";
     * Va sysReportInfo.hasNT='1' cho ca 3 menu -> nhom ngoai te phai hien.
     *
     * SP loc: AND p.ma_nt LIKE @pMa_nt + '%'
     *   '' -> tat ca ngoai te | 'VND' -> chi VND (dung mac dinh SimbaERP).
     */
    public ?string $pMa_nt = 'VND';

    public ?string $pMa_hd = null;

    public ?string $pSoct1 = null;

    public ?string $pSoct2 = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    public ?string $pGroupType = '';

    public ?string $pMa_nhhd = null;

    public ?string $pMa_plvt1 = null;

    public ?string $pMa_plvt2 = null;

    public ?string $pMa_plvt3 = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_lo = '';

    public ?string $pMa_httt = '';

    public ?string $pMa_TT = null;

    public ?string $pMa_spct = '';

    public ?string $pMa_Phi = '';

    protected function spClass(): string
    {
        return AsPORptBK03::class;
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
            'pMa_kh' => $this->pMa_kh,
            'pMa_nhkh' => $this->pMa_nhkh,
            'pMa_vt' => $this->pMa_vt,
            'pMa_nhvt' => $this->pMa_nhvt,
            'pMa_kho' => $this->pMa_kho,
            'pMa_bp' => $this->pMa_bp,
            'pMa_nt' => $this->pMa_nt,
            'pMa_hd' => $this->pMa_hd,
            'pSoct1' => $this->pSoct1,
            'pSoct2' => $this->pSoct2,
            'pMa_plkh1' => $this->pMa_plkh1,
            'pMa_plkh2' => $this->pMa_plkh2,
            'pMa_plkh3' => $this->pMa_plkh3,
            'pGroupType' => $this->pGroupType,
            'pMa_nhhd' => $this->pMa_nhhd,
            'pMa_plvt1' => $this->pMa_plvt1,
            'pMa_plvt2' => $this->pMa_plvt2,
            'pMa_plvt3' => $this->pMa_plvt3,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_lo' => $this->pMa_lo,
            'pMa_httt' => $this->pMa_httt,
            'pMa_TT' => $this->pMa_TT,
            'pMa_spct' => $this->pMa_spct,
            'pMa_Phi' => $this->pMa_Phi,
        ];
    }

    public function mount(?string $module = null, ?string $kind = null, ?string $slug = null): void
    {
        parent::mount($module, $kind, $slug);

        $this->syncTimer();
    }

    private function syncTimer(): void
    {
        $timer = \CatalogService::timer();

        $this->pNgay1 = (string) ($timer['from'] ?? now()->startOfMonth()->toDateString());
        $this->pNgay2 = (string) ($timer['to'] ?? now()->toDateString());
    }

    public function render(): View
    {
        return view('catalog::po.rpt.porptbk03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
