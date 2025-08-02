@props(['disabled' => false])

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} type="text"
        class="block w-full rounded-md border-gray-300 py-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        list="InDmKho-suggestions" wire:model="pMa_kho" />

    <datalist id="InDmKho-suggestions">
        @foreach ($inDmKhos as $inDmKho)
            <option value="{{ $inDmKho->ma_kho }}">{{ $inDmKho->ten_kho }}</option>
        @endforeach
    </datalist>
</div>
