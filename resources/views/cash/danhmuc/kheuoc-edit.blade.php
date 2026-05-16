<div class="w-full">
    <x-head-title>{{ 'Khế ước - ' . ('create' === $mode ? 'Thêm' : 'Sửa') }}</x-head-title>
    <x-slot name="header"><div class="flex items-center justify-between"><h2 class="text-xl font-semibold text-gray-800">{{ 'create' === $mode ? 'Thêm khế ước' : 'Sửa khế ước' }}</h2><a href="{{ route('ca.kheuoc') }}" class="rounded-md bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600">← Quay lại</a></div></x-slot>
    <div class="mt-6 max-w-5xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form wire:submit="submit" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div><label class="block text-sm font-medium">Mã KU *</label><input wire:model="pMa_ku" {{ 'edit' === $mode ? 'readonly' : '' }} class="mt-1 w-full rounded-md border-gray-300 text-sm {{ 'edit' === $mode ? 'bg-gray-100' : '' }}" />@error('pMa_ku')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium">Tên KU *</label><input wire:model="pTen_ku" class="mt-1 w-full rounded-md border-gray-300 text-sm" />@error('pTen_ku')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium">Số KU *</label><input wire:model="pSo_ku" class="mt-1 w-full rounded-md border-gray-300 text-sm" />@error('pSo_ku')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium">Ngày vay *</label><input type="date" wire:model="pNgay_vay" class="mt-1 w-full rounded-md border-gray-300 text-sm" />@error('pNgay_vay')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium">Ngày ký</label><input type="date" wire:model="pNgay_ky" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Ngày đáo hạn</label><input type="date" wire:model="pNgay_dh" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Thời hạn</label><input type="number" wire:model="pThoi_han" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Tính theo</label><select wire:model="pTinh_theo" class="mt-1 w-full rounded-md border-gray-300 text-sm"><option value="1">Tháng</option><option value="2">Năm</option></select></div>
            <div><label class="block text-sm font-medium">Phương pháp</label><select wire:model="pPhuong_phap" class="mt-1 w-full rounded-md border-gray-300 text-sm"><option value="1">Dư nợ giảm dần</option><option value="2">Lãi kép đều</option><option value="3">Lãi đơn đều</option><option value="4">Dư nợ giảm dần hàng tháng</option></select></div>
            <div><label class="block text-sm font-medium">Mã HĐ</label><input wire:model="pMa_hd" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Ngoại tệ</label><input wire:model="pMa_nt" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Tiền</label><input type="number" step="0.01" wire:model="pTien" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Tiền NT</label><input type="number" step="0.01" wire:model="pTien_nt" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Lãi suất</label><input type="number" step="0.0001" wire:model="pLai_suat" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">Lãi quá hạn</label><input type="number" step="0.0001" wire:model="pLs_qh" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">TK vay</label><input wire:model="pTk_vay" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">TK chi phí</label><input wire:model="pTk_cp" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium">TK CP phân bổ</label><input wire:model="pTk_cp_pt" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            <div class="md:col-span-3"><label class="block text-sm font-medium">Ghi chú</label><textarea wire:model="pGhi_chu" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea></div>
            <div class="md:col-span-3 flex items-center"><input id="pKsd" type="checkbox" wire:model="pKsd" class="h-4 w-4 rounded border-gray-300 text-blue-600" /><label for="pKsd" class="ml-2 text-sm">Khóa sử dụng</label></div>
            <div class="md:col-span-3 flex justify-end gap-3"><a href="{{ route('ca.kheuoc') }}" class="rounded-md bg-white px-4 py-2 text-sm text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">Hủy</a><button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Lưu</button></div>
        </form>
    </div>
</div>
