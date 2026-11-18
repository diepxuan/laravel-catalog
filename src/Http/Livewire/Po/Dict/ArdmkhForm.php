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

    /** Set khi không tìm thấy đối tượng: view sẽ chuyển hướng về list kèm thông báo. */
    public ?string $redirectToList = null;

    /** Thông báo lỗi hiển thị trong lúc chuyển hướng. */
    public string $redirectMessage = '';
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

    // Các cột NOT NULL còn lại của ArDmKh (simba-docs/tables/ArDmKh.md).
    // Khai báo ở base class để SO/PO/CA dùng chung; thiếu thì persist() gửi
    // NULL -> SP báo "Cannot insert the value NULL into column ...".
    public ?string $home_page = null;
    public ?string $ma_httt = null;
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

    public bool $tinh_dt_nb = false;

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

            $row = $this->firstExactMatch($result, $maKh);

            if (null === $row) {
                $this->handleNotFound($maKh);

                return;
            }
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

    /**
     * Nhãn loại đối tượng — form con override (SO = khách hàng, CA = nhân viên).
     */
    protected function doiTuongLabel(): string
    {
        return 'nhà cung cấp';
    }

    /**
     * Route name của màn danh sách tương ứng.
     */
    protected function listRouteName(): string
    {
        return 'po.dict.ardmkh';
    }

    /**
     * Không tìm thấy đối tượng theo mã (đã lọc khớp chính xác).
     *
     * KHÔNG dùng `$this->redirect()` trong `mount()`: Livewire 3.8 + Laravel 11
     * có bug ở `SupportRedirects::dehydrate()` — gọi `abort(redirect($to))` mà
     * `redirect()` trả `Redirector` (không phải int) → TypeError.
     *
     * Cách làm: set `$redirectToList` + message, `render()` trả một view nhỏ
     * hiển thị thông báo rồi chuyển trang (kèm `?error=` để list hiển thị).
     */
    protected function handleNotFound(string $maKh): void
    {
        $this->redirectMessage = sprintf('Đối tượng %s không phải %s.', $maKh, $this->doiTuongLabel());
        $this->redirectToList  = (string) simbaroute($this->listRouteName())
            . '?error=' . rawurlencode($this->redirectMessage);
    }

    public function save(): void
    {
        $this->validate();
        $this->ensureAtLeastOneType();

        $procedureClass = 'create' === $this->mode
            ? 'Diepxuan\\Simba\\StoredProcedures\\AsARInsDMKH'
            : 'Diepxuan\\Simba\\StoredProcedures\\AsARUpdDMKH';

        $this->persist($procedureClass);
    }

    /**
     * Đảm bảo đối tượng luôn thuộc ít nhất một loại.
     *
     * Đối tượng không mang cờ nào (iskh/isncc/isnv đều 0) sẽ không xuất hiện ở
     * bất kỳ màn danh mục nào của Portal, vì mỗi màn lọc theo module
     * (`asARGetDMKH` với pModuleId AR/AP/CA). Vì vậy khi người dùng bỏ hết ba
     * lựa chọn, mặc định chọn **khách hàng** (`isKh = true`).
     *
     * Chọn khách hàng vì `ArDmKh` là bảng công nợ phải thu (AR); đây cũng là
     * loại đối tượng phổ biến nhất. Không dispatch thông báo — Sếp yêu cầu lưu
     * là tự chuyển luôn.
     */
    protected function ensureAtLeastOneType(): void
    {
        if ($this->isKh || $this->isNcc || $this->isNv) {
            return;
        }

        $this->isKh = true;
    }

    public function render(): View
    {
        if (null !== $this->redirectToList) {
            return view('catalog::components.ardmkh-redirect', [
                'target'  => $this->redirectToList,
                'message' => $this->redirectMessage,
            ]);
        }

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

    /**
     * Tìm đúng bản ghi theo mã đối tượng trong kết quả `asARGetDMKH`.
     *
     * `asARGetDMKH` nhận `pMa_kh` là **prefix search** (xem
     * `simba-docs/procedures/AR/procedures.md`, mục `asARGetDMKH`), nên kết quả
     * có thể chứa nhiều mã cùng tiền tố: tra `K1` sẽ trả về cả `K1 HUYEN`.
     * Nếu lấy `first()` ngay thì form sửa mở NHẦM đối tượng và có thể ghi đè
     * dữ liệu của bản ghi khác.
     *
     * Hàm này lọc lại theo mã khớp chính xác (không phân biệt hoa/thường,
     * bỏ khoảng trắng thừa). Không sửa SP.
     *
     * Trả `null` khi không có mã nào khớp tuyệt đối — caller phải báo
     * "không tìm thấy" thay vì nạp bản ghi khác. Trước đây dùng `first()` nên
     * mã không tồn tại (vd `K1` khi chỉ có `K1 HUYEN`) vẫn mở ra bản ghi khác
     * và có thể ghi đè dữ liệu sai đối tượng.
     *
     * @param iterable<int, mixed> $rows
     *
     * @return null|array<string, mixed>|object
     */
    protected function firstExactMatch(iterable $rows, string $maKh): array|object|null
    {
        $target = mb_strtoupper(trim($maKh));

        if ('' === $target) {
            return null;
        }

        foreach ($rows as $row) {
            $ma = mb_strtoupper(trim((string) $this->field($row, 'ma_kh', 'MA_KH', '')));

            if ($ma === $target) {
                return $row;
            }
        }

        return null;
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
                // home_page là cột NOT NULL trong ArDmKh (PROJECT_CONVENTIONS §3):
                // không gửi -> SP nhận NULL -> UPDATE fails. Phải convert null -> ''.
                'pHome_page' => $this->stringValue($this->home_page ?? ''),
                'pNguoi_gd' => $this->stringValue($this->nguoi_gd),
                // Các cột NOT NULL trong ArDmKh (simba-docs/tables/ArDmKh.md):
                // thiếu tham số -> SP nhận NULL -> "Cannot insert the value NULL".
                'pMa_ngh' => $this->stringValue($this->ma_ngh),
                'pTen_nh' => $this->stringValue($this->ten_nh),
                'pCn_nh' => $this->stringValue($this->cn_nh),
                'pSo_tk_nh' => $this->stringValue($this->so_tk_nh),
                'pTinh_tp_nh' => $this->stringValue($this->tinh_tp_nh),
                'pMa_tt' => $this->stringValue($this->ma_tt),
                'pMa_httt' => $this->stringValue($this->ma_httt),
                'pGh_no' => $this->numberValue($this->gh_no),
                'pHan_ck' => $this->numberValue($this->han_ck),
                'pTl_ck' => $this->numberValue($this->tl_ck),
                'pHan_tt' => $this->numberValue($this->han_tt),
                'pLs_qh' => $this->numberValue($this->ls_qh),
                // tinh_dt_nb: bit NOT NULL trong ArDmKh.
                'pTinh_dt_nb' => $this->tinh_dt_nb ? 1 : 0,
                'pTk' => $this->stringValue($this->tk_cn),
                'pMa_httt_po' => $this->stringValue($this->ma_httt_po),
                'pMa_plkh1' => $this->stringValue($this->ma_plkh1),
                'pMa_plkh2' => $this->stringValue($this->ma_plkh2),
                'pMa_plkh3' => $this->stringValue($this->ma_plkh3),
                'pMa_nhkh' => $this->stringValue($this->ma_nhkh),
                'pGhi_chu' => $this->stringValue($this->ghi_chu),
                // Lay tu checkbox nguoi dung chon, KHONG hardcode: mot doi tuong
                // co the dong thoi la khach hang + nha cung cap + nhan vien.
                // Truoc day hardcode 0/1/0 nen bam Luu se xoa mat co khach hang
                // cua doi tuong (vd 2VANQT bi bien thanh chi con nha cung cap).
                'pIskh' => $this->isKh ? 1 : 0,
                'pIsncc' => $this->isNcc ? 1 : 0,
                'pIsnv' => $this->isNv ? 1 : 0,
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
