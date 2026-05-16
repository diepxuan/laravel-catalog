<div class="si-dictionary-container w-full">
    <x-head-title>{{ $metadata['code_name'] }} - Hệ thống</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $metadata['code_name'] }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ $routeName }} - {{ $metadata['table'] }} - menu {{ $metadata['menuid'] }}
            </p>
        </div>
    </x-slot>

    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        Danh mục đã được gắn đúng menu/table/PK từ sysDictionaryInfo. Phần danh sách và form thêm sửa xoá sẽ chỉ được mở sau khi đối chiếu đủ stored procedure và payload field cho từng danh mục.
    </div>
</div>
