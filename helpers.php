<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-03 22:30:54
 */

use Diepxuan\Catalog\Services\CatalogFunctions;
use Diepxuan\Catalog\Services\CatalogService;

if (!function_exists('catalog')) {
    /**
     * Get the catalog service instance.
     */
    function catalog(): CatalogService
    {
        return app('catalog-service');
    }
}
if (!function_exists('catalog_functions')) {
    /**
     * Get the catalog functions instance.
     */
    function catalog_functions(): CatalogFunctions
    {
        return app('catalog-functions');
    }
}

if (!function_exists('dvite')) {
    /**
     * Convert Vietnamese encoding to another.
     *
     * @param mixed $string
     * @param mixed $from
     * @param mixed $to
     */
    function dvite(string $string, $from = 'unicode', $to = 'ascii'): string
    {
        // try {
        //     return (string) new VnEncoding($string, $from, $to);
        // } catch (Throwable $th) {
        //     return '';
        // }
    }
}
