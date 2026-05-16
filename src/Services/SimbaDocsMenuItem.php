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

/**
 * Immutable row object for SimbaERP menu rows loaded from simba-docs/data.
 */
final class SimbaDocsMenuItem
{
    public const TYPE_MODULE_ROOT = '5';
    public const TYPE_GROUP       = '';
    public const TYPE_VOUCHER     = '1';
    public const TYPE_MASTER      = '2';
    public const TYPE_REPORT      = '4';
    public const TYPE_UTILITY     = '3';
    public const TYPE_SETUP       = '6';

    /**
     * @param array<string, string> $attributes
     */
    public function __construct(private readonly array $attributes) {}

    public function __get(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function getParentMenuId(): ?string
    {
        $parts = explode('.', (string) $this->menuid);
        if (3 !== \count($parts)) {
            return null;
        }

        if ('00' !== $parts[2]) {
            return "{$parts[0]}.{$parts[1]}.00";
        }

        if ('00' !== $parts[1]) {
            return "{$parts[0]}.00.00";
        }

        return null;
    }

    public function getDisplayName(): string
    {
        return $this->bar ?: $this->short_name ?: $this->menuid ?: '';
    }

    public function isRoot(): bool
    {
        return self::TYPE_MODULE_ROOT === $this->type;
    }

    public function isGroup(): bool
    {
        return self::TYPE_GROUP === $this->type || null === $this->type;
    }

    public function isVoucher(): bool
    {
        return self::TYPE_VOUCHER === $this->type;
    }

    public function isMasterData(): bool
    {
        return self::TYPE_MASTER === $this->type;
    }

    public function isReport(): bool
    {
        return self::TYPE_REPORT === $this->type;
    }

    public function isSetup(): bool
    {
        return self::TYPE_SETUP === $this->type;
    }
}
