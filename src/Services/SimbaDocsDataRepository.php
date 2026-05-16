<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:10
 */

namespace Diepxuan\Catalog\Services;

use Illuminate\Support\Collection;

/**
 * Reads markdown table exports from simba-docs/data.
 */
final class SimbaDocsDataRepository
{
    /**
     * @var array<string, Collection<int, array<string, string>>>
     */
    private array $cache = [];

    public function __construct(private readonly ?string $projectRoot = null) {}

    /**
     * @return Collection<int, array<string, string>>
     */
    public function table(string $fileName): Collection
    {
        if (isset($this->cache[$fileName])) {
            return $this->cache[$fileName];
        }

        return $this->cache[$fileName] = $this->readTable($this->dataPath($fileName));
    }

    private function dataPath(string $fileName): string
    {
        $relativePath = "simba-docs/data/{$fileName}";

        foreach ($this->candidateRoots() as $root) {
            $path = rtrim($root, '/') . "/{$relativePath}";
            if (is_file($path) && is_readable($path)) {
                return $path;
            }
        }

        return $this->rootPath() . "/{$relativePath}";
    }

    private function rootPath(): string
    {
        if (null !== $this->projectRoot) {
            return rtrim($this->projectRoot, '/');
        }

        if (\function_exists('base_path')) {
            try {
                return rtrim(base_path(), '/');
            } catch (\Throwable) {
                // Unit tests may load Laravel helpers without a full Application.
            }
        }

        return \dirname(__DIR__, 4);
    }

    /**
     * @return list<string>
     */
    private function candidateRoots(): array
    {
        $roots = [];

        if (null !== $this->projectRoot) {
            $roots[] = $this->projectRoot;
        }

        if (\function_exists('base_path')) {
            try {
                $roots[] = base_path();
            } catch (\Throwable) {
                // Unit tests may load Laravel helpers without a full Application.
            }
        }

        $roots[] = getcwd() ?: '';
        $roots[] = \dirname(__DIR__, 4);

        return array_values(array_unique(array_filter(array_map(
            static fn (string $root): string => rtrim($root, '/'),
            $roots,
        ))));
    }

    /**
     * @return Collection<int, array<string, string>>
     */
    private function readTable(string $path): Collection
    {
        if (!is_file($path) || !is_readable($path)) {
            return collect();
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if (false === $lines || \count($lines) < 3) {
            return collect();
        }

        $headers = $this->parseMarkdownRow($lines[0]);
        $rows    = collect();

        foreach (\array_slice($lines, 2) as $line) {
            if (!str_starts_with(trim($line), '|')) {
                continue;
            }

            $values = $this->parseMarkdownRow($line);
            if (\count($values) < \count($headers)) {
                $values = array_pad($values, \count($headers), '');
            }

            $rows->push(array_combine($headers, \array_slice($values, 0, \count($headers))));
        }

        return $rows;
    }

    /**
     * @return list<string>
     */
    private function parseMarkdownRow(string $line): array
    {
        $cells = explode('|', trim(trim($line), '|'));

        return array_map(
            static fn (string $cell): string => html_entity_decode(trim($cell), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            $cells,
        );
    }
}
