<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-04-05 22:36:58
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;

use Diepxuan\Catalog\Models\ArDmKh;
use Diepxuan\Catalog\Models\CaCt2;
use Diepxuan\Catalog\Models\CaPh2;
use Diepxuan\Simba\Models\GlDmTk;
use Diepxuan\Simba\StoredProcedures\AsCADelCT2;
use Diepxuan\Simba\StoredProcedures\AsCAInsCT2;
use Diepxuan\Simba\StoredProcedures\AsCAInsPH2;
use Diepxuan\Simba\StoredProcedures\AsCAUpdPH2;
use Diepxuan\Simba\StoredProcedures\AsChkSoCt;
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
use Diepxuan\Simba\StoredProcedures\AsGetSoDuKh;
use Diepxuan\Simba\StoredProcedures\AsGetSttRec;
use Diepxuan\Simba\StoredProcedures\AsPostCaPh2_glct;
use Diepxuan\Simba\StoredProcedures\AsProcessCT;
use Illuminate\Support\Collection;
use Livewire\Component;

class Phieubaono extends Component
{
    // Constants cho cấu hình
    const MA_CT          = 'CA4';
    const DEFAULT_TK_CO  = 11_217;
    const DEFAULT_MA_NT  = 'VND';
    const DEFAULT_TY_GIA = 1;
    // Property nhận từ bên ngoài (Baono component)
    public ?string $sttRec = '';

    public ?string $pMa_Kh = null;
    public $pKh;
    public string $pDien_Giai = '';
    public $pTk_Co            = self::DEFAULT_TK_CO;
    public $pNgay_Ct;
    public $pSo_Ct;
    public $pNgay_Lap;
    public $pDia_Chi    = '';
    public $pNguoi_Gd   = '';
    public $pMa_Nt      = self::DEFAULT_MA_NT;
    public $pTy_Gia     = self::DEFAULT_TY_GIA;
    public $pT_Tien_Nt  = 0;
    public $pT_Thue     = 0; // Tự động = 0
    public $pT_TT       = 0; // Tự động = tổng cộng
    public $pT_Thue_Nt  = 0; // Tự động = 0
    public $pT_TT_Nt    = 0; // Tự động = tổng NT
    public $pKht_Tain   = '0'; // Tự động = 0
    public $pTrang_Thai = ''; // Tự động = '' (theo SQL history)
    public $pPost2Gl    = ''; // Tự động = '' (theo SQL history)

    public Collection $pCts;
    public $pSoDu;
    public $pTong_Ps_No = 0;

    // Properties cho sửa phiếu
    public $pStt_Rec = ''; // stt_rec của phiếu cần sửa (nếu có)
    public $pMode    = 'create'; // 'create' hoặc 'edit'

    // Số dư cho từng dòng chi tiết
    public Collection $pSoDuCts;

    // Danh sách tài khoản cho datalist
    public $glDmTks = [];

    protected $rules = [
        'pMa_Kh'          => 'required',
        'pTk_Co'          => 'required',
        'pNgay_Ct'        => 'required|date',
        'pNgay_Lap'       => 'required|date',
        'pDia_Chi'        => 'nullable|string|max:255',
        'pNguoi_Gd'       => 'nullable|string|max:30',
        'pMa_Nt'          => 'required|string|size:3',
        'pTy_Gia'         => 'required|numeric|min:0',
        'pCts.*.ma_tk'    => 'required',
        'pCts.*.ps_no'    => 'required|numeric|min:0',
        'pCts.*.ps_co'    => 'nullable|numeric|min:0',
        'pCts.*.ps_no_nt' => 'nullable|numeric|min:0',
        'pCts.*.ps_co_nt' => 'nullable|numeric|min:0',
        'pCts.*.ma_kh'    => 'nullable|string|max:20',
        'pCts.*.ma_hd'    => 'nullable|string|max:20',
        'pCts.*.ma_bp'    => 'nullable|string|max:20',
        'pCts.*.ma_phi'   => 'nullable|string|max:20',
        'pCts.*.ma_spct'  => 'nullable|string|max:20',
        'pCts.*.ma_lo'    => 'nullable|string|max:20',
        'pCts.*.ma_ku'    => 'nullable|string|max:20',
    ];

    protected $messages = [
        'pMa_Kh.required'       => 'Vui lòng chọn khách hàng',
        'pTk_Co.required'       => 'Vui lòng chọn tài khoản Có',
        'pNgay_Ct.required'     => 'Vui lòng chọn ngày chứng từ',
        'pNgay_Ct.date'         => 'Ngày chứng từ không hợp lệ',
        'pNgay_Lap.required'    => 'Vui lòng chọn ngày lập',
        'pNgay_Lap.date'        => 'Ngày lập không hợp lệ',
        'pMa_Nt.required'       => 'Vui lòng chọn mã ngoại tệ',
        'pMa_Nt.size'           => 'Mã ngoại tệ phải có 3 ký tự',
        'pTy_Gia.required'      => 'Vui lòng nhập tỷ giá',
        'pTy_Gia.numeric'       => 'Tỷ giá phải là số',
        'pTy_Gia.min'           => 'Tỷ giá phải lớn hơn hoặc bằng 0',
        'pCts.*.ma_tk.required' => 'Vui lòng chọn tài khoản Nợ',
        'pCts.*.ps_no.required' => 'Vui lòng nhập số tiền',
        'pCts.*.ps_no.numeric'  => 'Số tiền phải là số',
        'pCts.*.ps_no.min'      => 'Số tiền phải lớn hơn hoặc bằng 0',
        'pCts.*.ps_co.numeric'  => 'Số tiền phải là số',
        'pCts.*.ps_co.min'      => 'Số tiền phải lớn hơn hoặc bằng 0',
    ];

    public function mount(): void
    {
        $this->pNgay_Ct  = now()->toDateString();
        $this->pNgay_Lap = now()->toDateString();
        $this->pNguoi_Gd = \Auth::user()->name ?? '';
        $this->pCts      = collect();
        $this->pSoDuCts  = collect();
        $this->addRow();

        $this->pSo_Ct = AsGetSoCt::call([
            'pMa_ct'   => self::MA_CT,
            'pNgay_Ct' => $this->pNgay_Ct,
        ]);

        // Lấy mã ngoại tệ mặc định từ CatalogService
        $this->pMa_Nt = \CatalogService::ma_Nt() ?: self::DEFAULT_MA_NT;

        // Nếu có stt_rec từ bên ngoài truyền vào, load phiếu để sửa
        \Debugbar::info('Phieubaono mount - sttRec:', $this->sttRec);
        if (!empty($this->sttRec)) {
            $this->pStt_Rec = $this->sttRec;
            $this->loadPhieu();
        } elseif (!empty($this->pStt_Rec)) {
            $this->loadPhieu();
        }

        // Load danh sách tài khoản cho datalist
        $this->glDmTks = GlDmTk::select('tk', 'ten_tk')
            ->orderBy('tk')
            ->limit(100)
            ->get()
        ;
    }

    /**
     * Xử lý khi sttRec thay đổi từ bên ngoài.
     *
     * @param mixed $value
     */
    public function updatedSttRec($value): void
    {
        \Debugbar::info('updatedSttRec called with value:', $value);
        if (!empty($value)) {
            $this->pStt_Rec = $value;
            $this->loadPhieu();
        } else {
            // Reset form khi sttRec rỗng
            $this->resetForm();
        }
    }

    public function loadPhieu(): void
    {
        \Debugbar::info('loadPhieu called with pStt_Rec:', $this->pStt_Rec);
        if (empty($this->pStt_Rec)) {
            return;
        }

        // Load header từ database
        \Debugbar::info('Querying CaPh2 with stt_rec:', $this->pStt_Rec);
        $caPh2 = CaPh2::where('stt_rec', $this->pStt_Rec)->first();
        \Debugbar::info('CaPh2 result:', $caPh2);

        if ($caPh2) {
            $this->pMode     = 'edit';
            $this->pSo_Ct    = $caPh2->so_ct;
            $this->pNgay_Ct  = $caPh2->ngay_ct->toDateString();
            $this->pNgay_Lap = $caPh2->ngay_lct->toDateString();
            // Không load các trường tự động: pKht_Tain, pTrang_Thai, pPost2Gl
            // Chỉ load các trường cần thiết từ phiếu cũ
            $this->pMa_Kh     = $caPh2->ma_kh;
            $this->pDia_Chi   = $caPh2->dia_chi;
            $this->pNguoi_Gd  = $caPh2->nguoi_gd;
            $this->pDien_Giai = $caPh2->dien_giai;
            $this->pTk_Co     = $caPh2->tk_co;
            $this->pMa_Nt     = $caPh2->ma_nt;
            $this->pTy_Gia    = $caPh2->ty_gia;
            // Các trường pT_Thue, pT_TT, pT_Thue_Nt, pT_TT_Nt sẽ tự động tính toán lại

            // Load chi tiết
            $caCt2s = CaCt2::where('stt_rec', $this->pStt_Rec)
                ->orderBy('stt_rec0')
                ->get()
            ;

            $this->pCts     = collect();
            $this->pSoDuCts = collect();

            foreach ($caCt2s as $index => $caCt2) {
                $this->pCts->push([
                    'ma_tk'     => $caCt2->tk,
                    'dien_giai' => $caCt2->dien_giai,
                    'ps_no'     => $caCt2->ps_no,
                    'ps_co'     => $caCt2->ps_co,
                    'ps_no_nt'  => $caCt2->ps_no_nt,
                    'ps_co_nt'  => $caCt3->ps_co_nt,
                    'ma_kh'     => $caCt3->ma_kh,
                    'ma_hd'     => $caCt3->ma_hd,
                    'ma_bp'     => $caCt3->ma_bp,
                    'ma_phi'    => $caCt3->ma_phi,
                    'ma_spct'   => $caCt3->ma_spct,
                    'ma_lo'     => $caCt3->ma_lo,
                    'ma_ku'     => $caCt3->ma_ku,
                ]);

                // Khởi tạo số dư cho từng dòng (sẽ được tính sau)
                $this->pSoDuCts->put($index, null);
            }

            // Tính tổng
            $this->calculateTotal();
            $this->calculateForeignCurrency();

            // Update khách hàng info và số dư
            $this->updateKhachHang();
            $this->updateSoDu();
        }
    }

    public function updatedPMaKh($value): void
    {
        \Debugbar::info(__METHOD__, $value);
        $this->updateKhachHang($value);
    }

    public function updated($property): void
    {
        \Debugbar::info(__METHOD__, $property);

        if ('pDien_Giai' === $property) {
            // Auto-fill diễn giải mới vào tất cả các dòng chi tiết
            $this->pCts = $this->pCts->map(function ($row) {
                $row['dien_giai'] = $this->pDien_Giai;

                return $row;
            });
        }

        if ('pNgay_Ct' === $property) {
            // Khi thay đổi ngày chứng từ, cập nhật số dư
            $this->updateSoDu();
        }

        if ('pMa_Kh' === $property) {
            // Khi thay đổi mã KH, cập nhật số dư
            $this->updateSoDu();
        }

        if (str_contains($property, 'pCts')) {
            $this->calculateTotal();
            $this->calculateForeignCurrency();

            // Nếu thay đổi tài khoản trong chi tiết, cập nhật số dư
            if (str_contains($property, 'ma_tk')) {
                $this->updateSoDuChiTiet();
            }
        }

        if (\in_array($property, ['pTong_Ps_No', 'pTy_Gia'], true)) {
            $this->calculateForeignCurrency();
        }
    }

    public function calculateTotal(): void
    {
        $this->pTong_Ps_No = $this->pCts->sum(static fn ($item) => (float) ($item['ps_no'] ?? 0));

        // Tổng thanh toán = Tổng phát sinh nợ (vì không có thuế)
        $this->pT_TT   = $this->pTong_Ps_No;
        $this->pT_Thue = 0; // Luôn = 0
    }

    public function calculateForeignCurrency(): void
    {
        // Tính tiền ngoại tệ
        $this->pT_Tien_Nt = $this->pTong_Ps_No * $this->pTy_Gia;
        $this->pT_Thue_Nt = 0; // Luôn = 0
        $this->pT_TT_Nt   = $this->pT_Tien_Nt; // = tổng NT (vì không có thuế)

        // Cập nhật ps_no_nt và ps_co_nt cho từng dòng
        $this->pCts = $this->pCts->map(function ($row) {
            $row['ps_no_nt'] = ($row['ps_no'] ?? 0) * $this->pTy_Gia;
            $row['ps_co_nt'] = ($row['ps_co'] ?? 0) * $this->pTy_Gia;

            return $row;
        });
    }

    /**
     * Xử lý khi nhấn Enter trong ô số tiền
     * Tự động thêm dòng mới và focus vào dòng tiếp theo.
     *
     * @param mixed $index
     */
    public function handleEnter($index): void
    {
        // Nếu đang ở dòng cuối cùng, thêm dòng mới
        if ($index === $this->pCts->count() - 1) {
            $this->addRow();
        }
    }

    public function submit(): void
    {
        // Loại bỏ các dòng có số tiền = 0 trước khi validate
        $this->removeEmptyRows();

        $this->validate();

        if ($this->pTong_Ps_No <= 0) {
            $this->addError('pCts', 'Tổng phát sinh nợ phải lớn hơn 0');

            return;
        }

        // Generate So Ct if empty
        if (empty($this->pSo_Ct)) {
            $this->pSo_Ct = AsGetSoCt::call([
                'pMa_ct'   => self::MA_CT,
                'pNgay_Ct' => $this->pNgay_Ct,
            ]);
        }

        $maCty = \CatalogService::company()->id ?? '001';
        $user  = \CatalogService::simbaUser();
        \Debugbar::info(__METHOD__, $user);
        $lUser = $user->username ?? '';

        // Xác định mode: create hoặc edit
        $isEditMode = !empty($this->pStt_Rec) && 'edit' === $this->pMode;

        // Check số chứng từ
        $checkSoCt = AsChkSoCt::call([
            'pMa_Cty'  => $maCty,
            'pMa_Ct'   => 'CA4',
            'pStt_Rec' => $isEditMode ? $this->pStt_Rec : '0', // 0 cho tạo mới
            'pSo_ct'   => $this->pSo_Ct,
            'pNgay_ct' => $this->pNgay_Ct,
        ]);

        // Transaction to ensure atomicity
        \DB::transaction(function () use ($maCty, $lUser, $isEditMode): void {
            if ($isEditMode) {
                // MODE SỬA PHIẾU
                $stt_rec = $this->pStt_Rec;

                // 1. Process chứng từ để unlock (mode 2 = sửa/xóa)
                AsProcessCT::call([
                    'pMa_cty'  => $maCty,
                    'pMa_Ct'   => 'CA4',
                    'pStt_rec' => $stt_rec,
                    'pMode'    => '2',
                ]);

                // 2. Xóa chi tiết cũ
                $deleteDetailsResult = AsCADelCT2::call([
                    'pMa_cty'  => $maCty,
                    'pStt_rec' => $stt_rec,
                ]);

                // Lấy giá trị output parameter pRet
                $deleteDetailsRet = $deleteDetailsResult->first()->pRet ?? null;
                \Debugbar::info('AsCADelCT2 pRet:', $deleteDetailsRet);

                if (0 !== $deleteDetailsRet) {
                    throw new \Exception('Không thể xóa chi tiết cũ. Lỗi SQL: ' . $deleteDetailsRet);
                }

                // 3. Update Header bằng stored procedure
                $updateHeaderResult = AsCAUpdPH2::call([
                    'pMa_cty'     => $maCty,
                    'pStt_rec'    => $stt_rec,
                    'pMa_ct'      => 'CA4',
                    'pSo_ct'      => $this->pSo_Ct,
                    'pNgay_ct'    => $this->pNgay_Ct,
                    'pNgay_lct'   => $this->pNgay_Lap,
                    'pKht_tain'   => $this->pKht_Tain,
                    'pMa_kh'      => $this->pMa_Kh,
                    'pDia_chi'    => $this->pDia_Chi,
                    'pNguoi_gd'   => $this->pNguoi_Gd,
                    'pDien_giai'  => $this->pDien_Giai,
                    'pTk_co'      => $this->pTk_Co,
                    'pMa_nt'      => $this->pMa_Nt,
                    'pTy_gia'     => $this->pTy_Gia,
                    'pT_tien_nt'  => $this->pT_Tien_Nt,
                    'pT_tien'     => $this->pTong_Ps_No,
                    'pT_Thue'     => $this->pT_Thue,
                    'pT_Tt_nt'    => $this->pT_TT_Nt,
                    'pT_Tt'       => $this->pT_TT,
                    'pTrang_thai' => '', // Luôn rỗng theo SQL history
                    'pPost2gl'    => '', // Luôn rỗng theo SQL history
                    'pLUser'      => $lUser,
                ]);

                // Lấy giá trị output parameter pRet
                $updateHeaderRet = $updateHeaderResult->first()->pRet ?? null;
                \Debugbar::info('AsCAUpdPH2 pRet:', $updateHeaderRet);

                if (0 !== $updateHeaderRet) {
                    throw new \Exception('Không thể cập nhật phiếu báo nợ. Lỗi SQL: ' . $updateHeaderRet);
                }
            } else {
                // MODE TẠO MỚI
                // Lấy stt_rec từ stored procedure
                $stt_rec_result = AsGetSttRec::call([
                    'pMa_cty' => $maCty,
                    'pMa_Ct'  => 'CA4',
                ]);

                \Debugbar::info('AsGetSttRec raw result:', $stt_rec_result);
                \Debugbar::info('AsGetSttRec first():', $stt_rec_result->first());
                \Debugbar::info('AsGetSttRec pStt_rec:', $stt_rec_result->first()->pStt_rec ?? 'NULL');

                $stt_rec = $stt_rec_result->first()->pStt_rec ?? '';

                \Debugbar::info('Final stt_rec:', $stt_rec);

                if (empty($stt_rec)) {
                    throw new \Exception('Không thể lấy stt_rec từ AsGetSttRec. Vui lòng kiểm tra stored procedure.');
                }

                // 1. Insert Header bằng stored procedure
                $insertHeaderResult = AsCAInsPH2::call([
                    'pMa_cty'     => $maCty,
                    'pStt_rec'    => $stt_rec,
                    'pMa_ct'      => 'CA4',
                    'pSo_ct'      => $this->pSo_Ct,
                    'pNgay_ct'    => $this->pNgay_Ct,
                    'pNgay_lct'   => $this->pNgay_Lap,
                    'pKht_tain'   => $this->pKht_Tain,
                    'pMa_kh'      => $this->pMa_Kh,
                    'pDia_chi'    => $this->pDia_Chi,
                    'pNguoi_gd'   => $this->pNguoi_Gd,
                    'pDien_giai'  => $this->pDien_Giai,
                    'pTk_co'      => $this->pTk_Co,
                    'pMa_nt'      => $this->pMa_Nt,
                    'pTy_gia'     => $this->pTy_Gia,
                    'pT_tien_nt'  => $this->pT_Tien_Nt,
                    'pT_tien'     => $this->pTong_Ps_No,
                    'pT_Thue'     => $this->pT_Thue,
                    'pT_Tt_nt'    => $this->pT_TT_Nt,
                    'pT_Tt'       => $this->pT_TT,
                    'pTrang_thai' => '', // Luôn rỗng theo SQL history
                    'pPost2gl'    => '', // Luôn rỗng theo SQL history
                    'pLUser'      => $lUser,
                ]);

                // Lấy giá trị output parameter pRet
                $insertHeaderRet = (int) ($insertHeaderResult->first()->pRet ?? 0);
                \Debugbar::info('AsCAInsPH2 pRet:', $insertHeaderRet);

                if (0 !== $insertHeaderRet) {
                    throw new \Exception('Không thể lưu phiếu báo nợ. Lỗi SQL: ' . $insertHeaderRet);
                }
            }

            // 4. Insert Details bằng stored procedure (chung cho cả create và edit)
            foreach ($this->pCts as $index => $row) {
                if (empty($row['ma_tk']) || empty($row['ps_no'])) {
                    continue;
                }

                $stt_rec0 = str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);

                $insertDetailResult = AsCAInsCT2::call([
                    'pMa_cty'    => $maCty,
                    'pStt_rec'   => $stt_rec,
                    'pStt_rec0'  => $stt_rec0,
                    'pTk'        => $row['ma_tk'],
                    'pPs_no'     => $row['ps_no'] ?? 0,
                    'pPs_co'     => $row['ps_co'] ?? 0,
                    'pPs_no_nt'  => $row['ps_no_nt'] ?? 0,
                    'pPs_co_nt'  => $row['ps_co_nt'] ?? 0,
                    'pDien_giai' => $row['dien_giai'] ?? $this->pDien_Giai,
                    'pMa_kh'     => $row['ma_kh'] ?? $this->pMa_Kh,
                    'pMa_ku'     => $row['ma_ku'] ?? '',
                    'pMa_lo'     => $row['ma_lo'] ?? '',
                    'pMa_bp'     => $row['ma_bp'] ?? '',
                    'pMa_hd'     => $row['ma_hd'] ?? '',
                    'pMa_phi'    => $row['ma_phi'] ?? '',
                    'pMa_spct'   => $row['ma_spct'] ?? '',
                ]);

                // Lấy giá trị output parameter pRet
                $insertDetailRet = $insertDetailResult->first()->pRet ?? null;
                \Debugbar::info('AsCAInsCT2 pRet (dòng ' . ($index + 1) . '):', $insertDetailRet);

                if (0 !== $insertDetailRet) {
                    throw new \Exception('Không thể lưu chi tiết dòng ' . ($index + 1) . '. Lỗi SQL: ' . $insertDetailRet);
                }
            }

            // 5. Process chứng từ (mode 1 = tạo mới/xác nhận sửa)
            AsProcessCT::call([
                'pMa_cty'  => $maCty,
                'pMa_Ct'   => 'CA4',
                'pStt_rec' => $stt_rec,
                'pMode'    => '1',
            ]);

            // 6. Post to GL
            AsPostCaPh2_glct::call([
                'pMa_cty'  => $maCty,
                'pStt_rec' => $stt_rec,
            ]);
        });

        $message = $isEditMode
            ? 'Cập nhật phiếu báo nợ thành công.'
            : 'Lưu phiếu báo nợ thành công.';
        session()->flash('message', $message);

        // Reset form
        $this->reset([
            'pDien_Giai', 'pCts', 'pSo_Ct', 'pTong_Ps_No',
            'pDia_Chi', 'pNguoi_Gd', 'pSoDu', 'pSoDuCts',
        ]);
        // Các trường pT_Thue, pT_TT, pKht_Tain, pTrang_Thai, pPost2Gl sẽ tự động set giá trị mặc định

        // Nếu đang ở chế độ tạo mới, reset hoàn toàn
        if ('create' === $this->pMode) {
            // Giữ nguyên pMa_Nt = 'VND' và pTy_Gia = 1
            // Các trường ngoại tệ sẽ tự động tính toán lại
            $this->mount();
        } else {
            // Nếu đang sửa, chỉ reset một phần và giữ stt_rec
            $this->pCts     = collect();
            $this->pSoDuCts = collect();
            $this->addRow();
            $this->calculateTotal();
            $this->calculateForeignCurrency();
        }
    }

    public function updateKhachHang($ma_kh = null): void
    {
        // Nếu có ma_kh từ parameter, cập nhật pMa_Kh
        if ($ma_kh) {
            $this->pMa_Kh = $ma_kh;
        }

        \Debugbar::info('Phieubaono/updateKhachHang/pMa_Kh:', $this->pMa_Kh);

        $this->pKh = ArDmKh::where('ma_kh', $this->pMa_Kh)->first();
        if ($this->pKh) {
            $this->pDien_Giai = 'Thanh toán nhà chung cấp ' . $this->pKh->ten_kh;
            $this->pDia_Chi   = $this->pKh->dia_chi ?? '';
            $this->pNguoi_Gd  = $this->pKh->nguoi_gd ?? '';

            // Auto-fill mã khách hàng và diễn giải vào các dòng chi tiết
            $this->pCts = $this->pCts->map(function ($row) {
                $row['ma_kh']     = $this->pMa_Kh;
                $row['dien_giai'] = $this->pDien_Giai;

                return $row;
            });

            // Lấy số dư khách hàng nếu có đủ thông tin
            $this->updateSoDu();
        }
    }

    public function updateSoDu(): void
    {
        if (empty($this->pMa_Kh) || empty($this->pNgay_Ct)) {
            $this->pSoDu = 0;

            return;
        }

        try {
            // Lấy số dư tổng - lấy số dư của tài khoản đầu tiên trong chi tiết
            $tk = null;
            foreach ($this->pCts as $row) {
                if (!empty($row['ma_tk'])) {
                    $tk = $row['ma_tk'];

                    break;
                }
            }

            if ($tk) {
                $soDuResult = AsGetSoDuKh::call([
                    'pMa_Cty' => \CatalogService::company()->id ?? '001',
                    'pMa_kh'  => $this->pMa_Kh,
                    'pTk'     => $tk,
                    'pNgay'   => $this->pNgay_Ct,
                ]);

                // SP class đã được sửa để luôn trả về float
                $this->pSoDu = is_numeric($soDuResult) ? (float) $soDuResult : 0;
            } else {
                $this->pSoDu = 0;
            }
        } catch (\Exception $e) {
            $this->pSoDu = 0;
            \Debugbar::error('Lỗi khi lấy số dư: ' . $e->getMessage());
        }

        // Cập nhật số dư cho từng dòng
        $this->updateSoDuTungDong();
    }

    public function updateSoDuChiTiet(): void
    {
        // Cập nhật số dư khi thay đổi tài khoản trong chi tiết
        $this->updateSoDu();
        $this->updateSoDuTungDong();
    }

    public function updateSoDuTungDong(): void
    {
        // Tính số dư cho từng dòng chi tiết
        $this->pSoDuCts = collect();

        foreach ($this->pCts as $index => $row) {
            $soDuValue = 0;

            if (!empty($row['ma_tk']) && !empty($this->pMa_Kh) && !empty($this->pNgay_Ct)) {
                try {
                    $soDuResult = AsGetSoDuKh::call([
                        'pMa_Cty' => \CatalogService::company()->id ?? '001',
                        'pMa_kh'  => $this->pMa_Kh,
                        'pTk'     => $row['ma_tk'],
                        'pNgay'   => $this->pNgay_Ct,
                    ]);

                    // SP class đã được sửa để luôn trả về float
                    $soDuValue = is_numeric($soDuResult) ? (float) $soDuResult : 0;
                } catch (\Exception $e) {
                    $soDuValue = 0;
                    \Debugbar::error('Lỗi khi lấy số dư dòng ' . $index . ': ' . $e->getMessage());
                }
            }

            $this->pSoDuCts->put($index, $soDuValue);
        }
    }

    public function updateCt(): void
    {
        // This seems unused or redundant if we use valid Livewire binding
    }

    public function addRow(): void
    {
        $newIndex = $this->pCts->count();
        $this->pCts->push([
            'ma_tk'     => '',
            'dien_giai' => $this->pDien_Giai,
            'ps_no'     => 0,
            'ps_co'     => 0,
            'ps_no_nt'  => 0,
            'ps_co_nt'  => 0,
            'ma_kh'     => $this->pMa_Kh ?? '',
            'ma_hd'     => '',
            'ma_bp'     => '',
            'ma_phi'    => '',
            'ma_spct'   => '',
            'ma_lo'     => '',
            'ma_ku'     => '',
        ]);

        // Thêm entry số dư cho dòng mới
        $this->pSoDuCts->put($newIndex, null);
    }

    public function removeRow($index): void
    {
        $this->pCts->forget($index);
        $this->pCts = $this->pCts->values(); // Re-index

        $this->pSoDuCts->forget($index);
        $this->pSoDuCts = $this->pSoDuCts->values()->mapWithKeys(static fn ($value, $newIndex) => [$newIndex => $value]);

        if ($this->pCts->isEmpty()) {
            $this->addRow();
        }

        $this->calculateTotal();
    }

    /**
     * Xóa phiếu báo nợ.
     */
    public function deletePhieu(): void
    {
        if (empty($this->pStt_Rec)) {
            $this->dispatch('action-message', ['on' => 'delete-error', 'message' => 'Không có phiếu nào được chọn để xóa']);

            return;
        }

        $maCty = \CatalogService::company()->id ?? '001';
        $lUser = \Auth::user()->name ?? '';

        try {
            \DB::transaction(function () use ($maCty): void {
                // 1. Process chứng từ để unlock (mode 2 = sửa/xóa)
                AsProcessCT::call([
                    'pMa_cty'  => $maCty,
                    'pMa_Ct'   => 'CA4',
                    'pStt_rec' => $this->pStt_Rec,
                    'pMode'    => '2',
                ]);

                // 2. Xóa chi tiết
                $deleteDetailsResult = AsCADelCT2::call([
                    'pMa_cty'  => $maCty,
                    'pStt_rec' => $this->pStt_Rec,
                ]);

                // Lấy giá trị output parameter pRet
                $deleteDetailsRet = $deleteDetailsResult->first()->pRet ?? null;
                \Debugbar::info('AsCADelCT2 pRet (delete):', $deleteDetailsRet);

                if (0 !== $deleteDetailsRet) {
                    throw new \Exception('Không thể xóa chi tiết phiếu báo nợ. Lỗi SQL: ' . $deleteDetailsRet);
                }

                // 3. Xóa header (dùng AsCADelPH2 nếu có, hoặc xóa trực tiếp)
                // Lưu ý: Cần stored procedure AsCADelPH2 để xóa header
                // Nếu chưa có, cần tạo hoặc dùng cách khác
            });

            // Reset form sau khi xóa thành công
            $this->resetForm();

            $this->dispatch('action-message', ['on' => 'phieu-deleted']);
        } catch (\Exception $e) {
            $this->dispatch('action-message', ['on' => 'delete-error', 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Reset form về trạng thái mặc định.
     */
    public function resetForm(): void
    {
        $this->pStt_Rec   = '';
        $this->pMode      = 'create';
        $this->pMa_Kh     = '';
        $this->pKh        = '';
        $this->pDien_Giai = '';
        $this->pTk_Co     = self::DEFAULT_TK_CO;
        $this->pNgay_Ct   = now()->toDateString();
        $this->pSo_Ct     = AsGetSoCt::call([
            'pMa_ct'   => self::MA_CT,
            'pNgay_Ct' => $this->pNgay_Ct,
        ]);
        $this->pNgay_Lap   = now()->toDateString();
        $this->pDia_Chi    = '';
        $this->pNguoi_Gd   = \Auth::user()->name ?? '';
        $this->pMa_Nt      = \CatalogService::ma_Nt() ?: self::DEFAULT_MA_NT;
        $this->pTy_Gia     = self::DEFAULT_TY_GIA;
        $this->pT_Tien_Nt  = 0;
        $this->pT_Thue     = 0;
        $this->pT_TT       = 0;
        $this->pT_Thue_Nt  = 0;
        $this->pT_TT_Nt    = 0;
        $this->pKht_Tain   = '0';
        $this->pTrang_Thai = '';
        $this->pPost2Gl    = '';
        $this->pCts        = collect();
        $this->pSoDu       = 0;
        $this->pTong_Ps_No = 0;
        $this->pSoDuCts    = collect();
        $this->addRow();
    }

    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/cash/nganhang/baono/phieubaono.blade.php
        return view('catalog::cash.nganhang.baono.phieubaono', [
            'arDmKhs' => ArDmKh::all(),
        ]);
    }

    /**
     * Loại bỏ các dòng có số tiền = 0 trước khi lưu.
     */
    protected function removeEmptyRows(): void
    {
        $this->pCts = $this->pCts->filter(static function ($row) {
            $ps_no = (float) ($row['ps_no'] ?? 0);

            return $ps_no > 0;
        })->values();

        // Nếu không còn dòng nào, thêm 1 dòng trống
        if ($this->pCts->isEmpty()) {
            $this->addRow();
        }

        // Tính lại tổng
        $this->calculateTotal();
        $this->calculateForeignCurrency();
    }
}
