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

use Diepxuan\Catalog\Models\Concerns\HasSoCt1SalesMetrics;
use Diepxuan\Simba\Models\SoCt1 as SimbaModel;

/**
 * Model SoCt1 - chi tiết đơn bán hàng (catalog layer).
 *
 * Mở rộng từ Simba\SoCt1 với chỉ tiêu / báo cáo bán hàng nghiệp vụ
 * (HasSoCt1SalesMetrics).
 */
class SoCt1 extends SimbaModel
{
    use HasSoCt1SalesMetrics;
}