<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-14 17:15:55
 */

namespace Diepxuan\Catalog\Commands;

use Diepxuan\Catalog\Models\Category;
use Diepxuan\Magento\Magento;
use Diepxuan\Simba\Models\Category as SCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Categories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Intergration sync Categories';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->productIntergration();
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
        $self   = $this;
        $format = ' %current%/%max% [%bar%] %percent:3s%% %message%';

        $self->output->writeln('[i] Loading all categories');
        $categories = Category::all();
        $self->output->writeln(sprintf('[i] Loaded <fg=green>%s</> categories.', $categories->count()));

        $self->output->writeln('[i] Starting import Simba categories');
        $self->withProgressBar(SCategory::all(), static function ($sCategory, $progressBar) use ($categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$sCategory->sku} <- Simba");
            $progressBar->advance();
            $category = Category::updateOrCreate(
                ['simba_id' => "{$sCategory->id}"],
                [
                    'sku'    => "{$sCategory->sku}",
                    'name'   => "{$sCategory->name}",
                    'parent' => "{$sCategory->parent}",
                    'urlKey' => "{$sCategory->urlKey}",
                ]
            );

            $categories->put($category->id, $category);

            $progressBar->setMessage('');
            $progressBar->advance();
            // return $sCategory;
        });
        $self->output->writeln("\r\n[i] Finished import Simba categories");

        // $self->output->writeln('[i] Starting import Magento products');
        // $mProducts = Magento::products()->get();
        // $self->withProgressBar($mProducts, static function ($mProduct, $progressBar) use (&$products, $format): void {
        //     $progressBar->setFormat($format);
        //     $progressBar->setMessage(" {$mProduct->sku} <- Magento");
        //     $progressBar->advance();
        //     $id      = $mProduct->sku;
        //     $product = $products->get("001_{$id}", static function () use ($mProduct) {
        //         $prod = Product::updateOrCreate(
        //             ['sku' => $mProduct->sku],
        //             [
        //                 'name'  => $mProduct->name,
        //                 'price' => $mProduct->price,
        //             ]
        //         );
        //         $prod->options()->updateOrCreate([
        //             'code' => 'magento_id',
        //         ], [
        //             'value' => $mProduct->id,
        //         ]);

        //         return $prod;
        //     });

        //     if ($product->magentoId !== $mProduct->id) {
        //         $product->options()->updateOrCreate([
        //             'code' => 'magento_id',
        //         ], [
        //             'value' => $mProduct->id,
        //         ]);
        //     }

        //     $product->magento = $mProduct;
        //     $products->put("001_{$id}", $product);

        //     $progressBar->setMessage('');
        //     $progressBar->advance();
        //     // return $mProduct;
        // });
        // $self->output->writeln("\r\n[i] Finished import Magento products");

        // $self->output->writeln('[i] Starting sync Magento products');
        // $self->withProgressBar($products->whereNull('magentoId'), static function ($product, $progressBar) use ($products, $format): void {
        //     $progressBar->setFormat($format);
        //     if (!$product->magentoId) {
        //         $sku     = $product->sku;
        //         $name    = $product->name;
        //         $price   = $product->price;
        //         $url_key = Str::of(vn_convert_encoding($name))->lower()->replace(' ', '-');

        //         $progressBar->setMessage(" {$sku} -> Magento");
        //         $progressBar->advance();

        //         try {
        //             $mProduct = Magento::products()->create([
        //                 'sku'               => $sku,
        //                 'name'              => $name,
        //                 'price'             => $price,
        //                 'attribute_set_id'  => 4,
        //                 'status'            => 1,
        //                 'visibility'        => 4,
        //                 'type_id'           => 'simple',
        //                 'custom_attributes' => [
        //                     [
        //                         'attribute_code' => 'meta_title',
        //                         'value'          => $name,
        //                     ],
        //                     [
        //                         'attribute_code' => 'meta_keyword',
        //                         'value'          => $name,
        //                     ],
        //                     [
        //                         'attribute_code' => 'meta_description',
        //                         'value'          => $name,
        //                     ],
        //                     [
        //                         'attribute_code' => 'url_key',
        //                         'value'          => $url_key,
        //                     ],
        //                 ],
        //             ]);

        //             $product->options()->updateOrCreate([
        //                 'code' => 'magento_id',
        //             ], [
        //                 'value' => $mProduct->id,
        //             ]);

        //             $product->magento = $mProduct;
        //             $products->put("001_{$mProduct->sku}", $product);
        //         } catch (\Throwable $th) {
        //             $progressBar->setMessage(" {$sku} >< Magento");
        //             $progressBar->advance();
        //         }
        //     }

        //     $progressBar->setMessage('');
        //     $progressBar->advance();
        // });
        // $self->output->writeln("\r\n[i] Finished sync Magento products");

        return $categories;
    }
}
