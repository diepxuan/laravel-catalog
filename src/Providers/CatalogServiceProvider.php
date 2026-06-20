<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-20 15:54:52
 */

namespace Diepxuan\Catalog\Providers;

use Diepxuan\Catalog\Facades\CatalogFunctionsFacade;
use Diepxuan\Catalog\Facades\CatalogServiceFacade;
use Diepxuan\Catalog\Models\User;
use Diepxuan\Catalog\Services\CatalogFunctions;
use Diepxuan\Catalog\Services\CatalogService;
use Diepxuan\Catalog\Services\SimbaMetadataService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void {}

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        config([
            'auth.providers.users.model' => User::class,
        ]);

        $this->app->singleton('catalog-service', static fn ($app) => new CatalogService());
        $this->app->singleton('catalog-functions', static fn ($app) => new CatalogFunctions());
        $this->app->singleton(SimbaMetadataService::class, static fn ($app) => new SimbaMetadataService());
        AliasLoader::getInstance()->alias('CatalogService', CatalogServiceFacade::class);
        AliasLoader::getInstance()->alias('CatalogFunctions', CatalogFunctionsFacade::class);
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
