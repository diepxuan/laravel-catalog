<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-14 22:49:46
 */

namespace Diepxuan\Catalog\Models\Casts;

use Diepxuan\Catalog\Models\Category;

class CategoryMagento
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Category $model, string $key, mixed $value, array $attributes)
    {
        $value      = new \stdClass();
        $magento_id = array_replace([-1, -1], explode(',', $attributes['magento_id']));

        $value->default = $magento_id[0];
        $value->everon  = $magento_id[1];

        if (Category::ROOT === $attributes['sku']) {
            $value->default = 2;
        }
        if (Category::EVR === $attributes['sku']) {
            $value->everon = 1_953;
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
    public function set(Category $model, string $key, mixed $value, array $attributes)
    {
        if (Category::ROOT === $attributes['sku']) {
            $value->default = 2;
        }
        if (Category::EVR === $attributes['sku']) {
            $value->everon = 1_953;
        }

        return [
            'magento_id' => "{$value->default},{$value->everon}",
        ];
    }
}
