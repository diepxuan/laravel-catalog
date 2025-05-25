<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-24 16:46:18
 */

namespace Diepxuan\Catalog\Providers;

use Diepxuan\Catalog\Commands\CatalogSync;
use Diepxuan\Catalog\Commands\EnvTest;
use Diepxuan\Catalog\Commands\Scavenger;
use Diepxuan\Catalog\Facades\CatalogServiceFacade;
use Diepxuan\Catalog\Models\Category;
use Diepxuan\Catalog\Models\Product;
use Diepxuan\Catalog\Models\User;
use Diepxuan\Catalog\Observers\CategoryObserver;
use Diepxuan\Catalog\Observers\ProductObserver;
use Diepxuan\Catalog\Services\CatalogService;
use Illuminate\Foundation\AliasLoader;
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
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         CatalogSync::class,
        //         EnvTest::class,
        //         Scavenger::class,
        //     ]);
        // }

        config([
            'auth.providers.users.model' => User::class,
        ]);

        $this->app->singleton('catalog-service', static fn ($app) => new CatalogService());
        AliasLoader::getInstance()->alias('CatalogService', CatalogServiceFacade::class);
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
