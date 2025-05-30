@props(['checked' => false])

<label {{ $attributes->merge(['class' => 'relative inline-flex items-center cursor-pointer']) }}>
    <input type="checkbox" class="sr-only peer" {{ $checked ? 'checked' : '' }} />
    <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600">
    </div>
    <span
        class="absolute left-[2px] top-[2px] w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></span>
</label>
