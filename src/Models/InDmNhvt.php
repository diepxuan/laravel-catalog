<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 21:12:40
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Models\Casts\CategoryMagento;
use Diepxuan\Simba\Models\InDmNhvt as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

class InDmNhvt extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'magento' => CategoryMagento::class,
    ];

    /**
     * Gọi stored procedure asINGetDMNHVT để lấy dữ Danh sách nhóm vật tư - hàng hóa.
     *
     * @return array
     */
    public static function getAsINGetDMNHVT(array $params): Collection
    {
        return self::hydrate(parent::getAsINGetDMNHVT($params)->toArray());
    }

    protected function ksd(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['ksd'] ?? $attributes['KSD'] ?? false) ? '✔' : ''
        );
    }

    protected function congSl(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['cong_sl'] ?? false) ? '✔' : ''
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), $this->casts);
    }
}
