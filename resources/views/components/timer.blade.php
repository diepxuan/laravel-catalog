<div>
    {{-- Timer period selector --}}
    <label for="timer-period" class="sr-only">Chọn thời gian</label>
    <x-select
        id="timer-period"
        class="mb-1 py-0 text-sm"
        :options="$options"
        wire:model.live="timerId"
    />

    {{-- Date range inputs (only enabled for custom mode) --}}
    <x-input-date
        class="mb-1 px-2 py-0 text-sm"
        wire:model.live="timerFrom"
        :disabled="$timerId !== 'c'"
    />
    <span class="text-xs text-gray-400">→</span>
    <x-input-date
        class="mb-1 px-2 py-0 text-sm"
        wire:model.live="timerTo"
        :disabled="$timerId !== 'c'"
    />
</div>
