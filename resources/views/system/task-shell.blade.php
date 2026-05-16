<div class="w-full">
    <x-head-title>{{ $title }}</x-head-title>
    <x-slot name="header">
        <div><h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2><p class="text-sm text-gray-500">{{ $dll }} · {{ $task }}</p></div>
    </x-slot>
    <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        {{ $note }}
    </div>
</div>
