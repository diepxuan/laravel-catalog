<div class="space-y-3">
    @if ($errorMessage)
        <div class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    @if ($statusMessage)
        <div class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
            {{ $statusMessage }}
        </div>
    @endif

    @if ($showRenameForm)
        <form id="indmvt-rename-form" wire:submit.prevent="renameItem" class="scroll-mt-4 space-y-4 rounded-lg border border-indigo-200 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Đổi mã vật tư</h3>
                    <p class="text-xs text-gray-500">Ghi qua asDoiMa theo dictionary INDMVT / MA_VT.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="cancelRename" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">Đổi mã</button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-600">Mã hiện tại</span>
                    <input type="text" wire:model="renameForm.old_ma_vt" disabled class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 font-mono text-sm text-gray-700 shadow-sm" />
                    @error('old_ma_vt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Mã mới</span>
                    <input type="text" wire:model.blur="renameForm.new_ma_vt" class="mt-1 w-full rounded-md border-gray-300 font-mono text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('new_ma_vt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>
        </form>
    @endif
</div>
