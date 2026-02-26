<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-02-26 12:33:00
 */

namespace Diepxuan\Catalog\Http\Livewire\System\Balance;

use Diepxuan\Simba\StoredProcedures\AsGLChuyenSdTk;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Transfer extends Component
{
    public $currentYear;
    public $newYear;
    public $transferDate;
    public $note;
    public $message = '';
    public $messageType = ''; // 'success', 'error', 'warning'
    public $isProcessing = false;
    public $previewData = [];

    protected $rules = [
        'currentYear' => 'required|integer|min:2000|max:2100',
        'newYear' => 'required|integer|min:2000|max:2100',
        'transferDate' => 'required|date',
        'note' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->currentYear = date('Y');
        $this->newYear = date('Y') + 1;
        $this->transferDate = date('Y-12-31');
        $this->note = 'Chuyển số dư từ năm ' . $this->currentYear . ' sang năm ' . $this->newYear;
        
        // Load preview data
        $this->loadPreviewData();
    }

    /**
     * Load preview data for balance transfer
     */
    public function loadPreviewData()
    {
        // This is sample data - in real implementation, you would query the database
        // to get actual balance data for the current year
        $this->previewData = [
            [
                'account' => '111 - Tiền mặt',
                'current_balance' => '50,000,000',
                'new_balance' => '50,000,000',
                'status' => 'Sẵn sàng',
            ],
            [
                'account' => '112 - Tiền gửi ngân hàng',
                'current_balance' => '200,000,000',
                'new_balance' => '200,000,000',
                'status' => 'Sẵn sàng',
            ],
            [
                'account' => '131 - Phải thu khách hàng',
                'current_balance' => '150,000,000',
                'new_balance' => '150,000,000',
                'status' => 'Sẵn sàng',
            ],
            [
                'account' => '331 - Phải trả người bán',
                'current_balance' => '80,000,000',
                'new_balance' => '80,000,000',
                'status' => 'Sẵn sàng',
            ],
        ];
    }

    /**
     * Execute balance transfer using stored procedure asGLChuyenSdTk
     */
    public function executeTransfer()
    {
        $this->validate();

        // Check if new year is greater than current year
        if ($this->newYear <= $this->currentYear) {
            $this->message = 'Năm mới phải lớn hơn năm hiện tại';
            $this->messageType = 'error';
            return;
        }

        // Check if transfer date is end of current year
        $transferYear = date('Y', strtotime($this->transferDate));
        if ($transferYear != $this->currentYear) {
            $this->message = 'Ngày chuyển phải là ngày cuối năm hiện tại (' . $this->currentYear . '-12-31)';
            $this->messageType = 'error';
            return;
        }

        $this->isProcessing = true;
        $this->message = '';
        $this->messageType = '';

        try {
            // Prepare parameters for stored procedure
            $params = [
                'pMa_cty' => '001', // Default company code
                'pNgay_cnt' => $this->transferDate . ' 23:59:59', // End of year datetime
            ];

            // Call the stored procedure
            $result = AsGLChuyenSdTk::call($params);

            // Check the result
            if ($result instanceof Collection) {
                $retValue = $result->get('pRet', -1);
                
                if ($retValue === 0) {
                    $this->message = 'Chuyển số dư thành công từ năm ' . $this->currentYear . ' sang năm ' . $this->newYear;
                    $this->messageType = 'success';
                    
                    // Update preview data for new year
                    $this->loadPreviewData();
                } else {
                    // Handle specific error codes
                    $errorMessage = $this->getErrorMessage($retValue);
                    $this->message = 'Lỗi khi chuyển số dư: ' . $errorMessage . ' (Mã lỗi: ' . $retValue . ')';
                    $this->messageType = 'error';
                }
            } else {
                $this->message = 'Lỗi không xác định khi gọi stored procedure';
                $this->messageType = 'error';
            }
        } catch (\Exception $e) {
            $this->message = 'Lỗi hệ thống: ' . $e->getMessage();
            $this->messageType = 'error';
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Get error message based on error code from stored procedure
     */
    private function getErrorMessage(int $errorCode): string
    {
        $errorMessages = [
            50012 => 'Ngày đầu năm sau nhỏ hơn hoặc bằng ngày kế sách. Không thể chuyển số dư.',
            // Add more error codes as needed
        ];

        return $errorMessages[$errorCode] ?? 'Lỗi không xác định (Mã: ' . $errorCode . ')';
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
}
