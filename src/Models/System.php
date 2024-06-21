<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-21 07:57:20
 */

namespace Diepxuan\Catalog\Models;

use Carbon\Carbon;
use Diepxuan\Simba\Models\System as SSystem;
use Diepxuan\Simba\Models\SystemConfig;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class System extends SSystem
{
    public function systemConfig(): HasOne
    {
        return $this->hasOne(SystemConfig::class, 'ma_cty', 'ma_cty');
    }

    protected function khoaSo(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Carbon::parse($this->systemConfig->ngay_ks)->format('Y-m-d'),
            set: function (mixed $value, array $attributes): void {
                $this->systemConfig->ngay_ks = Carbon::createFromFormat('Y-m-d', $value)->toDateTimeString();
                $this->push();
                $this->refresh();
            },
        );
    }
}
