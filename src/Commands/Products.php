<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-17 08:29:04
 */

namespace Diepxuan\Catalog\Commands;

use Diepxuan\Catalog\Models\Product;
use Diepxuan\Catalog\Models\Simba\SProduct;
use Illuminate\Console\Command;

class Products extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Intergration sync products';

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
        $this->output->writeln('[i] Starting import Simba products');

        $sProducts = SProduct::withQuantity()->get();
        $total     = $sProducts->count();
        $sProducts->map(function (SProduct $sProduct, int $index) use ($total) {
            $status = $sProduct->product ? $sProduct->product->status : false;
            $status = $sProduct->status && $status && $sProduct->price > 0;
            $sProduct->product()->updateOrCreate([], [
                'name'     => $sProduct->name,
                'price'    => $sProduct->price,
                'category' => $sProduct->category,
                'status'   => $status,
                'quantity' => (float) ($sProduct->quantity ?: 0),
            ]);
            if ($status) {
                $this->output->writeln("[<fg=green>✔</>] Imported <fg=green>{$sProduct->product->sku}</> ({$index}/{$total})");
            } else {
                $this->output->writeln("[<fg=green>✔</>] Imported <fg=red>{$sProduct->product->sku}</> ({$index}/{$total})");
            }
            // $this->output->writeln("    status [<fg=green>✓</>]");
            $this->output->writeln("    {$sProduct->product->name}");
            $this->output->writeln("    {$sProduct->product->quantity}");

            return $sProduct;
        });

        Product::all()->map(static function (Product $product) {
            if ($product->sProduct) {
                return $product;
            }
            $product->delete();
            $this->output->writeln("[<fg=red>✘</>] Deleted <fg=red>{$product->sku}</>");
        });

        return;
        $self   = $this;
        $format = ' %current%/%max% [%bar%] %percent:3s%% %message%';

        $self->output->writeln('[i] Loading all products');
        $products = Product::all()->keyBy('sku');
        $self->output->writeln(sprintf('[i] Loaded <fg=green>%s</> products.', $products->count()));

        $self->output->writeln('[i] Starting import Simba products');
        // $sProducts = SProduct::all()->keyBy('sku');
        $sProducts = SProduct::withQuantity()->get()->keyBy('sku');
        $self->withProgressBar($sProducts, static function ($sProduct, $progressBar) use ($products): void {
            $product = $products->get($sProduct->sku);
            if ($product) {
                $product->name     = $sProduct->name;
                $product->price    = $sProduct->price;
                $product->category = $sProduct->category;
                $product->status   = $sProduct->status && $product->status && $sProduct->price > 0;
                $product->quantity = (float) $sProduct->quantity;
                if ($product->isDirty()) {
                    $product->save();
                }
            } else {
                $product = Product::updateOrCreate(
                    ['sku' => $sProduct->sku],
                    [
                        'name'     => $sProduct->name,
                        'price'    => $sProduct->price,
                        'category' => $sProduct->category,
                        'status'   => $sProduct->status,
                        'quantity' => (float) ($sProduct->quantity ?: 0),
                    ]
                );
                $products->put($product->id, $products);
            }
            $progressBar->setMessage('');
        });
        $self->output->writeln("\r\n[i] Finished import Simba products");

        $self->output->writeln('[i] Deleting missing products from Simba');
        $self->withProgressBar($products, static function ($product, $progressBar) use ($sProducts, $products, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$product->sku}");

            if (!$sProducts->get($product->sku)) {
                $progressBar->setMessage(" {$product->sku} deleting");
                $products->pull($product->id);
                $product->delete();
            }

            $progressBar->setMessage('');
        });
        $self->output->writeln("\r\n[i] Finished delete missing products");
    }
}
