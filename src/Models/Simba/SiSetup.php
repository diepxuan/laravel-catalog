<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-04 10:42:11
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Simba\Models\SiSetup as SimbaModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SiSetup extends SimbaModel
{
    protected function system(): BelongsTo
    {
        return $this->belongsTo(SysCompany::class, 'ma_cty', 'ma_cty');
    }

    protected function ngayDntc(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => Carbon::parse($value ?? '1900-01-01 00:00:00')
        );
    }
}
