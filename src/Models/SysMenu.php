<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-20 12:54:00
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\SysMenu as SimbaModel;

/**
 * Model SysMenu - SimbaERP menu metadata (catalog layer).
 *
 * Mở rộng từ Simba SysMenu để Catalog dùng model nội bộ thay vì import trực tiếp model Simba.
 */
class SysMenu extends SimbaModel
{
}
