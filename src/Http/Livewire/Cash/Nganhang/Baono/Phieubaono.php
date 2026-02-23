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
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
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
    public Collection $pCts;
    public $pSoDu;
    public $pTong_Ps_No = 0;

    protected $rules = [
        'pMa_Kh'       => 'required',
        'pTk_Co'       => 'required',
        'pNgay_Ct'     => 'required|date',
        'pCts.*.ma_tk' => 'required',
        'pCts.*.ps_no' => 'required|numeric|min:0',
    ];

    public function mount(): void
    {
        $this->pNgay_Ct  = now()->toDateString();
        $this->pNgay_Lap = now()->toDateString();
        $this->pCts      = collect();
        $this->addRow();
        
        $this->pSo_Ct    = AsGetSoCt::call([
            'pMa_ct'   => 'CA4',
            'pNgay_Ct' => $this->pNgay_Ct,
        ]);
        \Debugbar::info('mount');
    }

    public function updated($property): void
    {
        if ($property == 'pMa_Kh') {
            $this->updateKhachHang();
        }

        if ($property == 'pDien_Giai') {
            $this->pCts = $this->pCts->map(function ($row) {
                $row['dien_giai'] = $this->pDien_Giai;
                return $row;
            });
        }

        if (str_contains($property, 'pCts')) {
            $this->calculateTotal();
        }
        
        \Debugbar::info('updated');
        \Debugbar::info($property);
    }

    public function calculateTotal(): void
    {
        $this->pTong_Ps_No = $this->pCts->sum(function ($item) {
            return (float) ($item['ps_no'] ?? 0);
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

        $stt_rec = \Str::uuid()->toString(); // Or generate appropriate ID

        // Create Header
        $caPh3 = new \Diepxuan\Catalog\Models\CaPh3();
        // Assume model has fillable or we set attributes directly
        // Mapping based on CaPh3 table structure (inferred)
        
        // Transaction to ensure atomicity
        \DB::transaction(function () use ($stt_rec) {
            $user = \Auth::user();
            
            // 1. Save Header (CaPh3)
            $caPh3 = new \Diepxuan\Catalog\Models\CaPh3();
            $caPh3->stt_rec   = $stt_rec;
            $caPh3->ma_ct     = 'CA4';
            $caPh3->ngay_ct   = $this->pNgay_Ct;
            $caPh3->ngay_lct  = $this->pNgay_Lap;
            $caPh3->so_ct     = $this->pSo_Ct;
            $caPh3->ma_kh     = $this->pMa_Kh;
            $caPh3->dien_giai = $this->pDien_Giai;
            $caPh3->tk_co     = $this->pTk_Co;
            $caPh3->t_tien    = $this->pTong_Ps_No;
            $caPh3->ma_nt     = 'VND'; 
            $caPh3->ty_gia    = 1;
            $caPh3->ma_cty    = \CatalogService::company()->id ?? '001';
            $caPh3->status    = '1'; // Active?
            // Add other required fields
            $caPh3->save();

            // 2. Save Details (CaCt3)
            foreach ($this->pCts as $index => $row) {
                if (empty($row['ma_tk']) || empty($row['ps_no'])) continue;

                $caCt3 = new \Diepxuan\Catalog\Models\CaCt3();
                $caCt3->stt_rec   = $stt_rec;
                $caCt3->stt_rec0  = str_pad((string)$index, 3, '0', STR_PAD_LEFT); 
                $caCt3->ma_ct     = 'CA4';
                $caCt3->ngay_ct   = $this->pNgay_Ct;
                $caCt3->ma_cty    = $caPh3->ma_cty;
                $caCt3->tk        = $row['ma_tk'];
                $caCt3->ps_no     = $row['ps_no'];
                $caCt3->ps_no_nt  = $row['ps_no']; // Assuming VND
                $caCt3->dien_giai = $row['dien_giai'] ?? $this->pDien_Giai;
                // Add other fields
                $caCt3->save();
            }

            // 3. Post to GL
            // Executing stored procedure using stored procedure class
            AsPostCaph3_glct::call([
                'pMa_cty' => $caPh3->ma_cty,
                'pStt_rec' => $stt_rec,
            ]);
        });

        session()->flash('message', 'Phiếu báo nợ đã được lưu thành công.');
        
        // Reset form
        $this->reset(['pDien_Giai', 'pCts', 'pSo_Ct', 'pTong_Ps_No']);
        $this->mount();
    }

    public function updateKhachHang(): void
    {
        $this->pKh   = ArDmKh::where('ma_kh', $this->pMa_Kh)->first();
        if ($this->pKh) {
             $this->pDien_Giai = 'Thu tiền khách hàng ' . $this->pKh->ten_kh;
             // Update So Du if needed
             // $this->pSoDu = ...
        }
        \Debugbar::info('updateKhachHang');
    }

    public function updateCt(): void
    {
        // This seems unused or redundant if we use valid Livewire binding
    }

    public function addRow(): void
    {
        $this->pCts->push([
            'ma_tk'     => '',
            'dien_giai' => $this->pDien_Giai,
            'ps_no'     => 0,
        ]);
    }

    public function removeRow($index): void
    {
        $this->pCts->forget($index);
        $this->pCts = $this->pCts->values(); // Re-index
        
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
