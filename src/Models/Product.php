<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-11 20:08:17
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\Product as SProduct;
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
     * The attributes that Intergrate to Simba.
     *
     * @var array
     */
    private SProduct $_simba;

    /**
     * Get the Options for the Product.
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * Get all of the models from the database.
     * Map with Simba.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function initIntergration()
    {
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        $products  = self::all();
        $sProducts = SProduct::all()->map(static function ($sProduct) use (&$products) {
            $id      = $sProduct->id ?: implode('_', [$sProduct->ma_cty, $sProduct->ma_vt]);
            $product = $products->firstWhere('simba_id', $id);

            if (!$product) {
                $product = self::updateOrCreate(
                    ['sku' => $sProduct->ma_vt],
                    [
                        'name'  => $sProduct->ten_vt,
                        'price' => 0,
                    ]
                );
            }

            $option = new ProductOption([
                'code'  => 'simba_id',
                'value' => $id,
            ]);

            $product->options()->save($option);
            $product->simba = $sProduct;
            $products->push($product);

            return $sProduct;
        });

        ini_set('max_execution_time', 30);
        set_time_limit(30);

        return $products;
    }

    /**
     * Interact with the Simba product.
     */
    protected function simba(): Attribute
    {
        $self = $this;

        return Attribute::make(
            get: static fn (SProduct $sProduct, array $attributes) => $self->_simba,
        );
    }

    /**
     * Get the Simba Product Id.
     */
    protected function simbaId(): Attribute
    {
        $self = $this;

        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => $self->options->first(static fn ($option) => 'simba_id' === $option->code) ?: new ProductOption(),
        );
    }
}
