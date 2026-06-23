<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-22 10:20:30
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Simba\Models\CaPh3 as SimbaModel;

/**
 * Model CaPh3 (Catalog layer).
 *
 * Extends `Diepxuan\Simba\Models\CaPh3` thay vì SModel trực tiếp để:
 * - Đồng nhất pattern với các Catalog Model khác (đi qua lớp Simba).
 * - Tận dụng `HasSimbaCompositeKey` cho primary key composite.
 */
class CaPh3 extends SimbaModel
{
}
