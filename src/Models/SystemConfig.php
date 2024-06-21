<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-20 17:39:49
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\System;
use Diepxuan\Simba\Models\SystemConfig as Config;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemConfig extends Config
{
    protected function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'ma_cty', 'ma_cty');
    }
}
