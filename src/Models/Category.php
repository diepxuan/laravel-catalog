<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-04 18:37:05
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Models\Casts\CategoryMagento;
use Diepxuan\Catalog\Observers\CategoryObserver;
use Diepxuan\Simba\Models\Category as SCategory;

#[ObservedBy([CategoryObserver::class])]
class Category extends SCategory
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), $this->casts);
    }
}
