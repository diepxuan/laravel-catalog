<div class="w-full">
    <x-head-title>{{ __('Chọn đơn vị') }}</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Chọn đơn vị') }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ __('Menu 90.40.17') }}
            </p>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if ($statusMessage)
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                {{ $statusMessage }}
            </div>
        @endif

        @error('selectedCompany')
            <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                {{ $message }}
            </div>
        @enderror

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('Mã') }}</th>
                        <th class="px-4 py-3">{{ __('Tên đơn vị') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Trạng thái') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($companies as $company)
                        <tr class="{{ $selectedCompany === $company->ma_cty ? 'bg-blue-50' : 'bg-white' }}">
                            <td class="px-4 py-3 font-mono text-gray-900">{{ $company->ma_cty }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $company->ten_cty }}</td>
                            <td class="px-4 py-3 text-right">
                                @if ($selectedCompany === $company->ma_cty)
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800">
                                        {{ __('Đang chọn') }}
                                    </span>
                                @else
                                    <button
                                        type="button"
                                        wire:click="selectCompany('{{ $company->ma_cty }}')"
                                        class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        {{ __('Chọn') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">
                                {{ __('Không có đơn vị được phân quyền.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
