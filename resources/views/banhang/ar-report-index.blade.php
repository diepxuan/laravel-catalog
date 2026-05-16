<div class="ar-report-container w-full">
    <x-head-title>{{ $metadata['spname'] }} - Công nợ phải thu</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $metadata['spname'] }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ $routeName }} - menu {{ $metadata['menuid'] }} - mẫu {{ $metadata['ma_mau'] }} - {{ $metadata['rptname'] }}
            </p>
        </div>
    </x-slot>

    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        Báo cáo đã được gắn đúng menu/SP từ sysReportInfo. Phần filter và render dữ liệu sẽ chỉ được mở sau khi đối chiếu đủ tham số từ simba-docs cho từng SP.
    </div>
</div>
