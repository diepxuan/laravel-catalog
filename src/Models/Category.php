<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-14 22:42:36
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Models\Casts\CategoryMagento;
use Diepxuan\Catalog\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

#[ObservedBy([CategoryObserver::class])]
class Category extends AbstractModel
{
    public const ROOT        = 'PRODUCT';
    public const EVR         = 'EVR';
    public const TYPEDEFAULT = 'DEFAULT';
    public const TYPEEVR     = 'EVR';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'magento' => CategoryMagento::class,
    ];

    /**
     * Get the children Categories.
     */
    public function catChildrens(): HasMany
    {
        return $this->hasMany(self::class, 'parent', 'sku');
    }

    /**
     * Get the parent Category.
     */
    public function catParent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent', 'sku');
    }

    /**
     * Parent scope.
     *
     * @param mixed $query
     */
    public function scopeIsParent($query)
    {
        return $query->where('parent', '');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return $this->casts;
    }

    protected function Products(): HasMany
    {
        return $this->hasMany(Product::class, 'category', 'sku');
    }

    protected function urlKey(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->isRoot ? '' : Str::of(vn_convert_encoding($attributes['name']))->slug('-'),
        );
    }

    protected function isRoot(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => static::ROOT === $attributes['sku'],
        );
    }

    protected function urlPath(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->catParent ? ($this->catParent->isRoot ? "{$this->urlKey}" : "{$this->catParent->urlPath}/{$this->urlKey}") : '',
        );
    }

    protected function ids(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => array_merge($this->catParent ? $this->catParent->ids : [], Arr::wrap($this->magento->default)),
        );
    }
}
