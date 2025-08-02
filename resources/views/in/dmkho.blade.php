<div>
    <x-head-title>{{ 'Kho hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Danh sách kho hàng' }}
        </h2>
        <p>{{ 'Danh sách' }}</p>
    </x-slot>
    <div class="w-full overflow-x-auto">
        <pre class="whitespace-pre font-mono text-xs">{!! $inDmKhos !!}</pre>
    </div>

</div>
