<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-05 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Po\Dict;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Illuminate\View\View;
use Livewire\Component;

class ArdmkhForm extends Component
{
    public string $mode = 'create';
    public ?string $ma_kh = null;
    public string $ten_kh = '';
    public string $dia_chi = '';
    public string $ma_so_thue = '';
    public string $dien_thoai = '';
    public string $fax = '';
    public string $email = '';
    public ?string $nguoi_gd = null;
    public ?string $tk_cn = null;
    public ?string $ma_httt_po = null;
    public ?string $ghi_chu = null;

    protected $messages = [
        'ma_kh.required' => 'Mã nhà cung cấp không được để trống.',
        'ten_kh.required' => 'Tên nhà cung cấp không được để trống.',
        'email.email' => 'Email không đúng định dạng.',
    ];

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->mode = 'edit';
            $this->loadDoiTuong($id);
        }
    }

    public function loadDoiTuong(string $maKh): void
    {
        try {
            $result = AsARGetDMKH::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh' => $maKh,
                'pModuleId' => 'AP',
            ]);

            if ($result->isEmpty()) {
                $this->dispatch('error', message: 'Không tìm thấy nhà cung cấp.');
                return;
            }

            $row = $result->first();
            $this->ma_kh       = $this->field($row, 'ma_kh', 'MA_KH', $maKh);
            $this->ten_kh      = $this->field($row, 'ten_kh', 'TEN_KH', '');
            $this->dia_chi     = $this->field($row, 'dia_chi', 'DIA_CHI', '');
            $this->ma_so_thue  = $this->field($row, 'ma_so_thue', 'MA_SO_THUE', '');
            $this->dien_thoai  = $this->field($row, 'tel', 'TEL', '');
            $this->fax         = $this->field($row, 'fax', 'FAX', '');
            $this->email       = $this->field($row, 'email', 'EMAIL', '');
            $this->nguoi_gd    = $this->field($row, 'nguoi_gd', 'NGUOI_GD');
            $this->tk_cn       = $this->field($row, 'tk', 'TK');
            $this->ma_httt_po  = $this->field($row, 'ma_httt_po', 'MA_HTTT_PO');
            $this->ghi_chu     = $this->field($row, 'ghi_chu', 'GHI_CHU');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể tải nhà cung cấp: ' . $e->getMessage());
        }
    }

    public function save(): void
    {
        $this->validate();
        $procedureClass = 'create' === $this->mode
            ? 'Diepxuan\\Simba\\StoredProcedures\\AsARInsDMKH'
            : 'Diepxuan\\Simba\\StoredProcedures\\AsARUpdDMKH';

        $this->persist($procedureClass);
    }

    public function render(): View
    {
        return view('catalog::po.dict.ardmkh-form')->layout('catalog::layouts.app');
    }

    protected function rules(): array
    {
        return [
            'ma_kh' => 'required|string|max:50',
            'ten_kh' => 'required|string|max:200',
            'dia_chi' => 'nullable|string|max:500',
            'ma_so_thue' => 'nullable|string|max:50',
            'dien_thoai' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'nguoi_gd' => 'nullable|string|max:100',
            'tk_cn' => 'nullable|string|max:20',
            'ma_httt_po' => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string|max:500',
        ];
    }

    /**
     * @param object|array<string, mixed> $row
     */
    private function field(object|array $row, string $lower, string $upper, mixed $default = null): mixed
    {
        if (is_array($row)) {
            return $row[$lower] ?? $row[$upper] ?? $default;
        }

        return $row->{$lower} ?? $row->{$upper} ?? $default;
    }

    protected function persist(string $procedureClass): void
    {
        $maKh = strtoupper(trim((string) $this->ma_kh));
        $user = auth()->user()->name ?? 'system';

        try {
            $procedureClass::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh' => $maKh,
                'pLoai' => 1,
                'pTen_kh' => $this->ten_kh,
                'pMa_so_thue' => $this->ma_so_thue,
                'pDia_chi' => $this->dia_chi,
                'pTel' => $this->dien_thoai,
                'pFax' => $this->fax,
                'pEmail' => $this->email,
                'pNguoi_gd' => $this->nguoi_gd,
                'pTk' => $this->tk_cn,
                'pMa_httt_po' => $this->ma_httt_po,
                'pGhi_chu' => $this->ghi_chu,
                'pIskh' => 0,
                'pIsncc' => 1,
                'pIsnv' => 0,
                'pKsd' => 0,
                'pLUser' => $user,
            ]);

            $this->dispatch('success', message: 'Đã lưu nhà cung cấp ' . $maKh);
            $this->redirect(simbaroute('po.dict.ardmkh'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể lưu nhà cung cấp: ' . $e->getMessage());
        }
    }
}
