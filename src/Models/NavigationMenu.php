<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-11 23:58:46
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NavigationMenu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'parent_id',
        'name',
        'order',
        'route',
        'icon',
        'simbaid',
    ];

    protected $casts = [
        'order'     => 'integer',
        'parent_id' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function scopeIsParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function hasSimbaLink(): bool
    {
        return Schema::hasColumn('menus', 'simbaid') && !empty($this->simbaid);
    }

    public function hasActiveRoute(): bool
    {
        return !empty($this->route) && 'space' !== $this->route && Route::has($this->route);
    }

    public function getStatus(): string
    {
        if (!$this->hasSimbaLink()) {
            return 'custom';
        }

        return $this->hasActiveRoute() ? 'active' : 'coming-soon';
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
     * Build full menu tree.
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
     * Fallback route: return raw value if empty/space,
     * otherwise validate route exists.
     */
    protected function route(): Attribute
    {
        return Attribute::make(
            get: static function (?string $value): ?string {
                $trimmed = trim((string) $value);
                if (empty($trimmed) || 'space' === $trimmed) {
                    return $value;
                }

                return Route::has($value) ? $value : 'home';
            },
        );
    }

    /**
     * @param Collection<Model> $menus
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

    protected static function booted(): void
    {
        // Single saving hook — covers both creating and updating
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
        $data              = $this->getAttributes();
        $data['parent_id'] = null !== $data['parent_id'] && '' !== $data['parent_id']
            ? (int) $data['parent_id']
            : null;

        Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'order'     => ['nullable', 'integer', 'min:0'],
            'route'     => ['nullable', 'string', 'max:255'],
            'icon'      => ['nullable', 'string', 'max:255'],
            'simbaid'   => ['nullable', 'string', 'max:20'],
            'parent_id' => ['nullable', 'exists:menus,id'],
        ])->after(function ($validator) use ($data): void {
            // Self-parent check
            if (isset($data['parent_id'], $this->id) && (int) $data['parent_id'] === (int) $this->id) {
                $validator->errors()->add('parent_id', 'Parent menu cannot be the menu itself.');
            }

            // Circular hierarchy check
            if (isset($data['parent_id'], $this->id) && $this->isDescendantOf($data['parent_id'])) {
                $validator->errors()->add('parent_id', 'Invalid menu hierarchy (loop detected).');
            }
        })->validate();
    }
}
