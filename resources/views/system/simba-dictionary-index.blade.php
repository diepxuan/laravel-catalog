<div class="w-full">
    <x-head-title>{{ $metadata['code_name'] }} - Simba dictionary</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $metadata['code_name'] }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ $routeName }} - {{ $metadata['table'] }} - menu {{ $metadata['menuid'] }}
                @if ($metadata['source_menuid'] ?? '')
                    - source {{ $metadata['source_menuid'] ?: 'blank' }}
                @endif
            </p>
        </div>
    </x-slot>

    <div class="space-y-3">
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            Danh mục đã được gắn theo menu active, khóa và bảng từ sysDictionaryInfo. Form nhập liệu chỉ mở sau khi đối chiếu đủ payload, lookup và quyền sửa/xóa trong simba-docs.
        </div>

        <dl class="grid gap-2 rounded-lg border border-gray-200 bg-white p-4 text-sm md:grid-cols-2">
            <div>
                <dt class="font-medium text-gray-600">Code name</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['code_name'] }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Table</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['table'] }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Primary key</dt>
                <dd class="font-mono text-gray-900">{{ implode(', ', $metadata['pk']) }}</dd>
            </div>
            @if ($metadata['source_menuid'] ?? '')
                <div>
                    <dt class="font-medium text-gray-600">Source menu</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['source_menuid'] ?: 'blank' }}</dd>
                </div>
            @endif
            <div>
                <dt class="font-medium text-gray-600">Carry fields</dt>
                <dd class="font-mono text-gray-900">{{ implode(', ', $metadata['fields']) ?: '-' }}</dd>
            </div>
        </dl>
    </div>
</div>
