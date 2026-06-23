<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-04 10:22:13
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Catalog\Models\Concerns\HasSysCompanyLocalizedResx;
use Diepxuan\Simba\Models\SysCompany as SimbaModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SysCompany extends SimbaModel
{
    use HasSysCompanyLocalizedResx;

    public function siSetup(): HasOne
    {
        return $this->hasOne(SiSetup::class, 'ma_cty', 'ma_cty');
    }

    protected function id(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $attributes['ma_cty'] ?? ''
        );
    }
}
