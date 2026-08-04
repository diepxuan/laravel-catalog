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

namespace Diepxuan\Catalog\Http\Livewire\Ca\Dict;

use Diepxuan\Catalog\Http\Livewire\Po\Dict\Ardmkh as BaseArdmkh;

/**
 * CA ARDMKH nhân viên — canonical namespace.
 * Kế thừa Po\Dict\Ardmkh; chỉ đổi mode SP + view + label tiếng Việt.
 */
class Ardmkh extends BaseArdmkh
{
    protected function spModuleId(): string
    {
        return 'CA';
    }

    protected function listView(): string
    {
        return 'catalog::ca.dict.ardmkh';
    }

    protected function moduleLabel(): string
    {
        return 'CA';
    }

    protected function notFoundMessage(): string
    {
        return 'Không tìm thấy nhân viên.';
    }

    protected function hasTransactionsMessage(): string
    {
        return 'Không thể xóa nhân viên đã có giao dịch.';
    }

    protected function deletedMessage(string $maKh): string
    {
        return 'Đã xóa nhân viên ' . $maKh;
    }

    protected function deleteFailedMessage(string $maKh, string $reason): string
    {
        return 'Không thể xóa nhân viên ' . $maKh . ': ' . $reason;
    }
}
