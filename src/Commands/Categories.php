<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-17 08:27:48
 */

namespace Diepxuan\Catalog\Commands;

use Diepxuan\Catalog\Models\Category;
use Diepxuan\Catalog\Models\Simba\SCategory;
use Illuminate\Console\Command;

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
        $this->output->writeln('[i] Starting import Simba categories');
        $sCategories = SCategory::all();
        $total       = $sCategories->count();
        $sCategories->map(function (SCategory $sCategory, int $index) use ($total) {
            $sCategory->category()->updateOrCreate([], [
                'sku'      => "{$sCategory->sku}",
                'name'     => "{$sCategory->name}",
                'parent'   => "{$sCategory->parent}",
                'urlKey'   => "{$sCategory->urlKey}",
                'simba_id' => "{$sCategory->id}",
            ]);
            $this->output->writeln("[<fg=green>✔</>] Imported <fg=green>{$sCategory->category->sku}</> ({$index}/{$total})");
            $this->output->writeln("    {$sCategory->category->name}");
            $this->output->writeln("    {$sCategory->category->magento_id}");

            return $sCategory;
        });

        Category::all()->map(static function (Category $category) {
            if ($category->sCategory) {
                return $category;
            }
            $category->delete();
            $this->output->writeln("[<fg=red>✘</>] Deleted <fg=red>{$category->sku}</>");
        });

        return;
        $self->output->writeln('[i] Deleting Magento categories are missing in Catalog');
        $mCategories = Magento::categories()->get()->whereNotIn('id', [1, 2, 1_953])->keyBy('id');
        $self->withProgressBar($mCategories, static function ($mCategory, $progressBar) use ($categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$mCategory->name} <- Magento");
            if ($categories->filter(static fn ($category) => $category->magento->default === $mCategory->id || $category->magento->everon === $mCategory->id)->isEmpty()) {
                try {
                    $mCategory->delete();
                } catch (\Throwable $th) {
                    $progressBar->setMessage(" {$mCategory->name} >< Magento");
                }
            }

            if ($categories->where('name', $mCategory->name)->isEmpty()) {
                try {
                    $mCategory->delete();
                } catch (\Throwable $th) {
                    $progressBar->setMessage(" {$mCategory->name} >< Magento");
                }

                return;
            }

            // $category = Category::updateOrCreate(
            //     ['name' => "{$mCategory->name}"],
            //     ['magento_id' => $mCategory->id]
            // );
            // $categories->put($category->id, $category);

            $progressBar->setMessage('');
        });
        $self->output->writeln("\r\n[i] Finished delete missing Magento categories");
    }
}
