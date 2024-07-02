<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-07-02 17:08:11
 */

namespace Diepxuan\Catalog\Commands;

use Diepxuan\Catalog\Models\Product;
use Diepxuan\Catalog\Models\Simba\SProduct;
use Diepxuan\Magento\Magento;
use Diepxuan\Magento\Models\Product as MProduct;
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
        $sProducts = SProduct::all();
        $total     = $sProducts->count();
        $sProducts = $sProducts->map(function (SProduct $sProduct, int $index) use ($total) {
            $sProduct = SProduct::withQuantity()->where('ma_vt', $sProduct->sku)->get()->first();
            $status   = $sProduct->product ? $sProduct->product->status : false;
            $status   = $sProduct->status && $status && $sProduct->price > 0;
            $sProduct->product()->updateOrCreate([], [
                'sku'      => $sProduct->sku,
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
            // $this->output->writeln('    status [<fg=green>✓</>]');
            $this->output->writeln("    {$sProduct->product->name}");
            $this->output->writeln("    {$sProduct->product->quantity}");

            return $sProduct;
        });

        Product::all()->map(function (Product $product) {
            if ($product->sProduct) {
                return $product;
            }
            $product->delete();
            $this->output->writeln("[<fg=red>✘</>] Deleted <fg=red>{$product->sku}</>");
        });

        $mProducts = Magento::products()->get()->map(static function (MProduct $mProduct) use ($sProducts): void {
            if ($sProducts->filter(static fn ($sProduct) => $sProduct->sku === $mProduct->sku)->isEmpty()) {
                try {
                    $mProduct->delete();
                } catch (\Throwable $th) {
                }
            }
        });
    }
}
