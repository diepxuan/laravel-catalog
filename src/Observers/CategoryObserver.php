<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-15 09:24:06
 */

namespace Diepxuan\Catalog\Observers;

use Diepxuan\Catalog\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $cat): void
    {
        // ...
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $cat): void
    {
        // ...
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $cat): void
    {
        // ...
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $cat): void
    {
        // ...
    }

    /**
     * Handle the Category "forceDeleted" event.
     */
    public function forceDeleted(Category $cat): void
    {
        // ...
    }
}
