<label {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'relative inline-flex items-center cursor-pointer']) }}>
    <input {{ $attributes->wire('model') }} type="checkbox" class="peer sr-only" />
    <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600">
    </div>
    <span class="absolute left-[2px] top-[2px] h-5 w-5 rounded-full bg-white transition-transform peer-checked:translate-x-full"></span>
</label>
