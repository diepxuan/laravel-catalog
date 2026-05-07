<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-05 20:11:18
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\ArDmPlKh as Model;
use Diepxuan\Simba\SModel\SModel;

/**
 * Model ArDmPlKh - Danh mục phân loại khách hàng (catalog layer).
 */
class ArDmPlKh extends Model
{
    /**
     * Company constant.
     */
    public const CTY = SModel::CTY;
}
