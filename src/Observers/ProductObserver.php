<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-27 19:30:51
 */

namespace Diepxuan\Catalog\Observers;

use Diepxuan\Catalog\Models\Product;
use Diepxuan\Magento\Magento;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $prod): void
    {
        try {
            Magento::products()->create($this->data($prod));
        } catch (\Throwable $th) {
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $prod): void
    {
        try {
            Magento::products()->find($prod->sku)->update($this->data($prod));
        } catch (\Throwable $th) {
            $this->created($prod);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $prod): void
    {
        try {
            Magento::products()->find($prod->sku)->delete();
        } catch (\Throwable $th) {
        }
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $prod): void
    {
        // ...
    }

    /**
     * Handle the Product "forceDeleted" event.
     */
    public function forceDeleted(Product $prod): void
    {
        try {
            Magento::products()->find($prod->sku)->delete();
        } catch (\Throwable $th) {
        }
    }

    /**
     * Magento Product Data.
     *
     * @param mixed $prod
     */
    public function data(Product $prod)
    {
        $data = [
            'sku'              => $prod->sku,
            'name'             => $prod->name,
            'price'            => $prod->price,
            'status'           => $prod->status,
            'attribute_set_id' => 4,
            'visibility'       => 4,
            'type_id'          => 'simple',
            'stock'            => [
                'qty'         => $prod->quantity,
                'is_in_stock' => true,
            ],
            'custom_attributes' => [
                [
                    'attribute_code' => 'meta_title',
                    'value'          => $prod->name,
                ],
                [
                    'attribute_code' => 'meta_keyword',
                    'value'          => $prod->name,
                ],
                [
                    'attribute_code' => 'meta_description',
                    'value'          => $prod->name,
                ],
                [
                    'attribute_code' => 'url_key',
                    'value'          => $prod->urlKey,
                ],
            ],
        ];
        if ($prod->cat) {
            $data['custom_attributes'][] = [
                'attribute_code' => 'category_ids',
                'value'          => [$prod->cat->magento_id],
            ];
        }

        return $data;
    }
}
