@props(['disabled' => false])

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} type="text"
        class="block w-full rounded-md border-gray-300 py-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        list="InDmNhvt-suggestions" wire:model="pMa_nhvt" />

    <datalist id="InDmNhvt-suggestions">
        @foreach ($inDmNhvts as $inDmNhvt)
            <option value="{{ $inDmNhvt->ma_nhvt }}">{{ $inDmNhvt->ten_nhvt }}</option>
        @endforeach
    </datalist>
</div>
