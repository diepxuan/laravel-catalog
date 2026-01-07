<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-07 08:27:52
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

    /**
     * Get the menu tree with default menus merged.
     *
     * @return Collection<int, NavigationMenu>
     *
     * @deprecated use CatalogService::menuTree() instead
     */
    public static function getTree(): Collection
    {
        return collect([]);
        $menus = static::isParent()
            ->with('children.children')
            ->get()
        ;

        return $menus;
        static::getDefaultMenus()->each(static function ($default) use ($menus): void {
            $existing = $menus->firstWhere('route', $default->route);
            if (!$existing) {
                $menus->push($default);

                return;
            }
            ($default->children ?? collect())->each(static function ($defaultChildren) use ($existing): void {
                if (!$existing->children->contains('route', $defaultChildren->route)) {
                    $existing->children->push($defaultChildren);
                }
            });

            $existing->setRelation(
                'children',
                $existing->children->sortBy('order')->values()
            );
        });

        return $menus;
        // return $menus->merge(static::getDefaultMenus());
    }

    public static function getDefaultMenus(): Collection
    {
        $menus = collect([
            ['name' => 'Hệ thống', 'route' => 'system.*', 'order' => 999],
            ['name' => 'Menu', 'route' => 'system.menu', 'order' => 999],
        ])->mapInto(static::class);

        return collect([]);

        return $menus;
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order', static function ($query): void {
            $query->orderBy('order');
        });
    }
}
