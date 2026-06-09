<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-07 21:44:00
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaProcessMetadata;

final class SimbaProcessRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function processes(): array
    {
        return SimbaProcessMetadata::processes(SimbaRouteRegistry::routesWithoutProcesses());
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $routeName): ?array
    {
        return SimbaProcessMetadata::get($routeName, SimbaRouteRegistry::routesWithoutProcesses());
    }
}
