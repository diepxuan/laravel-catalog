<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Dict;

use Diepxuan\Catalog\Http\Livewire\Po\Dict\ArdmkhForm as BaseForm;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Illuminate\View\View;

/**
 * ARDMKH khách hàng (SO, module AR, pIskh=1).
 * Kế thừa canonical pattern từ PO Dict\ArdmkhForm.
 */
class ArdmkhForm extends BaseForm
{
    public ?string $ma_nt = 'VND';
    public ?string $home_page = null;

    public bool $isKh  = true;
    public bool $isNcc = false;
    public bool $isNv  = false;
    public bool $ksd   = false;

    protected $messages = [
        'ma_kh.required'  => 'Mã khách hàng không được để trống.',
        'ten_kh.required' => 'Tên khách hàng không được để trống.',
        'email.email'     => 'Email không đúng định dạng.',
    ];

    public function mount(?string $id = null): void
    {
        $this->loadDropdowns();
        if ($id) {
            $this->mode = 'edit';
            $this->loadDoiTuong($id);
        }
    }

    public function loadDoiTuong(string $maKh): void
    {
        try {
            $result = AsARGetDMKH::getCustomers(search: $maKh);
            // asARGetDMKH là prefix search -> phải lọc khớp chính xác mã.
            $row = $this->firstExactMatch($result, $maKh);

            if (null === $row) {
                $this->dispatch('error', message: 'Không tìm thấy khách hàng.');
                return;
            }
            $_ = fn (string $lower, string $upper, mixed $default = null): mixed =>
                $this->field($row, $lower, $upper, $default);

            $this->ma_kh       = $_('ma_kh', 'MA_KH', $maKh);
            $this->ten_kh      = $_('ten_kh', 'TEN_KH', '');
            $this->dia_chi     = $_('dia_chi', 'DIA_CHI', '');
            $this->ma_so_thue  = $_('ma_so_thue', 'MA_SO_THUE', '');
            $this->dien_thoai  = $_('tel', 'TEL', '');
            $this->fax         = $_('fax', 'FAX', '');
            $this->email       = $_('email', 'EMAIL', '');
            $this->ma_nt       = $_('ma_nt', 'MA_NT', 'VND');
            $this->tk_cn       = $_('tk', 'TK');
            $this->ma_plkh1    = $_('ma_plkh1', 'MA_PLKH1');
            $this->ma_plkh2    = $_('ma_plkh2', 'MA_PLKH2');
            $this->ma_plkh3    = $_('ma_plkh3', 'MA_PLKH3');
            $this->ma_nhkh     = $_('ma_nhkh', 'MA_NHKH');
            $this->nguoi_gd    = $_('nguoi_gd', 'NGUOI_GD');
            $this->ghi_chu     = $_('ghi_chu', 'GHI_CHU');
            $this->isKh        = (bool) $_('iskh', 'ISKH', true);
            $this->isNcc       = (bool) $_('isncc', 'ISNCC', false);
            $this->isNv        = (bool) $_('isnv', 'ISNV', false);
            $this->ksd         = (bool) $_('ksd', 'KSD', false);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error', message: 'Không thể tải khách hàng: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::so.dict.ardmkh-form', [
            'nhomKhOptions' => $this->nhomKhOptions,
            'plkhOptions'   => $this->plkhOptions,
        ])->layout('catalog::layouts.app');
    }

    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'ma_plkh1' => 'nullable|string|max:8',
            'ma_plkh2' => 'nullable|string|max:8',
            'ma_plkh3' => 'nullable|string|max:8',
            'ma_nhkh'  => 'nullable|string|max:8',
            'ma_nt'    => 'nullable|string|max:10',
            'isKh'     => 'boolean',
            'ksd'      => 'boolean',
        ]);
    }

    protected function persist(string $procedureClass): void
    {
        $maKh = strtoupper(trim((string) $this->ma_kh));
        $user = auth()->user()->name ?? 'system';

        try {
            $result = $procedureClass::call([
                'pMa_cty'       => \Diepxuan\Simba\SModel\SModel::CTY,
                'pMa_kh'        => $maKh,
                'pLoai'         => '1',
                'pTen_kh'       => $this->stringValue($this->ten_kh),
                'pMa_so_thue'   => $this->stringValue($this->ma_so_thue),
                'pDia_chi'      => $this->stringValue($this->dia_chi),
                'pTel'          => $this->stringValue($this->dien_thoai),
                'pFax'          => $this->stringValue($this->fax),
                'pEmail'        => $this->stringValue($this->email),
                // Các cột NOT NULL trong ArDmKh (simba-docs/tables/ArDmKh.md):
                // thiếu tham số -> SP nhận NULL -> "Cannot insert the value NULL".
                'pHome_page'    => $this->stringValue($this->home_page ?? ''),
                'pMa_ngh'       => $this->stringValue($this->ma_ngh),
                'pTen_nh'       => $this->stringValue($this->ten_nh),
                'pCn_nh'        => $this->stringValue($this->cn_nh),
                'pSo_tk_nh'     => $this->stringValue($this->so_tk_nh),
                'pTinh_tp_nh'   => $this->stringValue($this->tinh_tp_nh),
                'pMa_tt'        => $this->stringValue($this->ma_tt),
                'pMa_httt'      => $this->stringValue($this->ma_httt),
                'pGh_no'        => $this->numberValue($this->gh_no),
                'pHan_ck'       => $this->numberValue($this->han_ck),
                'pTl_ck'        => $this->numberValue($this->tl_ck),
                'pHan_tt'       => $this->numberValue($this->han_tt),
                'pLs_qh'        => $this->numberValue($this->ls_qh),
                'pTinh_dt_nb'   => $this->tinh_dt_nb ? 1 : 0,
                'pTk'           => $this->stringValue($this->tk_cn),
                'pMa_plkh1'     => $this->stringValue($this->ma_plkh1),
                'pMa_plkh2'     => $this->stringValue($this->ma_plkh2),
                'pMa_plkh3'     => $this->stringValue($this->ma_plkh3),
                'pMa_nhkh'      => $this->stringValue($this->ma_nhkh),
                'pNguoi_gd'     => $this->stringValue($this->nguoi_gd),
                'pGhi_chu'      => $this->stringValue($this->ghi_chu),
                // Lay tu checkbox nguoi dung chon, KHONG hardcode.
                'pIskh'         => $this->isKh ? 1 : 0,
                'pIsncc'        => $this->isNcc ? 1 : 0,
                'pIsnv'         => $this->isNv ? 1 : 0,
                'pKsd'          => $this->ksd ? 1 : 0,
                'pLUser'        => $user,
            ]);

            $this->assertProcedureSuccess($result);

            $this->dispatch('khachhang-saved');
            $this->dispatch('success', message: 'Đã lưu khách hàng ' . $maKh);
            $this->redirect(simbaroute('so.dict.ardmkh'), navigate: true);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error', message: 'Không thể lưu khách hàng: ' . $e->getMessage());
        }
    }

}
