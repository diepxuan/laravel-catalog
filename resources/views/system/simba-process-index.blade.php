<div class="w-full">
    <x-head-title>{{ $metadata['name'] }} - Simba process</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $metadata['name'] }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ $routeName }} - {{ $metadata['module'] }} - menu {{ $metadata['menuid'] }} - type {{ $metadata['type'] }}
            </p>
        </div>
    </x-slot>

    <div class="space-y-3">
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            Menu này có DLL/command trong sysMenu nhưng chưa có metadata report, dictionary hoặc voucher đủ chuẩn. Portal chỉ mở shell tham chiếu; không execute, không ghi dữ liệu, không gọi SQL/SP cho đến khi audit đủ logic trong simba-docs.
        </div>

        <dl class="grid gap-2 rounded-lg border border-gray-200 bg-white p-4 text-sm md:grid-cols-2">
            <div>
                <dt class="font-medium text-gray-600">DLL</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['dll_name'] ?: '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Command</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['command'] ?: '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Code name</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['code_name'] ?: '-' }}</dd>
            </div>
        </dl>
    </div>
</div>
