<div>
    <x-head-title>{{ 'Nhóm Hàng Hoá Vật Tư' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Nhóm Hàng Hoá Vật Tư' }}
        </h2>
        <p>{{ 'Danh sách' }}</p>
    </x-slot>
    <div class="w-full overflow-x-auto">
        <pre class="whitespace-pre font-mono text-xs">{!! $inDmVts !!}</pre>
    </div>

</div>
