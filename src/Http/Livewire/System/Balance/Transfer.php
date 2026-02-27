<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-02-27 22:00:28
 */

namespace Diepxuan\Catalog\Http\Livewire\System\Balance;

use Diepxuan\Simba\StoredProcedures\AsGLChuyenSdTk;
use Illuminate\View\View;
use Livewire\Component;

class Transfer extends Component
{
    public $currentYear;
    public $newYear;
    public $transferDate;
    public $note;
    public $message      = '';
    public $messageType  = ''; // 'success', 'error', 'warning'
    public $isProcessing = false;
    public $previewData  = [];

    // helper bounds
    public $minYear;
    public $maxYear;

    protected $rules = [
        'currentYear' => 'required|integer|min:2000|max:2100',
    ];

    public function mount(): void
    {
        $this->maxYear     = now()->year;
        $this->minYear     = now()->subYears(4)->year;
        $this->currentYear = now()->subYears(1)->year;

        // Load preview data
        $this->resultRender();
    }

    public function resultRender(): void
    {
        $this->newYear      = now()->setYear((int) $this->currentYear)->addYear()->year;
        $this->transferDate = now()->setYear((int) $this->currentYear)->endOfYear();
    }

    public function updated($property): void
    {
        $this->resultRender();
    }

    /**
     * When user changes currentYear (from select) update dependent fields.
     */
    public function updatedCurrentYear(): void
    {
        // $this->resultRender();
        // \Debugbar::log($this->currentYear);
    }

    /**
     * Execute balance transfer using stored procedure asGLChuyenSdTk.
     */
    public function executeTransfer(): void
    {
        // Ensure currentYear within allowed range
        if ($this->currentYear < $this->minYear || $this->currentYear > $this->maxYear) {
            $this->message     = 'Năm hiện tại không hợp lệ';
            $this->messageType = 'error';

            return;
        }

        // Force transferDate to be 31/12/<currentYear>
        $this->newYear      = now()->setYear((int) $this->currentYear)->addYear()->year;
        $this->transferDate = now()->setYear((int) $this->currentYear)->endOfYear();

        $this->validate();

        $this->isProcessing = true;
        $this->message      = '';
        $this->messageType  = '';

        try {
            // Prepare parameters for stored procedure
            $params = [
                'pMa_cty'   => catalog()->company()->id, // Default company code
                'pNgay_cnt' => $this->transferDate, // End of year datetime
            ];

            // Call the stored procedure
            $result = AsGLChuyenSdTk::call($params);

            // Handling result left commented to avoid accidental execution in dev
        } catch (\Exception $e) {
            $this->message     = 'Lỗi hệ thống: ' . $e->getMessage();
            $this->messageType = 'error';
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::system.balance.transfer')->layout('catalog::layouts.app');
    }

    /**
     * Get error message based on error code from stored procedure.
     */
    private function getErrorMessage(int $errorCode): string
    {
        $errorMessages = [
            50_012 => 'Ngày đầu năm sau nhỏ hơn hoặc bằng ngày kế sách. Không thể chuyển số dư.',
            // Add more error codes as needed
        ];

        return $errorMessages[$errorCode] ?? 'Lỗi không xác định (Mã: ' . $errorCode . ')';
    }
}
