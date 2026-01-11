<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-11 16:03:06
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    protected $casts = [
        'order'     => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * Get the parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children menus.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Recursive children
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
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

    public function isSelfParent(?int $parentId): bool
    {
        return $this->exists && $parentId === $this->id;
    }

    public function isDescendantOf(int $parentId): bool
    {
        $parent = self::find($parentId);
        while ($parent) {
            if ($parent->id === $this->id) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
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
    }

    /**
     * Build full menu tree (optimized).
     */
    public static function tree(): Collection
    {
        $menus = self::withoutGlobalScopes()->get();

        return self::buildTree($menus);
    }

    /**
     * Get all menus with default menus merged.
     *
     * @return Collection<int, NavigationMenu>
     */
    public static function withDefaultMenus(): Collection
    {
        $menus   = static::all();
        $default = static::getDefaultMenus();

        $default->each(static function ($defaultMenu) use ($menus): void {
            if (!$menus->contains('route', $defaultMenu->route)) {
                $menus->push($defaultMenu);
            }
        });

        return $menus;
    }

    /**
     * Get the default menus.
     *
     * @return Collection<int, NavigationMenu>
     */
    public static function getDefaultMenus(): Collection
    {
        return collect([
            ['name' => 'Hệ thống', 'route' => 'system', 'order' => 999],
            ['name' => 'Menu', 'route' => 'system.menu', 'order' => 999],
        ])->mapInto(static::class);
    }

    /**
     * @param Collection<Menu> $menus
     */
    protected static function buildTree(Collection $menus, ?int $parentId = null): Collection
    {
        return $menus
            ->where('parent_id', $parentId)
            ->values()
            ->map(static function ($menu) use ($menus) {
                $menu->children = self::buildTree($menus, $menu->id);

                return $menu;
            })
        ;
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(static function ($model): void {
            $model->validateData();
        });

        static::updating(static function ($model): void {
            $model->validateData();
        });

        static::saving(static function (self $model): void {
            $model->validateData();
        });

        static::addGlobalScope('order', static function ($query): void {
            $query->orderBy('order');
        });
    }

    /**
     * Validate model data before saving.
     *
     * @throws ValidationException
     */
    protected function validateData(): void
    {
        $data = $this->getAttributes();

        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'order'     => ['nullable', 'integer', 'min:0'],
            'route'     => ['nullable', 'string', 'max:255'],
            'icon'      => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
        ];

        Validator::make($data, $rules)
            ->after(function ($validator) use ($data): void {
                if (
                    isset($data['parent_id'], $this->id)
                    && (int) $data['parent_id'] === (int) $this->id
                ) {
                    $validator->errors()->add(
                        'parent_id',
                        'Parent menu cannot be the menu itself.'
                    );
                }

                if (
                    isset($data['parent_id'], $this->id)
                    && $this->isDescendantOf($data['parent_id'])
                ) {
                    $validator->errors()->add(
                        'parent_id',
                        'Invalid menu hierarchy (loop detected).'
                    );
                }
            })->validate()
        ;

        // $rules = [
        //     'name'      => 'required|string|max:255',
        //     'order'     => 'nullable|integer|min:0',
        //     'route'     => 'nullable|string|max:255',
        //     'icon'      => 'nullable|string|max:255',
        //     'parent_id' => 'nullable|exists:menus,id|different:id',
        // ];

        // Validator::make($this->attributes, $rules)->validate();
    }
}
