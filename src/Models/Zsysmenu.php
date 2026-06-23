<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-20 00:00:00
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Models\Simba\SysMenu;

/**
 * Model Zsysmenu - SimbaERP z menu metadata (catalog layer).
 *
 * zsysmenu has the same column structure as sysMenu in simba-docs/tables,
 * so Catalog treats it as the same menu contract with a different table.
 */
class Zsysmenu extends SysMenu
{
    protected $table = 'zsysmenu';
}
