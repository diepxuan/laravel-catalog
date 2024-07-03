<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-07-03 11:10:43
 */

namespace Diepxuan\Catalog\Providers;

use Diepxuan\Catalog\Commands\CatalogSync;
use Diepxuan\Catalog\Commands\EnvTest;
use Diepxuan\Catalog\Models\Category;
use Diepxuan\Catalog\Models\Product;
use Diepxuan\Catalog\Observers\CategoryObserver;
use Diepxuan\Catalog\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        if ($this->app->runningInConsole()) {
            $this->commands([
                CatalogSync::class,
                EnvTest::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [];
    }
}
