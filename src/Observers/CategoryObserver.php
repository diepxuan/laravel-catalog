<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-15 11:37:04
 */

namespace Diepxuan\Catalog\Observers;

use Diepxuan\Catalog\Models\Category;
use Diepxuan\Magento\Magento;

class CategoryObserver
{
    public function created(Category $cat): void
    {
        if ($cat->isRoot) {
            return;
        }
        $this->createdDefault($cat);
        $this->createdEveron($cat);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $cat): void
    {
        if ($cat->isRoot) {
            return;
        }

        $this->updateDefault($cat);
        $this->updateEveron($cat);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $cat): void
    {
        if ($cat->isRoot) {
            return;
        }

        try {
            Magento::categories()->find($cat->magento->default)->delete();
        } catch (\Throwable $th) {
        }

        try {
            Magento::categories()->find($cat->magento->everon)->delete();
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
        if ($cat->isRoot) {
            return;
        }

        try {
            Magento::categories()->find($cat->magento->default)->delete();
        } catch (\Throwable $th) {
        }

        try {
            Magento::categories()->find($cat->magento->everon)->delete();
        } catch (\Throwable $th) {
        }
    }

    public function data(Category $cat, $website = Category::TYPEDEFAULT)
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
                    'attribute_code' => 'meta_title',
                    'value'          => $cat->name,
                ],
            ],
        ];

        switch ($website) {
            case Category::TYPEEVR:
                $data['parent_id'] = $cat->catParent->magento->everon;

                break;

            case Category::TYPEDEFAULT:
            default:
                $data['parent_id']         = $cat->parent ? $cat->catParent->magento->default : 2;
                $data['custom_attributes'] = array_merge($data['custom_attributes'], [
                    [
                        'attribute_code' => 'url_key',
                        'value'          => $cat->urlKey,
                    ],
                    [
                        'attribute_code' => 'url_path',
                        'value'          => $cat->urlKey,
                    ],
                ]);

                break;
        }

        return $data;
    }

    private function createdDefault(Category $cat): void
    {
        try {
            $mCat                  = Magento::categories()->create($this->data($cat));
            $cat->magento->default = $mCat->id;
            if ($cat->isDirty()) {
                $cat->save();
            }
        } catch (\Throwable $th) {
        }
    }

    private function createdEveron(Category $cat): void
    {
        if ($cat->catParent && $cat->catParent->magento->everon > 0) {
            try {
                $mCat                 = Magento::categories()->create($this->data($cat, Category::TYPEEVR));
                $cat->magento->everon = $mCat->id;
                if ($cat->isDirty()) {
                    $cat->save();
                }
            } catch (\Throwable $th) {
            }
        }
    }

    private function updateDefault(Category $cat): void
    {
        try {
            if ($cat->magento->default > 0) {
                Magento::categories()->find($cat->magento->default)->update($this->data($cat));
            } else {
                $this->createdDefault($cat);
            }
        } catch (\Throwable $th) {
            $this->createdDefault($cat);
        }
    }

    private function updateEveron(Category $cat): void
    {
        try {
            if ($cat->magento->everon > 0 && 1_953 !== $cat->magento->everon) {
                // dd($this->data($cat, Category::TYPEEVR));
                $mCat = Magento::categories()->find($cat->magento->everon)->update($this->data($cat, Category::TYPEEVR));
                // dd($mCat);
            } else {
                $this->createdEveron($cat);
            }
        } catch (\Throwable $th) {
            $this->createdEveron($cat);
        }
    }
}
