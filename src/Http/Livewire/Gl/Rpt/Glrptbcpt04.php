<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCPT04;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

/**
 * Kết quả hoạt động kinh doanh theo kỳ (BCPT04).
 *
 * Port từ GLRptBCPT04.dll (menu 02.60.11). SP `asGLRptBCPT04` (7 tham số).
 * Đối chiếu form gốc `frmGLRptBCPT04.LoadData()`:
 *   pMa_Cty, pNam (FinancialYear), pThang1, pThang2,
 *   pQuyThang (cboQuy_Thang.SelectedIndex + 1), pMau (ReportInfo.ma_mau), pMa_bp
 */
class Glrptbcpt04 extends AbstractReport
{
    public ?int $pNam = null;

    public int $pThang1 = 1;

    public int $pThang2 = 12;

    /** 1 = Theo quý, 2 = Theo tháng (cboQuy_Thang.SelectedIndex + 1). */
    public string $pQuyThang = '1';

    public string $pMau = '';

    public ?string $pMa_bp = '';

    /** Nhãn combo Quý/Tháng — đúng thứ tự form gốc (index + 1). */
    public const QUY_THANG_OPTIONS = [
        '1' => 'Theo quý',
        '2' => 'Theo tháng',
    ];

    protected function spClass(): string
    {
        return AsGLRptBCPT04::class;
    }

    protected function initialiseFilters(): void
    {
        $this->pNam ??= (int) now()->year;
    }

    protected function validateReport(): void
    {
        $this->validate([
            'pNam' => ['required', 'integer', 'min:1990', 'max:2100'],
            'pThang1' => ['required', 'integer', 'min:1', 'max:12'],
            'pThang2' => ['required', 'integer', 'min:1', 'max:12', 'gte:pThang1'],
            'pQuyThang' => ['required', Rule::in(array_keys(self::QUY_THANG_OPTIONS))],
            'pMa_bp' => ['nullable', 'string', 'max:20'],
        ], [
            'pNam.required' => 'Phải nhập năm.',
            'pThang1.required' => 'Phải chọn từ tháng.',
            'pThang2.gte' => 'Đến tháng phải lớn hơn hoặc bằng từ tháng.',
        ]);
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNam' => $this->pNam,
            'pThang1' => $this->pThang1,
            'pThang2' => $this->pThang2,
            'pQuyThang' => $this->pQuyThang,
            'pMau' => $this->pMau,
            'pMa_bp' => trim((string) $this->pMa_bp),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbcpt04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
            'quyThangOptions' => self::QUY_THANG_OPTIONS,
        ]);
    }
}
