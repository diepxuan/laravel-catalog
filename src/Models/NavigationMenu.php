<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-12 11:59:04
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NavigationMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'order',
        'route',
        'icon',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Scope a query to get parents only.
     *
     * @var array
     *
     * @param mixed $query
     */
    public function scopeIsParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public static function getTree(): Collection
    {
        $menus = static::isParent()
            ->with('children.children')
            ->get()
        ;

        static::getDefaultMenus()->each(static function ($default) use ($menus): void {
            if (!$menus->contains('route', $default->route)) {
                $menus->push($default);
            }
        });

        return $menus;
        // return $menus->merge(static::getDefaultMenus());
    }

    public static function getDefaultMenus(): Collection
    {
        $menuSystem = new static([
            'name'  => 'Hệ thống',
            'route' => 'system.*',
            'order' => 999,
        ]);

        $menuSystem->setRelation('children', collect([
            new static([
                'name'  => 'Menu',
                'route' => 'system.menu',
                'order' => 0,
            ]),
        ]));

        return collect([$menuSystem]);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order', static function ($query): void {
            $query->orderBy('order');
        });
    }
}
