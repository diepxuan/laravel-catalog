<div class="space-y-4">
    <div class="flex items-center justify-between border-b border-gray-200 pb-3">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">SimbaERP</h1>
            <p class="text-xs text-gray-500">
                @if ($target)
                    {{ $target['routeName'] }} · {{ $target['menuid'] ?? 'no-menuid' }} · {{ $target['sourceType'] }}
                @else
                    Chọn chức năng từ menu SimbaERP.
                @endif
            </p>
        </div>
        @if ($target)
            <span class="rounded bg-blue-50 px-2 py-1 font-mono text-xs text-blue-700 ring-1 ring-inset ring-blue-200">
                /simba/{{ $module }}/{{ $kind }}/{{ $slug }}
            </span>
        @endif
    </div>

    <div class="flex gap-4">
        <aside class="w-[360px] flex-shrink-0">
            @livewire('catalog::system.simba-erp-menus')
        </aside>

        <section class="min-w-0 flex-1 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            @if (! $target)
                <div class="flex min-h-[420px] flex-col items-center justify-center text-center text-gray-500">
                    <div class="text-lg font-semibold text-gray-700">SimbaERP Portal</div>
                    <p class="mt-2 text-sm">Sử dụng menu bên trái để mở chức năng theo URL chuẩn /simba/&lt;module&gt;/&lt;type&gt;/&lt;code&gt;.</p>
                </div>
            @elseif (empty($target['component']))
                <div class="space-y-3">
                    <div class="rounded border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        Route <span class="font-mono">{{ $target['routeName'] }}</span> chưa có Livewire component.
                        Portal mở shell tham chiếu metadata từ simba-docs để hỗ trợ audit.
                    </div>
                    @include('catalog::system.simba-page-metadata', [
                        'routeName' => $target['routeName'],
                        'sourceType' => $target['sourceType'],
                        'metadata' => $target['metadata'],
                    ])
                </div>
            @else
                @livewire($target['component'], $target['params'], key('simba-page-'.$target['routeName']))
            @endif
        </section>
    </div>
</div>
