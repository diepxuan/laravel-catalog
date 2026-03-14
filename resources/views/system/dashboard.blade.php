<x-catalog::head-title>{{ __('Hệ Thống') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Hệ Thống') }}
    </h2>
    <p>{{ __('Khoá Số Liệu') }}</p>
</x-slot>

<div wire:init="loadData" class="mx-4 my-2">
    <div class="mb-6">
        <h1 class="text-2xl font-medium text-gray-900">
            {{ $system->ten_cty }}
        </h1>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        {{ 'Chức năng' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        {{ 'Giá trị' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        {{ 'Trạng thái' }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                        {{ 'Khoá số liệu' }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        <x-catalog::input-date
                            wire:model="ngayKhoaSo"
                            wire:change="updateKhoaSo()"
                            class="block w-full"
                        />
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <x-catalog::icons.loadding class="mr-2 h-5 w-5 text-indigo-500" />
                            <span class="text-xs text-gray-500">Đã lưu</span>
                        </div>
                        <x-catalog::action-message on="khoa-so-updated" class="ms-2 text-xs text-green-600">
                            {{ 'Đã cập nhật ngày khoá số liệu' }}
                        </x-catalog::action-message>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
