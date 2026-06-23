<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-05 20:11:17
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Simba\Models\ArDmNhKh as SimbaModel;
use Diepxuan\Simba\SModel\SModel;

/**
 * Model ArDmNhKh - Danh mục nhóm khách hàng (catalog layer).
 */
class ArDmNhKh extends SimbaModel
{
    /**
     * Company constant.
     */
    public const CTY = SModel::CTY;
}
