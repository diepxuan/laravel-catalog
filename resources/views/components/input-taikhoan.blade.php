@props(['disabled' => false])

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} type="text"
        class="block w-full rounded-md border-gray-300 py-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        list="GlDmTk-suggestions" wire:model="pTk" />

    <datalist id="GlDmTk-suggestions">
        @foreach ($glDmTks as $glDmTk)
            <option value="{{ $glDmTk->tk }}">{{ $glDmTk->ten_tk }}</option>
        @endforeach
    </datalist>
</div>
