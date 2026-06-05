<div class="mx-auto max-w-6xl">
    <x-head-title>{{ 'Nhân viên' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ 'Nhân viên' }}</h2>
        <p class="text-sm text-gray-500">Theo Simba menu 04.90.05 / ARDMKH / frmARDMKH</p>
    </x-slot>

    <form wire:submit="save" class="mt-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium text-gray-700">Mã NV <span class="text-red-500">*</span></span>
                <input wire:model="ma_kh" @if($mode === 'edit') readonly @endif class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm @if($mode === 'edit') bg-gray-100 @endif" />
            </label>
            <label class="block">
                <span class="text-sm font-medium text-gray-700">Tên nhân viên <span class="text-red-500">*</span></span>
                <input wire:model="ten_kh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" />
            </label>
            <label class="block md:col-span-2"><span class="text-sm font-medium text-gray-700">Địa chỉ</span><input wire:model="dia_chi" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Mã số thuế</span><input wire:model="ma_so_thue" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            <label class="block"><span class="text-sm font-medium text-gray-700">Người giao dịch</span><input wire:model="nguoi_gd" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Liên hệ</h3>
            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2">
                <label class="block"><span class="text-sm font-medium text-gray-700">Điện thoại</span><input wire:model="dien_thoai" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Fax</span><input wire:model="fax" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Email</span><input type="email" wire:model="email" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Website</span><input wire:model="home_page" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Thanh toán và công nợ</h3>
            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <label class="block"><span class="text-sm font-medium text-gray-700">HTTT bán</span><input wire:model="ma_httt" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">HTTT mua</span><input wire:model="ma_httt_po" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">TK công nợ</span><input wire:model="tk_cn" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Giới hạn nợ</span><input type="number" step="0.01" wire:model="gh_no" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Hạn thanh toán</span><input type="number" step="0.01" wire:model="han_tt" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Điều khoản TT</span><input wire:model="ma_tt" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Hạn chiết khấu</span><input type="number" step="0.01" wire:model="han_ck" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Tỷ lệ chiết khấu</span><input type="number" step="0.01" wire:model="tl_ck" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Lãi suất quá hạn</span><input type="number" step="0.01" wire:model="ls_qh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Ngân hàng</h3>
            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <label class="block"><span class="text-sm font-medium text-gray-700">Mã ngân hàng</span><input wire:model="ma_ngh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Tên ngân hàng</span><input wire:model="ten_nh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Số TK ngân hàng</span><input wire:model="so_tk_nh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Chi nhánh NH</span><input wire:model="cn_nh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Tỉnh/TP ngân hàng</span><input wire:model="tinh_tp_nh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Phân loại</h3>
            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <label class="block"><span class="text-sm font-medium text-gray-700">Phân loại 1</span><input wire:model="ma_plkh1" maxlength="8" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Phân loại 2</span><input wire:model="ma_plkh2" maxlength="8" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Phân loại 3</span><input wire:model="ma_plkh3" maxlength="8" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
                <label class="block"><span class="text-sm font-medium text-gray-700">Nhóm KH</span><input wire:model="ma_nhkh" maxlength="8" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm" /></label>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Loại đối tượng và trạng thái</h3>
            <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5">
                <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" wire:model="isKh" class="rounded border-gray-300 text-blue-600" /> Là khách hàng</label>
                <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" wire:model="isNcc" class="rounded border-gray-300 text-blue-600" /> Là nhà cung cấp</label>
                <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" wire:model="isNv" class="rounded border-gray-300 text-blue-600" /> Là nhân viên</label>
                <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" wire:model="tinh_dt_nb" class="rounded border-gray-300 text-blue-600" /> Tính DT nội bộ</label>
                <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" wire:model="ksd" class="rounded border-gray-300 text-blue-600" /> Khóa sử dụng</label>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4">
            <label class="block"><span class="text-sm font-medium text-gray-700">Ghi chú</span><textarea wire:model="ghi_chu" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm"></textarea></label>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif
        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('ca.nhanvien') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</a>
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Lưu</button>
        </div>
    </form>
</div>
