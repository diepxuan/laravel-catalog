<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-14 21:41:58
 */

namespace Diepxuan\Catalog\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CategoryMagento implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        $value      = new \stdClass();
        $magento_id = array_replace([2, 1_953], explode(',', $attributes['magento_id']));

        $value->default = $magento_id[0];
        $value->everon  = $magento_id[1];

        if ('PRODUCT' === $attributes['sku']) {
            $value->default = 2;
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     *
     * @return array<string, string>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if ('PRODUCT' === $attributes['sku']) {
            $value->default = 2;
        }

        return [
            'magento_id' => "{$value->default},{$value->everon}",
        ];
    }
}
