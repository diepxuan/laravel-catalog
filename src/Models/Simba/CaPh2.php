<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-04-05 23:04:19
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Simba\Models\CaPh2 as SimbaModel;

/**
 * Model CaPh2 (Catalog layer).
 *
 * Extends `Diepxuan\Simba\Models\CaPh2` thay vì SModel trực tiếp để:
 * - Đồng nhất pattern với các Catalog Model khác (đi qua lớp Simba).
 * - Tận dụng `HasSimbaCompositeKey` cho primary key composite.
 */
class CaPh2 extends SimbaModel {}
