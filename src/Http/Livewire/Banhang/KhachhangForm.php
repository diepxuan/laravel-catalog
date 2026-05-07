<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-07 10:15:07
 */

namespace Diepxuan\Catalog\Http\Livewire\Banhang;

use Diepxuan\Catalog\Models\ArDmNhKh;
use Diepxuan\Catalog\Models\ArDmPlKh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Diepxuan\Simba\StoredProcedures\AsARInsDMKH;
use Diepxuan\Simba\StoredProcedures\AsARUpdDMKH;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Component KhachhangForm.
 *
 * Form tạo mới / chỉnh sửa khách hàng.
 */
class KhachhangForm extends Component
{
    /**
     * Chế độ: 'create' hoặc 'edit'.
     */
    public string $mode = 'create';

    /**
     * Mã khách hàng (dùng cho edit mode).
     */
    public ?string $ma_kh = null;

    // Form fields
    public string $ten_kh     = '';
    public string $dia_chi    = '';
    public string $ma_so_thue = '';
    public string $dien_thoai = '';
    public string $fax        = '';
    public string $email      = '';
    public string $ma_nt      = 'VND';
    public ?string $tk_cn     = null;
    public ?string $ma_plkh1  = null;
    public ?string $ma_plkh2  = null;
    public ?string $ma_plkh3  = null;
    public ?string $ma_nhkh   = null;
    public bool $isKh         = true;

    /**
     * Flag: có giao dịch liên quan.
     */
    public bool $hasTransactions = false;

    /**
     * Danh sách nhóm KH cho dropdown.
     */
    public Collection $nhomKhOptions;

    /**
     * Danh sách phân loại KH cho dropdown.
     *
     * @var array<int, Collection>
     */
    public array $plkhOptions = [];

    /**
     * Validation messages.
     *
     * @var array<string, string>
     */
    protected $messages = [
        'ma_kh.required'  => 'Mã khách hàng không được để trống.',
        'ten_kh.required' => 'Tên khách hàng không được để trống.',
        'email.email'     => 'Email không đúng định dạng.',
    ];

    /**
     * Khởi tạo component.
     *
     * @param null|string $id mã khách hàng (edit mode)
     */
    public function mount(?string $id = null): void
    {
        $this->loadDropdowns();

        if ($id) {
            $this->mode = 'edit';
            $this->loadKhachHang($id);
        }
    }

    /**
     * Tải thông tin khách hàng để chỉnh sửa qua Stored Procedure.
     *
     * @param string $maKh mã khách hàng
     */
    public function loadKhachHang(string $maKh): void
    {
        try {
            $result = AsARGetDMKH::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh'  => $maKh,
            ]);

            if ($result->isEmpty()) {
                $this->dispatch('error', message: 'Không tìm thấy khách hàng.');

                return;
            }

            $row = $result->first();

            $this->ma_kh           = $row['MA_KH'] ?? $maKh;
            $this->ten_kh          = $row['TEN_KH'] ?? '';
            $this->dia_chi         = $row['DIA_CHI'] ?? '';
            $this->ma_so_thue      = $row['MA_SO_THUE'] ?? '';
            $this->dien_thoai      = $row['TEL'] ?? '';
            $this->fax             = $row['FAX'] ?? '';
            $this->email           = $row['EMAIL'] ?? '';
            $this->ma_nt           = $row['MA_NT'] ?? 'VND';
            $this->tk_cn           = $row['TK_CN'] ?? null;
            $this->ma_plkh1        = $row['MA_PLKH1'] ?? null;
            $this->ma_plkh2        = $row['MA_PLKH2'] ?? null;
            $this->ma_plkh3        = $row['MA_PLKH3'] ?? null;
            $this->ma_nhkh         = $row['MA_NHKH'] ?? null;
            $this->isKh            = (bool) ($row['IS_KH'] ?? 1);
            $this->hasTransactions = false;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể tải thông tin khách hàng: ' . $e->getMessage());
        }
    }

    /**
     * Lưu khách hàng.
     */
    public function save(): void
    {
        $this->validate();

        if ('create' === $this->mode) {
            $this->createKhachHang();
        } else {
            $this->updateKhachHang();
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('catalog::banhang.khachhang-form', [
            'nhomKhOptions' => $this->nhomKhOptions,
            'plkhOptions'   => $this->plkhOptions,
        ])->layout('catalog::layouts.app');
    }

    /**
     * Validation rules.
     *
     * @var array<string, string>
     */
    protected function rules(): array
    {
        return [
            'ma_kh'      => 'required|string|max:50',
            'ten_kh'     => 'required|string|max:200',
            'dia_chi'    => 'nullable|string|max:500',
            'ma_so_thue' => 'nullable|string|max:50',
            'dien_thoai' => 'nullable|string|max:50',
            'fax'        => 'nullable|string|max:50',
            'email'      => 'nullable|email|max:100',
            'ma_nt'      => 'nullable|string|max:10',
            'tk_cn'      => 'nullable|string|max:20',
            'ma_plkh1'   => 'nullable|string|max:50',
            'ma_plkh2'   => 'nullable|string|max:50',
            'ma_plkh3'   => 'nullable|string|max:50',
            'ma_nhkh'    => 'nullable|string|max:50',
            'isKh'       => 'boolean',
        ];
    }

    /**
     * Tải danh sách dropdown.
     */
    protected function loadDropdowns(): void
    {
        $this->nhomKhOptions = ArDmNhKh::orderBy('ma_nhkh')->get();

        $this->plkhOptions[1] = ArDmPlKh::loai(1)->orderBy('ma_plkh')->get();
        $this->plkhOptions[2] = ArDmPlKh::loai(2)->orderBy('ma_plkh')->get();
        $this->plkhOptions[3] = ArDmPlKh::loai(3)->orderBy('ma_plkh')->get();
    }

    /**
     * Tạo mới khách hàng qua Stored Procedure.
     */
    protected function createKhachHang(): void
    {
        $maKh = strtoupper(trim($this->ma_kh));
        $user = auth()->user()->name ?? 'system';

        try {
            $result = AsARInsDMKH::call([
                'pMa_cty'     => SModel::CTY,
                'pMa_kh'      => $maKh,
                'pLoai'       => 1,
                'pTen_kh'     => $this->ten_kh,
                'pMa_so_thue' => $this->ma_so_thue,
                'pDia_chi'    => $this->dia_chi,
                'pTel'        => $this->dien_thoai,
                'pFax'        => $this->fax,
                'pEmail'      => $this->email,
                'pTk'         => $this->tk_cn,
                'pMa_plkh1'   => $this->ma_plkh1,
                'pMa_plkh2'   => $this->ma_plkh2,
                'pMa_plkh3'   => $this->ma_plkh3,
                'pMa_nhkh'    => $this->ma_nhkh,
                'pIskh'       => $this->isKh ? 1 : 0,
                'pKsd'        => 1,
                'pLUser'      => $user,
            ]);

            $this->dispatch('success', message: 'Đã thêm khách hàng ' . $maKh);
            $this->dispatch('khachhang-saved');
            $this->redirect(route('ar.khachhang'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể thêm khách hàng: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật khách hàng qua Stored Procedure.
     */
    protected function updateKhachHang(): void
    {
        $maKh = strtoupper(trim($this->ma_kh));
        $user = auth()->user()->name ?? 'system';

        try {
            $result = AsARUpdDMKH::call([
                'pMa_cty'     => SModel::CTY,
                'pMa_kh'      => $maKh,
                'pLoai'       => 1,
                'pTen_kh'     => $this->ten_kh,
                'pMa_so_thue' => $this->ma_so_thue,
                'pDia_chi'    => $this->dia_chi,
                'pTel'        => $this->dien_thoai,
                'pFax'        => $this->fax,
                'pEmail'      => $this->email,
                'pTk'         => $this->tk_cn,
                'pMa_plkh1'   => $this->ma_plkh1,
                'pMa_plkh2'   => $this->ma_plkh2,
                'pMa_plkh3'   => $this->ma_plkh3,
                'pMa_nhkh'    => $this->ma_nhkh,
                'pIskh'       => $this->isKh ? 1 : 0,
                'pKsd'        => 1,
                'pLUser'      => $user,
            ]);

            $this->dispatch('success', message: 'Đã cập nhật khách hàng ' . $maKh);
            $this->dispatch('khachhang-saved');
            $this->redirect(route('ar.khachhang'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể cập nhật khách hàng: ' . $e->getMessage());
        }
    }
}
