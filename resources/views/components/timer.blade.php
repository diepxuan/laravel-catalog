<div class="flex items-center gap-2">
    {{-- Timer period selector --}}
    <label for="timer-period" class="sr-only">Chọn thời gian</label>
    <x-select
        id="timer-period"
        class="h-9 min-w-[140px] rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-700 shadow-sm transition-all hover:border-gray-300 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
        :options="$options"
        wire:model.live="timerId"
    />

    {{-- Date range inputs (only enabled for custom mode) --}}
    <div class="flex flex-wrap items-center gap-2 w-full" wire:ignore.self>
        <x-input-date
            class="h-9 w-36 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 shadow-sm transition-all hover:border-gray-300 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400"
            wire:model.live="timerFrom"
            :disabled="$timerId !== 'c'"
        />
        <span class="text-gray-300">−</span>
        <x-input-date
            class="h-9 w-36 rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 shadow-sm transition-all hover:border-gray-300 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400"
            wire:model.live="timerTo"
            :disabled="$timerId !== 'c'"
        />
    </div>
</div>
