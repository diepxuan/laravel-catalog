<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-18 20:12:00
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaMenuRouteMetadata;

final class SimbaProcessRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function processes(): array
    {
        return app(SimbaMenuRouteMetadata::class)->processes();
    }

    /**
     * @return null|array<string, mixed>
     */
    public static function get(string $routeName): ?array
    {
        return app(SimbaMenuRouteMetadata::class)->getProcess($routeName);
    }
}
