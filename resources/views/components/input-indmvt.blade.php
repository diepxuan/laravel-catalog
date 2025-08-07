@props(['disabled' => false])

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} type="text"
        class="block w-full rounded-md border-gray-300 py-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        list="InDmVt-suggestions" wire:model="pMa_vt" />

    <datalist id="InDmVt-suggestions">
        @foreach ($inDmVts as $inDmVt)
            <option value="{{ $inDmVt->ma_vt }}">{{ $inDmVt->ten_vt }}</option>
        @endforeach
    </datalist>
</div>
