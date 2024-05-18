<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-17 10:27:34
 */

namespace Diepxuan\Catalog\Observers;

use Diepxuan\Catalog\Models\Category;
use Diepxuan\Magento\Magento;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $cat): void
    {
        try {
            Magento::categories()->create($this->data($cat));
        } catch (\Throwable $th) {
        }
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $cat): void
    {
        try {
            Magento::categories()->find($cat->magento_id)->update($this->data($cat));
        } catch (\Throwable $th) {
            $this->created($cat);
        }
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $cat): void
    {
        try {
            Magento::categories()->find($cat->magento_id)->delete();
        } catch (\Throwable $th) {
        }
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
        try {
            Magento::categories()->find($cat->magento_id)->delete();
        } catch (\Throwable $th) {
        }
    }

    public function data(Category $cat)
    {
        $data = [
            'name'              => $cat->name,
            'is_active'         => true,
            'include_in_menu'   => $cat->include_in_menu,
            'custom_attributes' => [
                [
                    'attribute_code' => 'display_mode',
                    'value'          => 'PRODUCTS',
                ],
                [
                    'attribute_code' => 'is_anchor',
                    'value'          => 1,
                ],
                [
                    'attribute_code' => 'url_key',
                    'value'          => $cat->urlKey,
                ],
                [
                    'attribute_code' => 'url_path',
                    'value'          => $cat->urlKey,
                ],
                [
                    'attribute_code' => 'meta_title',
                    'value'          => $cat->name,
                ],
            ],
        ];
        $data['parent_id'] = $cat->parent ? $cat->catParent->magento_id : 2;

        return $data;
    }
}
