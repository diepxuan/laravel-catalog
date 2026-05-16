<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:13
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\SimbaDictionaryRegistry;
use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class SimbaDictionaryRegistryTest extends TestCase
{
    public function testDictionaryRegistryMatchesSysDictionaryInfo(): void
    {
        $data = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $rows = $data->table('sysDictionaryInfo.md')->keyBy('code_name');

        foreach (SimbaDictionaryRegistry::dictionaries() as $route => $metadata) {
            self::assertTrue($rows->has($metadata['code_name']), "Missing dictionary {$route}");

            $source = $rows->get($metadata['code_name']);
            self::assertSame($metadata['table'], $source['table_name'], "Wrong table for {$route}");
            self::assertSame($metadata['source_menuid'] ?? $metadata['menuid'], $source['menuid'], "Wrong source menuid for {$route}");
            self::assertSame(implode(',', $metadata['pk']), $source['PK'], "Wrong PK for {$route}");
        }
    }

    public function testDictionaryFieldsComeFromCarryFieldList(): void
    {
        $data = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $rows = $data->table('sysDictionaryInfo.md')->keyBy('code_name');

        foreach (SimbaDictionaryRegistry::dictionaries() as $route => $metadata) {
            $sourceFields = array_values(array_filter(explode(',', strtolower($rows->get($metadata['code_name'])['carry_field_list']))));

            foreach ($metadata['fields'] as $field) {
                self::assertContains($field, $sourceFields, "Field {$field} is not in carry_field_list for {$route}");
            }
        }
    }

    public function testMenuCodeNameAliasesKeepSourceMenuId(): void
    {
        $data = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $rows = $data->table('sysDictionaryInfo.md')->keyBy('code_name');

        $aliases = array_filter(
            SimbaDictionaryRegistry::dictionaries(),
            static fn (array $metadata): bool => \array_key_exists('source_menuid', $metadata),
        );

        self::assertNotEmpty($aliases);

        foreach ($aliases as $route => $metadata) {
            $source = $rows->get($metadata['code_name']);

            self::assertNotNull($source, "Missing source dictionary for {$route}");
            self::assertSame($source['menuid'], $metadata['source_menuid'], "Wrong source menuid for {$route}");
            self::assertNotSame($metadata['menuid'], $metadata['source_menuid'], "Alias {$route} should expose a mismatched source menuid");
        }
    }
}
