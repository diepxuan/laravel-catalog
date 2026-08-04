<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-08-03 00:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\So\Dict;

use Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh as BaseArdmkh;
use Illuminate\View\View;

/**
 * SO ARDMKH khách hàng — canonical namespace.
 * Kế thừa Po\Dict\Ardmkh; chỉ đổi mode SP + view + label tiếng Việt.
 */
class Ardmkh extends BaseArdmkh
{
    protected function spModuleId(): string
    {
        return 'AR';
    }

    protected function listView(): string
    {
        return 'catalog::so.dict.ardmkh';
    }

    protected function moduleLabel(): string
    {
        return 'SO';
    }

    protected function notFoundMessage(): string
    {
        return 'Không tìm thấy khách hàng.';
    }

    protected function hasTransactionsMessage(): string
    {
        return 'Không thể xóa khách hàng đã có giao dịch.';
    }

    protected function deletedMessage(string $maKh): string
    {
        return 'Đã xóa khách hàng ' . $maKh;
    }

    protected function deleteFailedMessage(string $maKh, string $reason): string
    {
        return 'Không thể xóa khách hàng ' . $maKh . ': ' . $reason;
    }
}
