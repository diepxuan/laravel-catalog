<div wire:change="changeTimer">
    <x-select class="mb-1 py-0 text-sm" :options="$options" wire:model="timer.id" />
    <x-input-date class="mb-1 px-2 py-0 text-sm" wire:model="timer.from" :disabled="$timer['id'] !== 'c'" />
    <span class="text-xs">→</span>
    <x-input-date class="mb-1 px-2 py-0 text-sm" wire:model="timer.to" :disabled="$timer['id'] !== 'c'" />
</div>
