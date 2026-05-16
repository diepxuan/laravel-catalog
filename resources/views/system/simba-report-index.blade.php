<div class="w-full">
    <x-head-title>{{ $metadata['spname'] ?: ($metadata['dll_name'] ?? $routeName) }} - Simba report</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $metadata['spname'] ?: ($metadata['dll_name'] ?? $routeName) }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ $routeName }} - {{ $metadata['module'] }} - menu {{ $metadata['menuid'] }} - mẫu {{ $metadata['ma_mau'] }} - {{ $metadata['rptname'] ?: ($metadata['source_table'] ?? 'report') }}
            </p>
        </div>
    </x-slot>

    <div class="space-y-3">
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            Báo cáo đã được mở theo menu active và thông tin mẫu từ simba-docs. Phần filter, tham số và render dữ liệu chỉ mở sau khi đối chiếu đầy đủ SP/function trong tài liệu.
        </div>

        <dl class="grid gap-2 rounded-lg border border-gray-200 bg-white p-4 text-sm md:grid-cols-2">
            <div>
                <dt class="font-medium text-gray-600">Stored procedure</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['spname'] ?: '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Report file</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['rptname'] ?: '-' }}</dd>
            </div>
            @if (($metadata['dll_name'] ?? '') || ($metadata['command'] ?? ''))
                <div>
                    <dt class="font-medium text-gray-600">DLL</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['dll_name'] ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-600">Command</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['command'] ?? '-' }}</dd>
                </div>
            @endif
            <div>
                <dt class="font-medium text-gray-600">Menu</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['menuid'] }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Mẫu</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['ma_mau'] }}</dd>
            </div>
            @if ($metadata['source_menuid'] ?? '')
                <div>
                    <dt class="font-medium text-gray-600">Source menu</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['source_menuid'] }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-600">Key</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['press_key_name'] ?? '-' }}</dd>
                </div>
            @endif
        </dl>

        @if ($metadata['description'] ?? '')
            <div class="rounded-lg border border-gray-200 bg-white p-4 text-sm text-gray-700">
                {{ $metadata['description'] }}
            </div>
        @endif
    </div>
</div>
