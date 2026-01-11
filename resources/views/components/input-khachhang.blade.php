@props(['disabled' => false])

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} type="text"
        class="block w-full rounded-md border-gray-300 py-0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        list="ArDmKh-suggestions" wire:model="pMa_Kh" />

    <datalist id="ArDmKh-suggestions">
        @foreach ($arDmKhs as $arDmKh)
            <option value="{{ $arDmKh->ma_kh }}">{{ $arDmKh->ten_kh }}</option>
        @endforeach
    </datalist>
</div>
