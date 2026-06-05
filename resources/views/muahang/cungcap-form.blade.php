<div class="mx-auto max-w-4xl">
    <x-head-title>{{ 'Nhà cung cấp' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ 'Nhà cung cấp' }}</h2>
        <p class="text-sm text-gray-500">Theo Simba menu 10.90.22 / ARDMKH / frmARDMKH</p>
    </x-slot>

    <form wire:submit="save" class="mt-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <label class="block"><span class="text-sm font-medium text-gray-700">Mã NCC</span><input wire:model="ma_kh" @if($mode === 'edit') readonly @endif class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Tên nhà cung cấp</span><input wire:model="ten_kh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block md:col-span-2"><span class="text-sm font-medium text-gray-700">Địa chỉ</span><input wire:model="dia_chi" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Mã số thuế</span><input wire:model="ma_so_thue" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Điện thoại</span><input wire:model="dien_thoai" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Fax</span><input wire:model="fax" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Email</span><input wire:model="email" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Người giao dịch</span><input wire:model="nguoi_gd" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">TK công nợ</span><input wire:model="tk_cn" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">HTTT mua</span><input wire:model="ma_httt_po" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block md:col-span-2"><span class="text-sm font-medium text-gray-700">Ghi chú</span><textarea wire:model="ghi_chu" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm"></textarea></label>
        </div>
        @if ($errors->any())
            <div class="mt-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif
        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('po.cungcap') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</a>
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Lưu</button>
        </div>
    </form>
</div>
