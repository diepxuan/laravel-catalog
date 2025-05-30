<div wire:change="changeTimer">
    <x-select class="py-1" :options="$options" wire:model="timer.id" />
    <x-input-date class="px-2 py-1" wire:model="timer.from" :disabled="$timer['id'] !== 'c'" />
    <span class="text-xs">→</span>
    <x-input-date class="px-2 py-1" wire:model="timer.to" :disabled="$timer['id'] !== 'c'" />
</div>
