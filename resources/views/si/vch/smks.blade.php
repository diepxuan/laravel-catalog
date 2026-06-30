<div class="space-y-4">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 pb-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900">Khóa số liệu</h3>
            <p class="text-xs text-gray-500">SMKS · menu 90.30.05 · cập nhật qua assiUpd_ks.</p>
        </div>
        <button
            type="button"
            wire:click="loadData"
            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
        >
            Nạp lại
        </button>
    </div>

    @if ($statusMessage)
        <div class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
            {{ $statusMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit.prevent="updateKhoaSo" class="max-w-2xl space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="block text-sm">
                <span class="text-gray-600">Công ty</span>
                <input
                    type="text"
                    value="{{ trim($maCty . ' - ' . $tenCty, ' -') }}"
                    disabled
                    class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 text-sm text-gray-700 shadow-sm"
                />
            </label>

            <label class="block text-sm">
                <span class="text-gray-600">Năm tài chính</span>
                <input
                    type="text"
                    value="{{ $financialYear }}"
                    disabled
                    class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 text-sm text-gray-700 shadow-sm"
                />
            </label>

            <label class="block text-sm md:col-span-2">
                <span class="text-gray-600">Khóa sổ đến ngày</span>
                <x-catalog::input-date
                    wire:model="ngayKhoaSo"
                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                @error('ngayKhoaSo')
                    <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <div class="flex items-center gap-3 border-t border-gray-100 pt-4">
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="updateKhoaSo"
                class="rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-wait disabled:opacity-70"
            >
                <span wire:loading.remove wire:target="updateKhoaSo">Cập nhật</span>
                <span wire:loading wire:target="updateKhoaSo">Đang cập nhật...</span>
            </button>

            <x-catalog::action-message on="smks-updated" class="text-xs text-green-600">
                {{ 'Đã lưu' }}
            </x-catalog::action-message>
        </div>
    </form>
</div>
