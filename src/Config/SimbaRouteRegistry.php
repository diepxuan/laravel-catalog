<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-18 20:14:00
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaMenuRouteMetadata;

final class SimbaRouteRegistry
{
    public const TYPE_DICTIONARY = 'dictionary';
    public const TYPE_VOUCHER    = 'voucher';
    public const TYPE_REPORT     = 'report';
    public const TYPE_CUSTOM     = 'custom';

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function routes(): array
    {
        return app(SimbaMenuRouteMetadata::class)->routes();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function routesWithoutProcesses(): array
    {
        return app(SimbaMenuRouteMetadata::class)->routesWithoutProcesses();
    }

    /**
     * @return null|array<string, mixed>
     */
    public static function get(string $routeName): ?array
    {
        return app(SimbaMenuRouteMetadata::class)->getRoute($routeName);
    }

    /**
     * Reverse lookup: menuid → route name.
     *
     * @return array<string, string>
     */
    public static function menuidToRouteMap(): array
    {
        $map = [];
        foreach (self::routes() as $routeName => $metadata) {
            if (isset($metadata['menuid'])) {
                $map[(string) $metadata['menuid']] = $routeName;
            }
        }

        return $map;
    }
}
