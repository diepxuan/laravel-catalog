<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-03 22:47:04
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemConfig extends SiSetup
{
    protected function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'ma_cty', 'ma_cty');
    }
}
