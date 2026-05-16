<div class="so-voucher-container w-full">
    <x-head-title>{{ $metadata['title'] }} - Bán hàng</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $metadata['title'] }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ $voucherCode }} - {{ $metadata['ph'] }}/{{ $metadata['ct'] }} - menu {{ $metadata['sys_menuid'] ?? $metadata['menuid'] }}
            </p>
        </div>
    </x-slot>

    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        Chứng từ đã được gắn đúng menu, bảng PH/CT và sysVoucherInfo. Danh sách, form thêm sửa xoá và payload lưu sẽ chỉ được mở sau khi đối chiếu đủ trường và stored procedure xử lý từ simba-docs.
    </div>
</div>
