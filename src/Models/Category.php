<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-28 23:45:08
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[ObservedBy([CategoryObserver::class])]
class Category extends Model
{
    use HasFactory;

    public const ROOT = 'PRODUCT';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
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
            get: fn (mixed $value, array $attributes) => $this->isRoot ?
                [$this->magento_id] :
                array_merge($this->catParent->ids, [$this->magento_id]),
        );
    }
}
