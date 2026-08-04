<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-03 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Ca\Dict;

use Diepxuan\Catalog\Http\Livewire\Po\Dict\ArdmkhForm as BaseForm;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Diepxuan\Simba\StoredProcedures\AsARInsDMKH;
use Diepxuan\Simba\StoredProcedures\AsARUpdDMKH;
use Illuminate\View\View;

class ArdmkhForm extends BaseForm
{
    public ?string $home_page = null;
    public ?string $ma_httt = null;
    public ?string $ma_httt_po = null;
    public ?string $ma_ngh = null;
    public ?string $ten_nh = null;
    public ?string $cn_nh = null;
    public ?string $so_tk_nh = null;
    public ?string $tinh_tp_nh = null;
    public ?string $ma_tt = null;
    public ?float $gh_no = null;
    public ?float $han_ck = null;
    public ?float $tl_ck = null;
    public ?float $han_tt = null;
    public ?float $ls_qh = null;

    protected $messages = [
        'ma_kh.required' => 'Mã nhân viên không được để trống.',
        'ten_kh.required' => 'Tên nhân viên không được để trống.',
        'email.email' => 'Email không đúng định dạng.',
    ];

    public bool $isNv = true;
    public bool $tinh_dt_nb = false;

    public function loadDoiTuong(string $maKh): void
    {
        try {
            $result = AsARGetDMKH::getEmployees(search: $maKh);

            if ($result->isEmpty()) {
                $this->dispatch('error', message: 'Không tìm thấy nhân viên.');
                return;
            }

            $row = $result->first();
            $this->ma_kh = $this->field($row, 'ma_kh', 'MA_KH', $maKh);
            $this->ten_kh = $this->field($row, 'ten_kh', 'TEN_KH', '');
            $this->dia_chi = $this->field($row, 'dia_chi', 'DIA_CHI', '');
            $this->ma_so_thue = $this->field($row, 'ma_so_thue', 'MA_SO_THUE', '');
            $this->dien_thoai = $this->field($row, 'tel', 'TEL', '');
            $this->fax = $this->field($row, 'fax', 'FAX', '');
            $this->email = $this->field($row, 'email', 'EMAIL', '');
            $this->home_page = $this->field($row, 'home_page', 'HOME_PAGE');
            $this->nguoi_gd = $this->field($row, 'nguoi_gd', 'NGUOI_GD');
            $this->ma_httt = $this->field($row, 'ma_httt', 'MA_HTTT');
            $this->ma_httt_po = $this->field($row, 'ma_httt_po', 'MA_HTTT_PO');
            $this->gh_no = $this->toNullableFloat($this->field($row, 'gh_no', 'GH_NO'));
            $this->han_tt = $this->toNullableFloat($this->field($row, 'han_tt', 'HAN_TT'));
            $this->ma_ngh = $this->field($row, 'ma_ngh', 'MA_NGH');
            $this->ten_nh = $this->field($row, 'ten_nh', 'TEN_NH');
            $this->cn_nh = $this->field($row, 'cn_nh', 'CN_NH');
            $this->so_tk_nh = $this->field($row, 'so_tk_nh', 'SO_TK_NH');
            $this->tinh_tp_nh = $this->field($row, 'tinh_tp_nh', 'TINH_TP_NH');
            $this->tk_cn = $this->field($row, 'tk', 'TK');
            $this->ma_plkh1 = $this->field($row, 'ma_plkh1', 'MA_PLKH1');
            $this->ma_plkh2 = $this->field($row, 'ma_plkh2', 'MA_PLKH2');
            $this->ma_plkh3 = $this->field($row, 'ma_plkh3', 'MA_PLKH3');
            $this->ma_nhkh = $this->field($row, 'ma_nhkh', 'MA_NHKH');
            $this->ma_tt = $this->field($row, 'ma_tt', 'MA_TT');
            $this->han_ck = $this->toNullableFloat($this->field($row, 'han_ck', 'HAN_CK'));
            $this->tl_ck = $this->toNullableFloat($this->field($row, 'tl_ck', 'TL_CK'));
            $this->ls_qh = $this->toNullableFloat($this->field($row, 'ls_qh', 'LS_QH'));
            $this->ghi_chu = $this->field($row, 'ghi_chu', 'GHI_CHU');
            $this->isKh = (bool) ($this->field($row, 'iskh', 'ISKH', false));
            $this->isNcc = (bool) ($this->field($row, 'isncc', 'ISNCC', false));
            $this->isNv = (bool) ($this->field($row, 'isnv', 'ISNV', true));
            $this->tinh_dt_nb = (bool) ($this->field($row, 'tinh_dt_nb', 'TINH_DT_NB', false));
            $this->ksd = (bool) ($this->field($row, 'ksd', 'KSD', false));
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error', message: 'Không thể tải nhân viên: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::ca.dict.ardmkh-form', [
            'nhomKhOptions' => $this->nhomKhOptions,
            'plkhOptions'   => $this->plkhOptions,
        ])->layout('catalog::layouts.app');
    }

    public function save(): void
    {
        if (!$this->isKh && !$this->isNcc && !$this->isNv) {
            $this->addError('isNv', 'Phải chọn ít nhất một loại đối tượng: khách hàng, nhà cung cấp hoặc nhân viên.');

            return;
        }

        parent::save();
    }

    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'home_page' => 'nullable|string|max:100',
            'ma_httt' => 'nullable|string|max:20',
            'ma_httt_po' => 'nullable|string|max:20',
            'gh_no' => 'nullable|numeric',
            'han_tt' => 'nullable|numeric',
            'ma_ngh' => 'nullable|string|max:20',
            'ten_nh' => 'nullable|string|max:100',
            'cn_nh' => 'nullable|string|max:100',
            'so_tk_nh' => 'nullable|string|max:50',
            'tinh_tp_nh' => 'nullable|string|max:100',
            'ma_plkh1' => 'nullable|string|max:8',
            'ma_plkh2' => 'nullable|string|max:8',
            'ma_plkh3' => 'nullable|string|max:8',
            'ma_nhkh' => 'nullable|string|max:8',
            'ma_tt' => 'nullable|string|max:8',
            'han_ck' => 'nullable|numeric',
            'tl_ck' => 'nullable|numeric',
            'ls_qh' => 'nullable|numeric',
            'isKh' => 'boolean',
            'isNcc' => 'boolean',
            'isNv' => 'boolean',
            'tinh_dt_nb' => 'boolean',
            'ksd' => 'boolean',
        ]);
    }

    protected function persist(string $procedureClass): void
    {
        $maKh = strtoupper(trim((string) $this->ma_kh));
        $user = auth()->user()->name ?? 'system';

        try {
            $result = $procedureClass::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh' => $maKh,
                'pLoai' => '1',
                'pTen_kh' => $this->stringValue($this->ten_kh),
                'pMa_so_thue' => $this->stringValue($this->ma_so_thue),
                'pDia_chi' => $this->stringValue($this->dia_chi),
                'pTel' => $this->stringValue($this->dien_thoai),
                'pFax' => $this->stringValue($this->fax),
                'pEmail' => $this->stringValue($this->email),
                'pHome_page' => $this->stringValue($this->home_page),
                'pNguoi_gd' => $this->stringValue($this->nguoi_gd),
                'pMa_httt' => $this->stringValue($this->ma_httt),
                'pMa_httt_po' => $this->stringValue($this->ma_httt_po),
                'pGh_no' => $this->numberValue($this->gh_no),
                'pHan_tt' => $this->numberValue($this->han_tt),
                'pMa_ngh' => $this->stringValue($this->ma_ngh),
                'pTen_nh' => $this->stringValue($this->ten_nh),
                'pCn_nh' => $this->stringValue($this->cn_nh),
                'pSo_tk_nh' => $this->stringValue($this->so_tk_nh),
                'pTinh_tp_nh' => $this->stringValue($this->tinh_tp_nh),
                'pTk' => $this->stringValue($this->tk_cn),
                'pMa_plkh1' => $this->stringValue($this->ma_plkh1),
                'pMa_plkh2' => $this->stringValue($this->ma_plkh2),
                'pMa_plkh3' => $this->stringValue($this->ma_plkh3),
                'pMa_nhkh' => $this->stringValue($this->ma_nhkh),
                'pMa_tt' => $this->stringValue($this->ma_tt),
                'pHan_ck' => $this->numberValue($this->han_ck),
                'pTl_ck' => $this->numberValue($this->tl_ck),
                'pLs_qh' => $this->numberValue($this->ls_qh),
                'pGhi_chu' => $this->stringValue($this->ghi_chu),
                'pTinh_dt_nb' => $this->tinh_dt_nb ? 1 : 0,
                'pIskh' => $this->isKh ? 1 : 0,
                'pIsncc' => $this->isNcc ? 1 : 0,
                'pIsnv' => $this->isNv ? 1 : 0,
                'pKsd' => $this->ksd ? 1 : 0,
                'pLUser' => $user,
            ]);

            $this->assertProcedureSuccess($result);

            $this->dispatch('success', message: 'Đã lưu nhân viên ' . $maKh);
            $this->redirect(simbaroute('ca.dict.ardmkh'), navigate: true);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error', message: 'Không thể lưu nhân viên: ' . $e->getMessage());
        }
    }

    private function toNullableFloat(mixed $value): ?float
    {
        if (null === $value || '' === $value) {
            return null;
        }

        return (float) $value;
    }
}
