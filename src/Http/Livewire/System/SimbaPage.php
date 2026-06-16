<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-16 08:47:00
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Services\SimbaMenuTargetResolver;
use Illuminate\View\View;
use Livewire\Component;

class SimbaPage extends Component
{
    public ?string $module = null;
    public ?string $kind   = null;
    public ?string $slug   = null;

    /** @var array<string,mixed>|null */
    public ?array $target = null;

    protected SimbaMenuTargetResolver $resolver;

    public function boot(SimbaMenuTargetResolver $resolver): void
    {
        $this->resolver = $resolver;
    }

    public function mount(?string $module = null, ?string $kind = null, ?string $slug = null): void
    {
        $this->module = $module;
        $this->kind   = $kind;
        $this->slug   = $slug;
        $this->target = $this->resolver->resolvePath($module, $kind, $slug);

        if (null !== $module && null === $this->target) {
            abort(404);
        }
    }

    public function render(): View
    {
        return view('catalog::system.simba-page')->layout('catalog::layouts.app');
    }
}
