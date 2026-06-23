<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-21 07:31:00
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Simba\Models\SysDictionaryInfo as SimbaModel;

/**
 * Model SysDictionaryInfo - SimbaERP dictionary metadata (catalog layer).
 *
 * Mở rộng từ Simba SysDictionaryInfo để Catalog dùng model nội bộ thay vì import trực tiếp model Simba.
 */
class SysDictionaryInfo extends SimbaModel
{
}
