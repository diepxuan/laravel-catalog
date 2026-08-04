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

use Diepxuan\Catalog\Models\Simba\ArDmNhKh;
use Diepxuan\Catalog\Models\Simba\ArDmPlKh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Illuminate\Support\Collection;
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
    public ?string $ma_plkh1 = null;
    public ?string $ma_plkh2 = null;
    public ?string $ma_plkh3 = null;
    public ?string $ma_nhkh  = null;
    public bool $isKh  = false;
    public bool $isNcc = false;
    public bool $isNv  = false;
    public bool $ksd   = false;

    /** @var Collection */
    public Collection $nhomKhOptions;
    /** @var array<int, Collection> */
    public array $plkhOptions = [];

    protected $messages = [
        'ma_kh.required' => 'Mã nhà cung cấp không được để trống.',
        'ten_kh.required' => 'Tên nhà cung cấp không được để trống.',
        'email.email' => 'Email không đúng định dạng.',
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
            $this->ma_plkh1    = $this->field($row, 'ma_plkh1', 'MA_PLKH1');
            $this->ma_plkh2    = $this->field($row, 'ma_plkh2', 'MA_PLKH2');
            $this->ma_plkh3    = $this->field($row, 'ma_plkh3', 'MA_PLKH3');
            $this->ma_nhkh     = $this->field($row, 'ma_nhkh', 'MA_NHKH');
            $this->isKh        = (bool) $this->field($row, 'iskh', 'ISKH', false);
            $this->isNcc       = (bool) $this->field($row, 'isncc', 'ISNCC', true);
            $this->isNv        = (bool) $this->field($row, 'isnv', 'ISNV', false);
            $this->ksd         = (bool) $this->field($row, 'ksd', 'KSD', false);
            $this->ghi_chu     = $this->field($row, 'ghi_chu', 'GHI_CHU');
        } catch (\Exception $e) {
            report($e);
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
        return view('catalog::po.dict.ardmkh-form', [
            'nhomKhOptions' => $this->nhomKhOptions,
            'plkhOptions'   => $this->plkhOptions,
        ])->layout('catalog::layouts.app');
    }

    protected function rules(): array
    {
        return [
            'ma_kh' => 'required|string|max:20',
            'ten_kh' => 'required|string|max:100',
            'dia_chi' => 'nullable|string|max:255',
            'ma_so_thue' => 'nullable|string|max:20',
            'dien_thoai' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:30',
            'nguoi_gd' => 'nullable|string|max:30',
            'tk_cn' => 'nullable|string|max:20',
            'ma_httt_po' => 'nullable|string|max:20',
            'ma_plkh1' => 'nullable|string|max:8',
            'ma_plkh2' => 'nullable|string|max:8',
            'ma_plkh3' => 'nullable|string|max:8',
            'ma_nhkh'  => 'nullable|string|max:8',
            'isKh'     => 'boolean',
            'isNcc'    => 'boolean',
            'isNv'     => 'boolean',
            'ksd'      => 'boolean',
            'ghi_chu' => 'nullable|string|max:255',
        ];
    }

    /**
     * @param object|array<string, mixed> $row
     */
    protected function field(object|array $row, string $lower, string $upper, mixed $default = null): mixed
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
            $result = $procedureClass::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh' => $maKh,
                'pLoai' => 1,
                'pTen_kh' => $this->stringValue($this->ten_kh),
                'pMa_so_thue' => $this->stringValue($this->ma_so_thue),
                'pDia_chi' => $this->stringValue($this->dia_chi),
                'pTel' => $this->stringValue($this->dien_thoai),
                'pFax' => $this->stringValue($this->fax),
                'pEmail' => $this->stringValue($this->email),
                'pNguoi_gd' => $this->stringValue($this->nguoi_gd),
                'pTk' => $this->stringValue($this->tk_cn),
                'pMa_httt_po' => $this->stringValue($this->ma_httt_po),
                'pMa_plkh1' => $this->stringValue($this->ma_plkh1),
                'pMa_plkh2' => $this->stringValue($this->ma_plkh2),
                'pMa_plkh3' => $this->stringValue($this->ma_plkh3),
                'pMa_nhkh' => $this->stringValue($this->ma_nhkh),
                'pGhi_chu' => $this->stringValue($this->ghi_chu),
                'pIskh' => 0,
                'pIsncc' => 1,
                'pIsnv' => 0,
                'pKsd' => $this->ksd ? 1 : 0,
                'pLUser' => $user,
            ]);

            $this->assertProcedureSuccess($result);

            $this->dispatch('success', message: 'Đã lưu nhà cung cấp ' . $maKh);
            $this->redirect(simbaroute('po.dict.ardmkh'), navigate: true);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error', message: 'Không thể lưu nhà cung cấp: ' . $e->getMessage());
        }
    }

    protected function assertProcedureSuccess(Collection $result): void
    {
        $row = $result->first();
        $pRet = is_array($row) ? ($row['pRet'] ?? null) : ($row->pRet ?? null);

        if (null !== $pRet && 0 !== (int) $pRet) {
            throw new \RuntimeException('Stored procedure trả về mã lỗi ' . (int) $pRet . '.');
        }
    }

    protected function stringValue(?string $value): string
    {
        return (string) ($value ?? '');
    }

    protected function numberValue(mixed $value): float
    {
        return null === $value || '' === $value ? 0.0 : (float) $value;
    }

    protected function loadDropdowns(): void
    {
        $this->nhomKhOptions = ArDmNhKh::orderBy('ma_nhkh')->get();
        $this->plkhOptions[1] = ArDmPlKh::loai(1)->orderBy('ma_plkh')->get();
        $this->plkhOptions[2] = ArDmPlKh::loai(2)->orderBy('ma_plkh')->get();
        $this->plkhOptions[3] = ArDmPlKh::loai(3)->orderBy('ma_plkh')->get();
    }
}
