<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-13 23:28:43
 */

namespace Diepxuan\Catalog\Commands;

use Diepxuan\Catalog\Models\Product;
use Diepxuan\Magento\Magento;
use Diepxuan\Simba\Models\Product as SProduct;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CatalogSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:catalog-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->productIntergration();
        $this->output->writeln('');
    }

    /**
     * Get and sync all of the product models from the database.
     * Map with Simba.
     * Map with Magento2.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public function productIntergration()
    {
        $self = $this;
        $self->output->writeln('[i] Loading all products');

        /** @var Collection $products */
        $products = Product::all()->keyBy('simbaId');
        $self->output->writeln(sprintf('[i] Loaded <fg=green>%s</> products.', $products->count()));

        $self->output->writeln('[i] Starting import Simba products');
        $self->withProgressBar(SProduct::all(), static function ($sProduct) use ($products): void {
            $id      = $sProduct->id;
            $product = $products->get($id, static function () use ($sProduct) {
                // $self->output->write("  {$sProduct->ma_vt}");

                $prod = Product::updateOrCreate(
                    ['sku' => "{$sProduct->ma_vt}"],
                    [
                        'name'  => "{$sProduct->ten_vt}",
                        'price' => 0,
                    ]
                );
                $prod->options()->updateOrCreate([
                    'code' => 'simba_id',
                ], [
                    'value' => "{$sProduct->id}",
                ]);

                return $prod;
            });

            if ($product->category !== $sProduct->ma_nhvt) {
                $product->options()->updateOrCreate([
                    'code' => 'category',
                ], [
                    'value' => $sProduct->ma_nhvt,
                ]);
            }

            $product->simba = $sProduct;
            $products->put($id, $product);

            // return $sProduct;
        });
        $self->output->writeln("\r\n[i] Finished import Simba products");

        $self->output->writeln('[i] Starting import Magento products');
        $mProducts = Magento::products()->get();
        $self->withProgressBar($mProducts, static function ($mProduct) use (&$products) {
            $id      = $mProduct->sku;
            $product = $products->get("001_{$id}", static function () use ($mProduct) {
                $prod = Product::updateOrCreate(
                    ['sku' => $mProduct->sku],
                    [
                        'name'  => $mProduct->name,
                        'price' => $mProduct->price,
                    ]
                );
                $prod->options()->updateOrCreate([
                    'code' => 'magento_id',
                ], [
                    'value' => $mProduct->id,
                ]);

                return $prod;
            });

            if ($product->magentoId !== $mProduct->id) {
                $product->options()->updateOrCreate([
                    'code' => 'magento_id',
                ], [
                    'value' => $mProduct->id,
                ]);
            }

            $product->magento = $mProduct;
            $products->put("001_{$id}", $product);

            return $mProduct;
        });
        $self->output->writeln("\r\n[i] Finished import Magento products");

        $self->output->writeln('[i] Starting sync Magento products');
        $self->withProgressBar($products->whereNull('magentoId'), static function ($product) use ($products): void {
            if (!$product->magentoId) {
                $sku     = $product->sku;
                $name    = $product->name;
                $price   = $product->price;
                $url_key = Str::of(vn_convert_encoding($name))->lower()->replace(' ', '-');

                try {
                    $mProduct = Magento::products()->create([
                        'sku'               => $sku,
                        'name'              => $name,
                        'price'             => $price,
                        'attribute_set_id'  => 4,
                        'status'            => 1,
                        'visibility'        => 4,
                        'type_id'           => 'simple',
                        'custom_attributes' => [
                            [
                                'attribute_code' => 'meta_title',
                                'value'          => $name,
                            ],
                            [
                                'attribute_code' => 'meta_keyword',
                                'value'          => $name,
                            ],
                            [
                                'attribute_code' => 'meta_description',
                                'value'          => $name,
                            ],
                            [
                                'attribute_code' => 'url_key',
                                'value'          => $url_key,
                            ],
                        ],
                    ]);

                    $product->options()->updateOrCreate([
                        'code' => 'magento_id',
                    ], [
                        'value' => $mProduct->id,
                    ]);

                    $product->magento = $mProduct;
                    $products->put($product->simbaId, $product);
                    // code...
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
        });
        $self->output->writeln("\r\n[i] Finished sync Magento products");

        return $products;
    }
}
