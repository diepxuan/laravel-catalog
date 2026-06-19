<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-18 20:13:00
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaMenuRouteMetadata;

final class SimbaDictionaryRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function dictionaries(): array
    {
        return app(SimbaMenuRouteMetadata::class)->dictionaries();
    }

    /**
     * @return null|array<string, mixed>
     */
    public static function get(string $routeName): ?array
    {
        return app(SimbaMenuRouteMetadata::class)->getDictionary($routeName);
    }
}
