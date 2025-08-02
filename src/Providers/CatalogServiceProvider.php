<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 19:15:39
 */

namespace Diepxuan\Catalog\Providers;

use Diepxuan\Catalog\Commands\CatalogSync;
use Diepxuan\Catalog\Commands\EnvTest;
use Diepxuan\Catalog\Commands\Scavenger;
use Diepxuan\Catalog\Connectors\SqlServerConnector;
use Diepxuan\Catalog\Facades\CatalogFunctionsFacade;
use Diepxuan\Catalog\Facades\CatalogServiceFacade;
use Diepxuan\Catalog\Models\Product;
use Diepxuan\Catalog\Models\User;
use Diepxuan\Catalog\Observers\ProductObserver;
use Diepxuan\Catalog\Services\CatalogFunctions;
use Diepxuan\Catalog\Services\CatalogService;
use Illuminate\Database\Connection;
use Illuminate\Database\SqlServerConnection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);

        // Đăng ký connector tùy chỉnh của chúng ta
        Connection::resolverFor('sqlsrv', static function ($connection, $database, $prefix, $config) {
            $connector = new SqlServerConnector();

            // \Debugbar::info($config);
            $pdo = $connector->connect($config);

            return new SqlServerConnection($pdo, $database, $prefix, $config);
        });
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
        $this->app->singleton('catalog-functions', static fn ($app) => new CatalogFunctions());
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
