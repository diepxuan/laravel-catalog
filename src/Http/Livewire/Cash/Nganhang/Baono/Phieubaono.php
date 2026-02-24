<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-16 14:21:40
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;

use Diepxuan\Catalog\Models\ArDmKh;
use Diepxuan\Simba\Models\GlDmTk;
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
use Diepxuan\Simba\StoredProcedures\AsGetSttRec;
use Diepxuan\Simba\StoredProcedures\AsChkSoCt;
use Diepxuan\Simba\StoredProcedures\AsProcessCt;
use Diepxuan\Simba\StoredProcedures\AsCAInsPH2;
use Diepxuan\Simba\StoredProcedures\AsCAUpdPH2;
use Diepxuan\Simba\StoredProcedures\AsCAInsCT2;
use Diepxuan\Simba\StoredProcedures\AsCADelCT2;
use Diepxuan\Simba\StoredProcedures\AsGetSoDuKh;
use Diepxuan\Simba\StoredProcedures\AsPostCaph3_glct;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Phieubaono extends Component
{
    public $pMa_Kh;
    public $pKh;
    public $pDien_Giai;
    public $pTk_Co = 11217;
    public $pNgay_Ct;
    public $pSo_Ct;
    public $pNgay_Lap;
    public $pDia_Chi = '';
    public $pNguoi_Gd = '';
    public $pMa_Nt = 'VND';
    public $pTy_Gia = 1;
    public $pT_Tien_Nt = 0;
    public $pT_Thue = 0; // Tự động = 0
    public $pT_TT = 0; // Tự động = tổng cộng
    public $pT_Thue_Nt = 0; // Tự động = 0
    public $pT_TT_Nt = 0; // Tự động = tổng NT
    public $pKht_Tain = '0'; // Tự động = 0
    public $pTrang_Thai = ''; // Tự động = '' (theo SQL history)
    public $pPost2Gl = ''; // Tự động = '' (theo SQL history)
    public Collection $pCts;
    public $pSoDu;
    public $pTong_Ps_No = 0;
    
    // Properties cho sửa phiếu
    public $pStt_Rec = ''; // stt_rec của phiếu cần sửa (nếu có)
    public $pMode = 'create'; // 'create' hoặc 'edit'
    
    // Số dư cho từng dòng chi tiết
    public Collection $pSoDuCts;
    
    // Danh sách tài khoản cho datalist
    public $glDmTks = [];

    protected $rules = [
        'pMa_Kh'       => 'required',
        'pTk_Co'       => 'required',
        'pNgay_Ct'     => 'required|date',
        'pNgay_Lap'    => 'required|date',
        'pDia_Chi'     => 'nullable|string|max:255',
        'pNguoi_Gd'    => 'nullable|string|max:30',
        'pMa_Nt'       => 'required|string|size:3',
        'pTy_Gia'      => 'required|numeric|min:0',
        'pCts.*.ma_tk' => 'required',
        'pCts.*.ps_no' => 'required|numeric|min:0',
        'pCts.*.ps_co' => 'nullable|numeric|min:0',
        'pCts.*.ps_no_nt' => 'nullable|numeric|min:0',
        'pCts.*.ps_co_nt' => 'nullable|numeric|min:0',
        'pCts.*.ma_kh' => 'nullable|string|max:20',
        'pCts.*.ma_hd' => 'nullable|string|max:20',
        'pCts.*.ma_bp' => 'nullable|string|max:20',
        'pCts.*.ma_phi' => 'nullable|string|max:20',
        'pCts.*.ma_spct' => 'nullable|string|max:20',
        'pCts.*.ma_lo' => 'nullable|string|max:20',
        'pCts.*.ma_ku' => 'nullable|string|max:20',
    ];

    public function mount(): void
    {
        $this->pNgay_Ct  = now()->toDateString();
        $this->pNgay_Lap = now()->toDateString();
        $this->pNguoi_Gd = \Auth::user()->name ?? '';
        $this->pCts      = collect();
        $this->pSoDuCts  = collect();
        $this->addRow();
        
        $this->pSo_Ct    = AsGetSoCt::call([
            'pMa_ct'   => 'CA4',
            'pNgay_Ct' => $this->pNgay_Ct,
        ]);
        
        // Lấy mã ngoại tệ mặc định từ CatalogService
        $this->pMa_Nt = \CatalogService::ma_Nt();
        
        // Nếu có stt_rec, load phiếu để sửa
        if (!empty($this->pStt_Rec)) {
            $this->loadPhieu();
        }
        
        // Load danh sách tài khoản cho datalist
        $this->glDmTks = \Diepxuan\Simba\Models\GlDmTk::select('tk', 'ten_tk')
            ->orderBy('tk')
            ->limit(100)
            ->get();
        
        \Debugbar::info('mount');
    }
    
    public function loadPhieu(): void
    {
        if (empty($this->pStt_Rec)) {
            return;
        }
        
        // Load header từ database
        $caPh3 = \Diepxuan\Catalog\Models\CaPh3::where('stt_rec', $this->pStt_Rec)->first();
        
        if ($caPh3) {
            $this->pMode = 'edit';
            $this->pSo_Ct = $caPh3->so_ct;
            $this->pNgay_Ct = $caPh3->ngay_ct->toDateString();
            $this->pNgay_Lap = $caPh3->ngay_lct->toDateString();
            // Không load các trường tự động: pKht_Tain, pTrang_Thai, pPost2Gl
            // Chỉ load các trường cần thiết từ phiếu cũ
            $this->pMa_Kh = $caPh3->ma_kh;
            $this->pDia_Chi = $caPh3->dia_chi;
            $this->pNguoi_Gd = $caPh3->nguoi_gd;
            $this->pDien_Giai = $caPh3->dien_giai;
            $this->pTk_Co = $caPh3->tk_co;
            $this->pMa_Nt = $caPh3->ma_nt;
            $this->pTy_Gia = $caPh3->ty_gia;
            // Các trường pT_Thue, pT_TT, pT_Thue_Nt, pT_TT_Nt sẽ tự động tính toán lại
            
            // Load chi tiết
            $caCt3s = \Diepxuan\Catalog\Models\CaCt3::where('stt_rec', $this->pStt_Rec)
                ->orderBy('stt_rec0')
                ->get();
            
            $this->pCts = collect();
            $this->pSoDuCts = collect();
            
            foreach ($caCt3s as $index => $caCt3) {
                $this->pCts->push([
                    'ma_tk'      => $caCt3->tk,
                    'dien_giai'  => $caCt3->dien_giai,
                    'ps_no'      => $caCt3->ps_no,
                    'ps_co'      => $caCt3->ps_co,
                    'ps_no_nt'   => $caCt3->ps_no_nt,
                    'ps_co_nt'   => $caCt3->ps_co_nt,
                    'ma_kh'      => $caCt3->ma_kh,
                    'ma_hd'      => $caCt3->ma_hd,
                    'ma_bp'      => $caCt3->ma_bp,
                    'ma_phi'     => $caCt3->ma_phi,
                    'ma_spct'    => $caCt3->ma_spct,
                    'ma_lo'      => $caCt3->ma_lo,
                    'ma_ku'      => $caCt3->ma_ku,
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

    public function updated($property): void
    {
        \Debugbar::info('=== updated called ===');
        \Debugbar::info('Property changed: ' . $property);
        
        // Log chi tiết cho pCts
        if (str_contains($property, 'pCts')) {
            $parts = explode('.', $property);
            if (count($parts) >= 3) {
                $index = $parts[1];
                $field = $parts[2];
                $value = $this->pCts[$index][$field] ?? null;
                \Debugbar::info('Chi tiết thay đổi - dòng ' . $index . ', field "' . $field . '": ' . $value);
                
                // Log toàn bộ dòng để debug
                \Debugbar::info('Toàn bộ dòng ' . $index . ':', $this->pCts[$index] ?? []);
            }
        }

        if ($property == 'pMa_Kh') {
            $this->updateKhachHang();
        }

        if ($property == 'pDien_Giai') {
            // Auto-fill diễn giải mới vào tất cả các dòng chi tiết
            $this->pCts = $this->pCts->map(function ($row) {
                $row['dien_giai'] = $this->pDien_Giai;
                return $row;
            });
        }
        
        if ($property == 'pNgay_Ct') {
            // Khi thay đổi ngày chứng từ, cập nhật số dư
            $this->updateSoDu();
        }
        
        if ($property == 'pMa_Kh') {
            // Khi thay đổi mã KH, cập nhật số dư
            $this->updateSoDu();
        }

        if (str_contains($property, 'pCts')) {
            $this->calculateTotal();
            $this->calculateForeignCurrency();
            
            // Nếu thay đổi tài khoản trong chi tiết, cập nhật số dư
            if (str_contains($property, 'ma_tk')) {
                \Debugbar::info('Tài khoản thay đổi, gọi updateSoDuChiTiet');
                $this->updateSoDuChiTiet();
            }
        }

        if (in_array($property, ['pTong_Ps_No', 'pTy_Gia'])) {
            $this->calculateForeignCurrency();
        }
        
        \Debugbar::info('=== updated completed ===');
    }

    public function calculateTotal(): void
    {
        $this->pTong_Ps_No = $this->pCts->sum(function ($item) {
            return (float) ($item['ps_no'] ?? 0);
        });
        
        // Tổng thanh toán = Tổng phát sinh nợ (vì không có thuế)
        $this->pT_TT = $this->pTong_Ps_No;
        $this->pT_Thue = 0; // Luôn = 0
    }

    public function calculateForeignCurrency(): void
    {
        // Tính tiền ngoại tệ
        $this->pT_Tien_Nt = $this->pTong_Ps_No * $this->pTy_Gia;
        $this->pT_Thue_Nt = 0; // Luôn = 0
        $this->pT_TT_Nt = $this->pT_Tien_Nt; // = tổng NT (vì không có thuế)
        
        // Cập nhật ps_no_nt và ps_co_nt cho từng dòng
        $this->pCts = $this->pCts->map(function ($row) {
            $row['ps_no_nt'] = ($row['ps_no'] ?? 0) * $this->pTy_Gia;
            $row['ps_co_nt'] = ($row['ps_co'] ?? 0) * $this->pTy_Gia;
            return $row;
        });
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->pTong_Ps_No <= 0) {
            $this->addError('pCts', 'Tổng phát sinh nợ phải lớn hơn 0');
            return;
        }

        // Generate So Ct if empty
        if (empty($this->pSo_Ct)) {
             $this->pSo_Ct = AsGetSoCt::call([
                'pMa_ct'   => 'CA4',
                'pNgay_Ct' => $this->pNgay_Ct,
            ]);
        }

        $maCty = \CatalogService::company()->id ?? '001';
        $lUser = \Auth::user()->name ?? '';
        
        // Xác định mode: create hoặc edit
        $isEditMode = !empty($this->pStt_Rec) && $this->pMode === 'edit';
        
        // Check số chứng từ
        $checkSoCt = AsChkSoCt::call([
            'pMa_Cty' => $maCty,
            'pMa_Ct' => 'CA4',
            'pStt_Rec' => $isEditMode ? $this->pStt_Rec : '0', // 0 cho tạo mới
            'pSo_ct' => $this->pSo_Ct,
            'pNgay_ct' => $this->pNgay_Ct,
        ]);

        if ($checkSoCt !== 0) {
            $this->addError('pSo_Ct', 'Số chứng từ đã tồn tại hoặc không hợp lệ');
            return;
        }

        // Transaction to ensure atomicity
        \DB::transaction(function () use ($maCty, $lUser, $isEditMode) {
            if ($isEditMode) {
                // MODE SỬA PHIẾU
                $stt_rec = $this->pStt_Rec;
                
                // 1. Process chứng từ để unlock (mode 2 = sửa/xóa)
                AsProcessCt::call([
                    'pMa_cty' => $maCty,
                    'pMa_Ct' => 'CA4',
                    'pStt_rec' => $stt_rec,
                    'pMode' => '2',
                ]);
                
                // 2. Xóa chi tiết cũ
                $deleteDetails = AsCADelCT2::call([
                    'pMa_cty' => $maCty,
                    'pStt_rec' => $stt_rec,
                ]);
                
                if ($deleteDetails !== 0) {
                    throw new \Exception('Lỗi khi xóa chi tiết cũ');
                }
                
                // 3. Update Header bằng stored procedure
                $updateHeader = AsCAUpdPH2::call([
                    'pMa_cty' => $maCty,
                    'pStt_rec' => $stt_rec,
                    'pMa_ct' => 'CA4',
                    'pSo_ct' => $this->pSo_Ct,
                    'pNgay_ct' => $this->pNgay_Ct,
                    'pNgay_lct' => $this->pNgay_Lap,
                    'pKht_tain' => $this->pKht_Tain,
                    'pMa_kh' => $this->pMa_Kh,
                    'pDia_chi' => $this->pDia_Chi,
                    'pNguoi_gd' => $this->pNguoi_Gd,
                    'pDien_giai' => $this->pDien_Giai,
                    'pTk_co' => $this->pTk_Co,
                    'pMa_nt' => $this->pMa_Nt,
                    'pTy_gia' => $this->pTy_Gia,
                    'pT_tien_nt' => $this->pT_Tien_Nt,
                    'pT_tien' => $this->pTong_Ps_No,
                    'pT_Thue' => $this->pT_Thue,
                    'pT_Tt_nt' => $this->pT_TT_Nt,
                    'pT_Tt' => $this->pT_TT,
                    'pTrang_thai' => '', // Luôn rỗng theo SQL history
                    'pPost2gl' => '', // Luôn rỗng theo SQL history
                    'pLUser' => $lUser,
                ]);

                if ($updateHeader !== 0) {
                    throw new \Exception('Lỗi khi cập nhật header phiếu báo nợ');
                }
                
            } else {
                // MODE TẠO MỚI
                // Lấy stt_rec từ stored procedure
                $stt_rec_result = AsGetSttRec::call([
                    'pMa_cty' => $maCty,
                    'pMa_Ct' => 'CA4',
                ]);
                
                $stt_rec = $stt_rec_result; // Kết quả từ stored procedure
                
                // 1. Insert Header bằng stored procedure
                $insertHeader = AsCAInsPH2::call([
                    'pMa_cty' => $maCty,
                    'pStt_rec' => $stt_rec,
                    'pMa_ct' => 'CA4',
                    'pSo_ct' => $this->pSo_Ct,
                    'pNgay_ct' => $this->pNgay_Ct,
                    'pNgay_lct' => $this->pNgay_Lap,
                    'pKht_tain' => $this->pKht_Tain,
                    'pMa_kh' => $this->pMa_Kh,
                    'pDia_chi' => $this->pDia_Chi,
                    'pNguoi_gd' => $this->pNguoi_Gd,
                    'pDien_giai' => $this->pDien_Giai,
                    'pTk_co' => $this->pTk_Co,
                    'pMa_nt' => $this->pMa_Nt,
                    'pTy_gia' => $this->pTy_Gia,
                    'pT_tien_nt' => $this->pT_Tien_Nt,
                    'pT_tien' => $this->pTong_Ps_No,
                    'pT_Thue' => $this->pT_Thue,
                    'pT_Tt_nt' => $this->pT_TT_Nt,
                    'pT_Tt' => $this->pT_TT,
                    'pTrang_thai' => '', // Luôn rỗng theo SQL history
                    'pPost2gl' => '', // Luôn rỗng theo SQL history
                    'pLUser' => $lUser,
                ]);

                if ($insertHeader !== 0) {
                    throw new \Exception('Lỗi khi lưu header phiếu báo nợ');
                }
            }
            
            // 4. Insert Details bằng stored procedure (chung cho cả create và edit)
            foreach ($this->pCts as $index => $row) {
                if (empty($row['ma_tk']) || empty($row['ps_no'])) continue;

                $stt_rec0 = str_pad((string)($index + 1), 3, '0', STR_PAD_LEFT);
                
                $insertDetail = AsCAInsCT2::call([
                    'pMa_cty' => $maCty,
                    'pStt_rec' => $stt_rec,
                    'pStt_rec0' => $stt_rec0,
                    'pTk' => $row['ma_tk'],
                    'pPs_no' => $row['ps_no'] ?? 0,
                    'pPs_co' => $row['ps_co'] ?? 0,
                    'pPs_no_nt' => $row['ps_no_nt'] ?? 0,
                    'pPs_co_nt' => $row['ps_co_nt'] ?? 0,
                    'pDien_giai' => $row['dien_giai'] ?? $this->pDien_Giai,
                    'pMa_kh' => $row['ma_kh'] ?? $this->pMa_Kh,
                    'pMa_ku' => $row['ma_ku'] ?? '',
                    'pMa_lo' => $row['ma_lo'] ?? '',
                    'pMa_bp' => $row['ma_bp'] ?? '',
                    'pMa_hd' => $row['ma_hd'] ?? '',
                    'pMa_phi' => $row['ma_phi'] ?? '',
                    'pMa_spct' => $row['ma_spct'] ?? '',
                ]);

                if ($insertDetail !== 0) {
                    throw new \Exception('Lỗi khi lưu chi tiết dòng ' . ($index + 1));
                }
            }

            // 5. Process chứng từ (mode 1 = tạo mới/xác nhận sửa)
            AsProcessCt::call([
                'pMa_cty' => $maCty,
                'pMa_Ct' => 'CA4',
                'pStt_rec' => $stt_rec,
                'pMode' => '1',
            ]);

            // 6. Post to GL
            AsPostCaph3_glct::call([
                'pMa_cty' => $maCty,
                'pStt_rec' => $stt_rec,
            ]);
        });

        $message = $isEditMode 
            ? 'Phiếu báo nợ đã được cập nhật thành công.' 
            : 'Phiếu báo nợ đã được lưu thành công.';
        session()->flash('message', $message);
        
        // Reset form
        $this->reset([
            'pDien_Giai', 'pCts', 'pSo_Ct', 'pTong_Ps_No',
            'pDia_Chi', 'pNguoi_Gd', 'pSoDu', 'pSoDuCts'
        ]);
        // Các trường pT_Thue, pT_TT, pKht_Tain, pTrang_Thai, pPost2Gl sẽ tự động set giá trị mặc định
        
        // Nếu đang ở chế độ tạo mới, reset hoàn toàn
        if ($this->pMode === 'create') {
            // Giữ nguyên pMa_Nt = 'VND' và pTy_Gia = 1
            // Các trường ngoại tệ sẽ tự động tính toán lại
            $this->mount();
        } else {
            // Nếu đang sửa, chỉ reset một phần và giữ stt_rec
            $this->pCts = collect();
            $this->pSoDuCts = collect();
            $this->addRow();
            $this->calculateTotal();
            $this->calculateForeignCurrency();
        }
    }

    public function updateKhachHang(): void
    {
        $this->pKh = ArDmKh::where('ma_kh', $this->pMa_Kh)->first();
        if ($this->pKh) {
            $this->pDien_Giai = 'Thu tiền khách hàng ' . $this->pKh->ten_kh;
            $this->pDia_Chi = $this->pKh->dia_chi ?? '';
            $this->pNguoi_Gd = $this->pKh->nguoi_gd ?? '';
            
            // Auto-fill mã khách hàng và diễn giải vào các dòng chi tiết
            $this->pCts = $this->pCts->map(function ($row) {
                $row['ma_kh'] = $this->pMa_Kh;
                $row['dien_giai'] = $this->pDien_Giai;
                return $row;
            });
            
            // Lấy số dư khách hàng nếu có đủ thông tin
            $this->updateSoDu();
        }
        \Debugbar::info('updateKhachHang');
    }
    
    public function updateSoDu(): void
    {
        if (empty($this->pMa_Kh) || empty($this->pNgay_Ct)) {
            $this->pSoDu = null;
            return;
        }
        
        try {
            // Lấy số dư tổng (có thể lấy số dư của tài khoản đầu tiên hoặc tổng số dư)
            // Hiện tại lấy số dư của tài khoản đầu tiên trong chi tiết
            $tk = null;
            foreach ($this->pCts as $row) {
                if (!empty($row['ma_tk'])) {
                    $tk = $row['ma_tk'];
                    break;
                }
            }
            
            if ($tk) {
                \Debugbar::info('Gọi AsGetSoDuKh với params:', [
                    'pMa_Cty' => \CatalogService::company()->id ?? '001',
                    'pMa_kh' => $this->pMa_Kh,
                    'pTk' => $tk,
                    'pNgay' => $this->pNgay_Ct,
                ]);
                
                $soDuResult = AsGetSoDuKh::call([
                    'pMa_Cty' => \CatalogService::company()->id ?? '001',
                    'pMa_kh' => $this->pMa_Kh,
                    'pTk' => $tk,
                    'pNgay' => $this->pNgay_Ct,
                ]);
                
                \Debugbar::info('Kết quả AsGetSoDuKh (raw):', $soDuResult);
                \Debugbar::info('Kiểu dữ liệu kết quả: ' . gettype($soDuResult));
                
                if (is_object($soDuResult)) {
                    \Debugbar::info('Class của kết quả: ' . get_class($soDuResult));
                }
                
                // Kiểm tra kết quả trả về
                // Stored procedure có thể trả về Collection, array hoặc giá trị đơn
                if (is_numeric($soDuResult)) {
                    $this->pSoDu = (float) $soDuResult;
                    \Debugbar::info('Kết quả là số: ' . $this->pSoDu);
                } elseif ($soDuResult instanceof \Illuminate\Support\Collection) {
                    \Debugbar::info('Kết quả là Collection, count: ' . $soDuResult->count());
                    \Debugbar::info('Collection content:', $soDuResult->toArray());
                    
                    if ($soDuResult->isNotEmpty()) {
                        $firstItem = $soDuResult->first();
                        \Debugbar::info('First item:', $firstItem);
                        
                        if (is_object($firstItem) && property_exists($firstItem, 'so_du')) {
                            $this->pSoDu = (float) $firstItem->so_du;
                            \Debugbar::info('Lấy so_du từ object: ' . $this->pSoDu);
                        } elseif (is_array($firstItem) && isset($firstItem['so_du'])) {
                            $this->pSoDu = (float) $firstItem['so_du'];
                            \Debugbar::info('Lấy so_du từ array: ' . $this->pSoDu);
                        } else {
                            $this->pSoDu = null;
                            \Debugbar::info('Không tìm thấy so_du trong first item');
                        }
                    } else {
                        $this->pSoDu = null;
                        \Debugbar::info('Collection rỗng');
                    }
                } elseif (is_array($soDuResult)) {
                    \Debugbar::info('Kết quả là array, count: ' . count($soDuResult));
                    \Debugbar::info('Array content:', $soDuResult);
                    
                    if (!empty($soDuResult)) {
                        $firstItem = $soDuResult[0];
                        \Debugbar::info('First item:', $firstItem);
                        
                        if (is_object($firstItem) && property_exists($firstItem, 'so_du')) {
                            $this->pSoDu = (float) $firstItem->so_du;
                            \Debugbar::info('Lấy so_du từ object: ' . $this->pSoDu);
                        } elseif (is_array($firstItem) && isset($firstItem['so_du'])) {
                            $this->pSoDu = (float) $firstItem['so_du'];
                            \Debugbar::info('Lấy so_du từ array: ' . $this->pSoDu);
                        } else {
                            $this->pSoDu = null;
                            \Debugbar::info('Không tìm thấy so_du trong first item');
                        }
                    } else {
                        $this->pSoDu = null;
                        \Debugbar::info('Array rỗng');
                    }
                } else {
                    $this->pSoDu = null;
                    \Debugbar::info('Kết quả không phải số, Collection hay array');
                }
                
                \Debugbar::info('Số dư tổng cuối cùng: ' . $this->pSoDu);
            } else {
                $this->pSoDu = null;
                \Debugbar::info('Không có tài khoản để lấy số dư');
            }
        } catch (\Exception $e) {
            $this->pSoDu = null;
            \Debugbar::error('Lỗi khi lấy số dư: ' . $e->getMessage());
        }
        
        // Cập nhật số dư cho từng dòng
        $this->updateSoDuTungDong();
    }
    
    public function updateSoDuChiTiet(): void
    {
        \Debugbar::info('updateSoDuChiTiet called');
        // Cập nhật số dư khi thay đổi tài khoản trong chi tiết
        $this->updateSoDu();
        $this->updateSoDuTungDong();
    }
    
    public function updateSoDuTungDong(): void
    {
        \Debugbar::info('updateSoDuTungDong called');
        // Tính số dư cho từng dòng chi tiết
        $this->pSoDuCts = collect();
        
        foreach ($this->pCts as $index => $row) {
            $soDuValue = null;
            
            if (!empty($row['ma_tk']) && !empty($this->pMa_Kh) && !empty($this->pNgay_Ct)) {
                try {
                    \Debugbar::info('Lấy số dư cho tài khoản dòng ' . $index . ': ' . $row['ma_tk']);
                    \Debugbar::info('Params cho dòng ' . $index . ':', [
                        'pMa_Cty' => \CatalogService::company()->id ?? '001',
                        'pMa_kh' => $this->pMa_Kh,
                        'pTk' => $row['ma_tk'],
                        'pNgay' => $this->pNgay_Ct,
                    ]);
                    
                    $soDuResult = AsGetSoDuKh::call([
                        'pMa_Cty' => \CatalogService::company()->id ?? '001',
                        'pMa_kh' => $this->pMa_Kh,
                        'pTk' => $row['ma_tk'],
                        'pNgay' => $this->pNgay_Ct,
                    ]);
                    
                    \Debugbar::info('Kết quả AsGetSoDuKh cho dòng ' . $index . ' (raw):', $soDuResult);
                    \Debugbar::info('Kiểu dữ liệu kết quả dòng ' . $index . ': ' . gettype($soDuResult));
                    
                    // Xử lý kết quả trả về
                    if (is_numeric($soDuResult)) {
                        $soDuValue = (float) $soDuResult;
                        \Debugbar::info('Kết quả dòng ' . $index . ' là số: ' . $soDuValue);
                    } elseif ($soDuResult instanceof \Illuminate\Support\Collection) {
                        \Debugbar::info('Kết quả dòng ' . $index . ' là Collection, count: ' . $soDuResult->count());
                        
                        if ($soDuResult->isNotEmpty()) {
                            $firstItem = $soDuResult->first();
                            if (is_object($firstItem) && property_exists($firstItem, 'so_du')) {
                                $soDuValue = (float) $firstItem->so_du;
                                \Debugbar::info('Lấy so_du từ object dòng ' . $index . ': ' . $soDuValue);
                            } elseif (is_array($firstItem) && isset($firstItem['so_du'])) {
                                $soDuValue = (float) $firstItem['so_du'];
                                \Debugbar::info('Lấy so_du từ array dòng ' . $index . ': ' . $soDuValue);
                            } else {
                                $soDuValue = null;
                                \Debugbar::info('Không tìm thấy so_du trong first item dòng ' . $index);
                            }
                        } else {
                            $soDuValue = null;
                            \Debugbar::info('Collection rỗng dòng ' . $index);
                        }
                    } elseif (is_array($soDuResult)) {
                        \Debugbar::info('Kết quả dòng ' . $index . ' là array, count: ' . count($soDuResult));
                        
                        if (!empty($soDuResult)) {
                            $firstItem = $soDuResult[0];
                            if (is_object($firstItem) && property_exists($firstItem, 'so_du')) {
                                $soDuValue = (float) $firstItem->so_du;
                                \Debugbar::info('Lấy so_du từ object dòng ' . $index . ': ' . $soDuValue);
                            } elseif (is_array($firstItem) && isset($firstItem['so_du'])) {
                                $soDuValue = (float) $firstItem['so_du'];
                                \Debugbar::info('Lấy so_du từ array dòng ' . $index . ': ' . $soDuValue);
                            } else {
                                $soDuValue = null;
                                \Debugbar::info('Không tìm thấy so_du trong first item dòng ' . $index);
                            }
                        } else {
                            $soDuValue = null;
                            \Debugbar::info('Array rỗng dòng ' . $index);
                        }
                    } else {
                        $soDuValue = null;
                        \Debugbar::info('Kết quả dòng ' . $index . ' không phải số, Collection hay array');
                    }
                    
                    \Debugbar::info('Số dư nhận được dòng ' . $index . ': ' . $soDuValue);
                } catch (\Exception $e) {
                    $soDuValue = null;
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
            'ma_tk'      => '',
            'dien_giai'  => $this->pDien_Giai,
            'ps_no'      => 0,
            'ps_co'      => 0,
            'ps_no_nt'   => 0,
            'ps_co_nt'   => 0,
            'ma_kh'      => $this->pMa_Kh ?? '',
            'ma_hd'      => '',
            'ma_bp'      => '',
            'ma_phi'     => '',
            'ma_spct'    => '',
            'ma_lo'      => '',
            'ma_ku'      => '',
        ]);
        
        // Thêm entry số dư cho dòng mới
        $this->pSoDuCts->put($newIndex, null);
    }

    public function removeRow($index): void
    {
        $this->pCts->forget($index);
        $this->pCts = $this->pCts->values(); // Re-index
        
        $this->pSoDuCts->forget($index);
        $this->pSoDuCts = $this->pSoDuCts->values()->mapWithKeys(function ($value, $newIndex) {
            return [$newIndex => $value];
        });
        
        if ($this->pCts->isEmpty()) {
            $this->addRow();
        }
        
        $this->calculateTotal();
    }

    public function render()
    {
        \Debugbar::info('render');

        // diepxuan/laravel-catalog/resources/views/cash/nganhang/baono/phieubaono.blade.php
        return view('catalog::cash.nganhang.baono.phieubaono', [
            'arDmKhs' => ArDmKh::all(),
        ]);
    }
}
