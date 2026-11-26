<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCPT05;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

/**
 * So sánh tình hình thực hiện ngân sách (BCPT05).
 *
 * Port từ GLRptBCPT05.dll (menu 02.60.14). SP `asGLRptBCPT05` (12 tham số).
 * Đối chiếu form gốc `frmGLRptBCPT05.LoadData()`:
 *   pMa_cty, pNam (Year(txtNgay1)), pTK, pMa_phi, pMa_BP, pMa_spct,
 *   pNgay1, pNgay2, pGroup1..pGroup4 (4 combo nhóm cấp).
 *
 * Combo nhóm cấp: key theo `DataSource4CBO` gốc —
 *   '' (Tất cả), 'TK', 'ma_bo', 'ma_phi', 'ma_spct'.
 */
class Glrptbcpt05 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pTk = '';

    public string $pMa_phi = '';

    public string $pMa_bp = '';

    public string $pMa_spct = '';

    public string $pGroup1 = '';

    public string $pGroup2 = '';

    public string $pGroup3 = '';

    public string $pGroup4 = '';

    /** Key combo nhóm cấp — đúng `DataSource4CBO` trong form gốc. */
    public const GROUP_OPTIONS = [
        '' => 'Tất cả',
        'TK' => 'Tài khoản',
        'ma_bo' => 'Mã bộ phận',
        'ma_phi' => 'Mã phí',
        'ma_spct' => 'Mã sản phẩm công trình',
    ];

    protected function spClass(): string
    {
        return AsGLRptBCPT05::class;
    }

    protected function validateReport(): void
    {
        $this->validate([
            'pNgay1' => ['required', 'date'],
            'pNgay2' => ['required', 'date', 'after_or_equal:pNgay1'],
            'pGroup1' => ['nullable', Rule::in(array_keys(self::GROUP_OPTIONS))],
            'pGroup2' => ['nullable', Rule::in(array_keys(self::GROUP_OPTIONS))],
            'pGroup3' => ['nullable', Rule::in(array_keys(self::GROUP_OPTIONS))],
            'pGroup4' => ['nullable', Rule::in(array_keys(self::GROUP_OPTIONS))],
        ], [
            'pNgay1.required' => 'Phải nhập từ ngày.',
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
            'pNam' => (int) date('Y', strtotime((string) $this->pNgay1)),
            'pTK' => trim($this->pTk),
            'pMa_phi' => trim($this->pMa_phi),
            'pMa_BP' => trim($this->pMa_bp),
            'pMa_spct' => trim($this->pMa_spct),
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pGroup1' => $this->pGroup1,
            'pGroup2' => $this->pGroup2,
            'pGroup3' => $this->pGroup3,
            'pGroup4' => $this->pGroup4,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbcpt05', [
            'rows' => $this->rows,
            'columns' => $this->columns,
            'groupOptions' => self::GROUP_OPTIONS,
        ]);
    }
}
