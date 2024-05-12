<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-12 16:11:16
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
        ini_set('max_execution_time', '3000');
        $products = self::all()->keyBy('simbaId');

        $sProducts = SProduct::all()->keyBy('id')->map(static function ($sProduct, $id) use (&$products) {
            $product = $products->get($id, static function () use ($sProduct) {
                $prod = Product::updateOrCreate(
                    ['sku' => $sProduct->ma_vt],
                    [
                        'name'  => $sProduct->ten_vt,
                        'price' => 0,
                    ]
                );
                $prod->options()->updateOrCreate([
                    'code' => 'simba_id',
                ], [
                    'value' => $sProduct->id,
                ]);

                return $prod;
            });

            $product->simba = $sProduct;
            $products->put($id, $product);

            return $sProduct;
        });

        ini_set('max_execution_time', '30');

        return $products;
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
}
