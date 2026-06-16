<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-15 13:00:00
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Config\SimbaDictionaryRegistry;
use Diepxuan\Catalog\Config\SimbaReportRegistry;
use Diepxuan\Catalog\Config\SimbaRouteRegistry;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\Route;

class SimbaMenuTargetResolver
{
    private const SOURCE_TYPE_TO_SEGMENT = [
        SimbaRouteRegistry::TYPE_DICTIONARY => 'dict',
        SimbaRouteRegistry::TYPE_VOUCHER    => 'vch',
        SimbaRouteRegistry::TYPE_REPORT     => 'rpt',
        SimbaRouteRegistry::TYPE_CUSTOM     => 'proc',
    ];

    /**
     * @return array{routeName:string, metadata:array<string,string>, menuid:?string, url:string, component:?string, params:array<string,mixed>, title:string, sourceType:string}|null
     */
    public function resolvePath(?string $module = null, ?string $kind = null, ?string $slug = null): ?array
    {
        if (null === $module || '' === $module) {
            return null;
        }

        foreach ($this->candidateRouteNames($module, $kind, $slug) as $routeName) {
            $target = $this->resolveRouteName($routeName);
            if (null !== $target) {
                return $target;
            }
        }

        return null;
    }

    /**
     * @return array{routeName:string, metadata:array<string,string>, menuid:?string, url:string, component:?string, params:array<string,mixed>, title:string, sourceType:string}|null
     */
    public function resolveRouteName(string $routeName): ?array
    {
        $routes = SimbaRouteRegistry::routes();
        $metadata = $routes[$routeName] ?? null;
        if (null === $metadata) {
            return null;
        }

        $route = Route::getRoutes()->getByName($routeName);
        $metadata = $this->enrichMetadata($routeName, $metadata);

        return [
            'routeName'   => $routeName,
            'metadata'    => $metadata,
            'menuid'      => $metadata['menuid'] ?? null,
            'url'         => $this->urlForRouteName($routeName, $metadata),
            'component'   => $this->componentForRoute($route),
            'params'      => $this->paramsForRoute($route),
            'title'       => $this->titleFor($routeName, $metadata, $route),
            'sourceType'  => $metadata['source_type'] ?? SimbaRouteRegistry::TYPE_CUSTOM,
        ];
    }

    /**
     * Merge full metadata from the appropriate Simba* registry into the route's
     * base metadata (which only carries module/menuid/source_type). The merged
     * metadata drives the read-only "shell" view shown when a route has no
     * Livewire component yet.
     *
     * @param array<string, string> $metadata
     *
     * @return array<string, string>
     */
    private function enrichMetadata(string $routeName, array $metadata): array
    {
        $sourceType = $metadata['source_type'] ?? null;

        $extra = match ($sourceType) {
            SimbaRouteRegistry::TYPE_DICTIONARY => SimbaDictionaryRegistry::get($routeName) ?? [],
            SimbaRouteRegistry::TYPE_REPORT     => SimbaReportRegistry::get($routeName) ?? [],
            SimbaRouteRegistry::TYPE_CUSTOM     => $this->findProcessMetadata($routeName) ?? [],
            default                             => [],
        };

        if ([] === $extra) {
            return $metadata;
        }

        // Base metadata takes precedence so we never overwrite authoritative
        // module/menuid/source_type with registry values.
        return array_merge($extra, $metadata);
    }

    /**
     * @return array<string, string>|null
     */
    private function findProcessMetadata(string $routeName): ?array
    {
        foreach (SimbaProcessMetadata::processes() as $route => $metadata) {
            if ($route === $routeName) {
                /** @var array<string, string> $metadata */
                return $metadata;
            }
        }

        return null;
    }

    public function urlForRouteName(string $routeName, ?array $metadata = null): string
    {
        $metadata ??= SimbaRouteRegistry::routes()[$routeName] ?? [];
        $parts = explode('.', $routeName);
        $module = strtolower((string) ($metadata['module'] ?? $parts[0] ?? 'simba'));
        $sourceType = $metadata['source_type'] ?? null;
        $kind = self::SOURCE_TYPE_TO_SEGMENT[$sourceType] ?? ($parts[1] ?? 'proc');
        if (isset(self::SOURCE_TYPE_TO_SEGMENT[$sourceType])) {
            $slugParts = match ($sourceType) {
                SimbaRouteRegistry::TYPE_DICTIONARY => array_slice($parts, 1),
                SimbaRouteRegistry::TYPE_VOUCHER, SimbaRouteRegistry::TYPE_REPORT => array_slice($parts, 2) ?: array_slice($parts, 1),
                SimbaRouteRegistry::TYPE_CUSTOM => array_slice($parts, 1),
                default => array_slice($parts, 1),
            };
            $slug = implode('.', $slugParts);
        } else {
            $slug = implode('.', array_slice($parts, 1)) ?: $routeName;
        }

        return route('simba.show', [
            'module' => $module,
            'kind'   => $kind,
            'slug'   => $slug,
        ]);
    }

    /**
     * @return list<string>
     */
    private function candidateRouteNames(string $module, ?string $kind, ?string $slug): array
    {
        $module = strtolower($module);
        $kind   = null === $kind ? null : strtolower($kind);
        $slug   = null === $slug ? null : strtolower($slug);

        if (null === $kind || '' === $kind) {
            return [$module];
        }

        if (null === $slug || '' === $slug) {
            return ["{$module}.{$kind}"];
        }

        return match ($kind) {
            'dict' => ["{$module}.{$slug}", "{$module}.dict.{$slug}"],
            'vch'  => ["{$module}.vch.{$slug}", "{$module}.{$slug}"],
            'rpt'  => ["{$module}.rpt.{$slug}", "{$module}.{$slug}"],
            'proc' => ["{$module}.proc.{$slug}", "{$module}.process.{$slug}", "{$module}.{$slug}"],
            default => ["{$module}.{$kind}.{$slug}"],
        };
    }

    private function componentForRoute(?LaravelRoute $route): ?string
    {
        if (null === $route) {
            return null;
        }

        $action = $route->getActionName();
        if (!str_contains($action, '@') && class_exists($action)) {
            return $action;
        }

        return null;
    }

    /**
     * @return array<string,mixed>
     */
    private function paramsForRoute(?LaravelRoute $route): array
    {
        if (null === $route) {
            return [];
        }

        $defaults = $route->defaults;
        unset($defaults['controller'], $defaults['namespace'], $defaults['prefix'], $defaults['where'], $defaults['as']);

        return $defaults;
    }

    private function titleFor(string $routeName, array $metadata, ?LaravelRoute $route): string
    {
        $defaults = $route?->defaults ?? [];
        if (isset($defaults['title']) && '' !== $defaults['title']) {
            return (string) $defaults['title'];
        }

        return $metadata['title'] ?? $routeName;
    }
}
