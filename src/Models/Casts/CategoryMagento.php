<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-07 22:47:52
 */

namespace Diepxuan\Catalog\Models\Casts;

use Diepxuan\Catalog\Models\Category;
use Diepxuan\Magento\Magento;

class CategoryMagento
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Category $model, string $key, mixed $value, array $attributes): ?\Diepxuan\Magento\Models\Category
    {
        $magento = Magento::categories()->get([['field' => 'name', 'value' => $model->name]]);

        return $magento->first();
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
        return [
        ];
    }
}
