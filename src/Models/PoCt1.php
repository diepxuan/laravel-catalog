<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-06-22
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Models\Concerns\HasPoCt1PurchaseMetrics;
use Diepxuan\Simba\Models\PoCt1 as SimbaModel;

/**
 * Model PoCt1 - chi tiết đơn mua hàng (catalog layer).
 *
 * Mở rộng từ Simba\PoCt1 với chỉ tiêu / báo cáo mua hàng nghiệp vụ
 * (HasPoCt1PurchaseMetrics).
 */
class PoCt1 extends SimbaModel
{
    use HasPoCt1PurchaseMetrics;
}