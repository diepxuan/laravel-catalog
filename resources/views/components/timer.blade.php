<div class="flex w-full flex-wrap items-center gap-2">
    {{-- Timer period selector --}}
    <label for="timer-period" class="sr-only">Chọn thời gian</label>
    <x-select
        id="timer-period"
        class="min-w-[140px] rounded-md border-gray-300 bg-white px-2 py-1 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :options="$options"
        wire:model.live="timerId"
    />

    {{-- Date range inputs (only enabled for custom mode) --}}
    <div class="flex min-w-0 flex-wrap items-center gap-2" wire:ignore.self>
        <x-input-date
            class="w-36 rounded-md border-gray-300 bg-white px-2 py-1 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400"
            wire:model.live.debounce.500ms="timerFrom"
            :disabled="$timerId !== 'c'"
        />
        <span class="text-gray-400">-</span>
        <x-input-date
            class="w-36 rounded-md border-gray-300 bg-white px-2 py-1 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400"
            wire:model.live.debounce.500ms="timerTo"
            :disabled="$timerId !== 'c'"
        />
    </div>
</div>
