<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-13 18:22:54
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Magento\Magento;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

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
     * Get the Options for the Product.
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * Get the Simba Product Id.
     */
    protected function simbaId(): Attribute
    {
        $self = $this;

        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => ($self->options->first(static fn ($option) => 'simba_id' === $option->code) ?: new ProductOption())->value,
        );
    }

    /**
     * Get the Magento Product Id.
     */
    protected function magentoId(): Attribute
    {
        $self = $this;

        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => ($self->options->first(static fn ($option) => 'magento_id' === $option->code) ?: new ProductOption())->value,
        );
    }

    /**
     * Get the Product Category.
     */
    protected function category(): Attribute
    {
        $self = $this;

        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => ($self->options->first(static fn ($option) => 'category' === $option->code) ?: new ProductOption())->value,
        );
    }
}
