<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:09
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Illuminate\View\View;
use Livewire\Component;

class TaskShell extends Component
{
    public string $title;
    public string $dll;
    public string $task;
    public string $note;

    public function mount(string $title, string $dll, string $task, string $note = 'Route shell đã mở theo task docs; chưa execute SP/write khi chưa có wrapper/payload thật trong source.'): void
    {
        $this->title = $title;
        $this->dll   = $dll;
        $this->task  = $task;
        $this->note  = $note;
    }

    public function render(): View
    {
        return view('catalog::system.task-shell')->layout('catalog::layouts.app');
    }
}
