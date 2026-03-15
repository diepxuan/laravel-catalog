<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-15 17:08:09
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang;

use Diepxuan\Catalog\Models\GlCt;
use Diepxuan\Simba\StoredProcedures\AsCADelCT2;
use Diepxuan\Simba\StoredProcedures\AsCADelPH2;
use Diepxuan\Simba\StoredProcedures\AsProcessCt;
use Illuminate\View\View;
use Livewire\Component;

class Baono extends Component
{
    public $pTk_List   = '';
    public $pTkdu_List = '112';
    public $pMa_Nt;
    public $pMa_Bp;

    // Property để quản lý chế độ sửa
    public $editingSttRec = '';
    public $showForm      = false;

    protected $glCts;

    public function mount(): void
    {
        $this->pMa_Nt = \CatalogService::ma_Nt();
        $this->resultRender();
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    public function submit(): void
    {
        $this->resultRender();
    }

    /**
     * Mở form sửa phiếu.
     *
     * @param mixed $sttRec
     */
    public function editPhieu($sttRec): void
    {
        $this->editingSttRec = $sttRec;
        $this->showForm      = true;

        // Scroll to form
        $this->dispatch('scroll-to-form');

        $this->resultRender();
    }

    /**
     * Xóa phiếu.
     *
     * @param mixed $sttRec
     */
    public function deletePhieu($sttRec): void
    {
        // Gọi stored procedure để xóa
        $maCty = \CatalogService::company()->id ?? '001';
        $lUser = \Auth::user()->name ?? '';

        try {
            \DB::transaction(static function () use ($maCty, $sttRec): void {
                // 1. Process chứng từ để unlock (mode 2 = sửa/xóa)
                AsProcessCt::call([
                    'pMa_cty'  => $maCty,
                    'pMa_Ct'   => 'CA4',
                    'pStt_rec' => $sttRec,
                    'pMode'    => '2',
                ]);

                // 2. Xóa chi tiết
                $deleteDetails = AsCADelCT2::call([
                    'pMa_cty'  => $maCty,
                    'pStt_rec' => $sttRec,
                ]);

                if (0 !== $deleteDetails) {
                    throw new \Exception('Không thể xóa chi tiết phiếu báo nợ');
                }

                // 3. Xóa header (dùng AsCADelPH2 nếu có)
                // Lưu ý: Cần kiểm tra stored procedure AsCADelPH2 có tồn tại chưa
                if (class_exists('\Diepxuan\Simba\StoredProcedures\AsCADelPH2')) {
                    $deleteHeader = AsCADelPH2::call([
                        'pMa_cty'  => $maCty,
                        'pStt_rec' => $sttRec,
                    ]);

                    if (0 !== $deleteHeader) {
                        throw new \Exception('Không thể xóa phiếu báo nợ');
                    }
                }
            });

            // Refresh danh sách
            $this->resultRender();

            $this->dispatch('action-message', ['on' => 'phieu-deleted']);
        } catch (\Exception $e) {
            $this->dispatch('action-message', ['on' => 'delete-error', 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Đóng form.
     */
    public function closeForm(): void
    {
        $this->editingSttRec = '';
        $this->showForm      = false;
        $this->resultRender();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/cash/nganhang/baono.blade.php
        return view('catalog::cash.nganhang.baono', [
            'glCts' => $this->glCts,
        ])->layout('catalog::layouts.app');
    }

    protected function resultRender(): void
    {
        // $this->glCts = GlCt::getNKThuChi([
        //     'ma_cty'    => \CatalogService::company()->id,
        //     'ngay_ct1'  => \CatalogService::timerFrom(),
        //     'ngay_ct2'  => \CatalogService::timerTo(),
        //     'tk_list'   => $this->pTk_List,
        //     'tkdu_list' => $this->pTkdu_List,
        //     'ma_bp'     => $this->pMa_Bp ?? '',
        //     'ma_nt'     => 'VND',
        // ])->get();

        $this->glCts = GlCt::getGLRptNKC03([
            'pMa_Cty'    => \CatalogService::company()->id,
            'pNgay_Ct1'  => \CatalogService::timerFrom(),
            'pNgay_Ct2'  => \CatalogService::timerTo(),
            'pTk_List'   => $this->pTk_List,
            'pTkdu_List' => $this->pTkdu_List,
            'pMa_Nt'     => $this->pMa_Nt,
            'pMa_Bp'     => $this->pMa_Bp,
        ]);
    }
}
