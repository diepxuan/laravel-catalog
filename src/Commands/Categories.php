<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-16 14:40:40
 */

namespace Diepxuan\Catalog\Commands;

use Diepxuan\Catalog\Models\Category;
use Diepxuan\Catalog\Models\Simba\SCategory;
use Diepxuan\Magento\Magento;
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
        $sCategories = SCategory::all()->map(function (SCategory $sCategory) {
            $this->output->writeln("[i] Importing {$sCategory->sku}");
            $sCategory->category()->updateOrCreate([], [
                'sku'      => "{$sCategory->sku}",
                'name'     => "{$sCategory->name}",
                'parent'   => "{$sCategory->parent}",
                'urlKey'   => "{$sCategory->urlKey}",
                'simba_id' => "{$sCategory->id}",
            ]);

            return $sCategory;
        });

        return;
        $self   = $this;
        $format = ' %current%/%max% [%bar%] %percent:3s%% %message%';

        $self->output->writeln('[i] Loading all categories');
        $categories = Category::all()->keyBy('id');
        $self->output->writeln("[i] Finished load <fg=green>{$categories->count()}</> categories");

        $self->output->writeln('[i] Starting import Simba categories');
        $sCategories = SCategory::all();
        $self->withProgressBar($sCategories, static function ($sCategory, $progressBar) use ($categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$sCategory->sku} <- Simba");
            $catOptions = [
                'sku'    => "{$sCategory->sku}",
                'name'   => "{$sCategory->name}",
                'parent' => "{$sCategory->parent}",
                'urlKey' => "{$sCategory->urlKey}",
            ];
            $category = Category::updateOrCreate(
                ['simba_id' => "{$sCategory->id}"],
                $catOptions
            );

            $categories->put($category->id, $category);

            $progressBar->setMessage('');
        });
        $self->output->writeln("\r\n[i] Finished import Simba categories");

        $self->output->writeln('[i] Delete categories is missing from Simba');
        $self->withProgressBar($categories, static function ($category, $progressBar) use ($sCategories, $categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$category->sku} <- Simba");

            if ($sCategories->where('id', $category->simba_id)->isEmpty()) {
                $categories->pull($category->id);
                $category->delete();
            }

            $progressBar->setMessage('');
        });
        $self->output->writeln("\r\n[i] Finished delete missing categories");

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
