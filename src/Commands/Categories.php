<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-16 08:41:31
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
        $categories = Category::all()->keyBy('id');
        $self->output->writeln("\r\n[i] Finished load all categories");

        $self->output->writeln('[i] Starting import Simba categories');
        $sCategories = SCategory::all();
        $self->withProgressBar($sCategories, static function ($sCategory, $progressBar) use ($categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$sCategory->sku} <- Simba");
            $progressBar->advance();
            $catOptions = [
                'sku'    => "{$sCategory->sku}",
                'name'   => "{$sCategory->name}",
                'parent' => "{$sCategory->parent}",
                'urlKey' => "{$sCategory->urlKey}",
            ];
            if ('PRODUCT' === $sCategory->sku) {
                $catOptions['magento_id'] = 2;
            }
            $category = Category::updateOrCreate(
                ['simba_id' => "{$sCategory->id}"],
                $catOptions
            );

            $categories->put($category->id, $category);

            $progressBar->setMessage('');
            $progressBar->advance();
        });
        $self->output->writeln("\r\n[i] Finished import Simba categories");

        $self->output->writeln('[i] Delete categories is missing from Simba');
        $self->withProgressBar($categories, static function ($category, $progressBar) use ($sCategories, $categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$category->sku} <- Simba");
            $progressBar->advance();

            if ($sCategories->where('id', $category->simba_id)->isEmpty()) {
                $categories->pull($category->id);
                $category->delete();
            }

            $progressBar->setMessage('');
            $progressBar->advance();
        });
        $self->output->writeln("\r\n[i] Finished delete missing categories");

        $self->output->writeln('[i] Deleting Magento categories are missing in Catalog');
        $mCategories = Magento::categories()->get()->whereNotIn('id', [1, 2])->keyBy('id');
        $self->withProgressBar($mCategories, static function ($mCategory, $progressBar) use ($categories, $format): void {
            $progressBar->setFormat($format);
            $progressBar->setMessage(" {$mCategory->name} <- Magento");
            $progressBar->advance();
            if ($categories->where('magento_id', $mCategory->id)->isEmpty()) {
                try {
                    $mCategory->delete();
                } catch (\Throwable $th) {
                    $progressBar->setMessage(" {$mCategory->name} >< Magento");
                    $progressBar->advance();
                }
            } elseif ($categories->where('name', $mCategory->name)->isEmpty()) {
                try {
                    $mCategory->delete();
                } catch (\Throwable $th) {
                    $progressBar->setMessage(" {$mCategory->name} >< Magento");
                    $progressBar->advance();
                }
            } else {
                $category = Category::updateOrCreate(
                    ['name' => "{$mCategory->name}"],
                    ['magento_id' => $mCategory->id]
                );
                $categories->put($category->id, $category);
            }

            $progressBar->setMessage('');
            $progressBar->advance();
            // return $mCategory;
        });
        $self->output->writeln("\r\n[i] Finished delete missing Magento categories");

        $self->output->writeln('[i] Starting sync Magento categories');
        $self->withProgressBar($categories->where('sku', '!=', 'PRODUCT'), static function ($category, $progressBar) use (&$categories, $format): void {
            $progressBar->setFormat($format);
            $sku       = $category->sku;
            $parent_id = $category->parent ? $category->catParent->magento_id : 2;
            $name      = $category->name;
            $urlKey    = Str::of(vn_convert_encoding($name))->lower()->replace(' ', '-');

            $progressBar->setMessage(" {$sku} -> Magento");
            $progressBar->advance();

            try {
                $mCategory = Magento::categories()->create([
                    'name'      => $name,
                    'is_active' => true,
                    'parent_id' => $parent_id,
                    // 'include_in_menu'   => true,
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
                            'value'          => $urlKey,
                        ],
                        [
                            'attribute_code' => 'url_path',
                            'value'          => $urlKey,
                        ],
                        [
                            'attribute_code' => 'meta_title',
                            'value'          => $name,
                        ],
                    ],
                ]);

                $category->magento_id = $mCategory->id;
                $category->save();

                $category->magento = $mCategory;
                $categories->put("{$mCategory->id}", $category);
            } catch (\Throwable $th) {
                $progressBar->setMessage(" {$sku} >< Magento");
                $progressBar->advance();
            }

            $progressBar->setMessage('');
            $progressBar->advance();
        });
        $self->output->writeln("\r\n[i] Finished sync Magento categories");
    }
}
