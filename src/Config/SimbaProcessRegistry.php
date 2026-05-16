<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:48
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;

final class SimbaProcessRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function processes(): array
    {
        $existingMenuIds = [];
        foreach (SimbaRouteRegistry::routesWithoutProcesses() as $metadata) {
            $existingMenuIds[$metadata['menuid']] = true;
        }

        $processes = [];
        foreach ((new SimbaDocsMenuRepository())->activeMenus() as $menu) {
            if (isset($existingMenuIds[$menu->menuid])) {
                continue;
            }

            if ('' === trim((string) $menu->dllName) && '' === trim((string) $menu->command) && '' === trim((string) $menu->code_name)) {
                continue;
            }

            $route = self::generatedRouteName((string) $menu->moduleid, (string) ($menu->dllName ?: $menu->code_name ?: $menu->menuid));
            while (isset($processes[$route])) {
                $route .= '-' . str_replace('.', '-', $menu->menuid);
            }

            $processes[$route] = [
                'module'    => (string) $menu->moduleid,
                'menuid'    => (string) $menu->menuid,
                'name'      => $menu->getDisplayName(),
                'type'      => (string) $menu->type,
                'dll_name'  => (string) $menu->dllName,
                'command'   => (string) $menu->command,
                'code_name' => (string) $menu->code_name,
            ];
        }

        return $processes;
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $routeName): ?array
    {
        return self::processes()[$routeName] ?? null;
    }

    private static function generatedRouteName(string $module, string $source): string
    {
        $slug = strtolower((string) preg_replace('/[^A-Za-z0-9]+/', '-', $source));
        $slug = trim($slug, '-');

        return strtolower($module) . '.process.' . ($slug ?: 'menu');
    }
}
