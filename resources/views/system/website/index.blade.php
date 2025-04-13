<x-catalog::app-layout>
    <x-catalog::head-title>{{ __('Hệ Thống') }}</x-catalog::head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hệ Thống') }}
        </h2>
        <p>{{ __('Magento Websites') }}</p>
    </x-slot>

    <table class="table-auto w-full border border-gray-200 divide-y divide-gray-200">
        <tr class="border border-gray-200">
            <th class="text-left">web name</th>
            <th class="text-left">store name</th>
            <th></th>
        </tr>
        @foreach ($websites as $website)
            <tr class="border border-gray-200">
                <td>{{ $website->name }}</td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($website->storeViews as $storeView)
                <tr class="border border-gray-200">
                    <td></td>
                    <td>{{ $storeView->name }}</td>
                    @isset($storeView->storeConfig)
                        <td>
                            <a href="{{ $storeView->storeConfig->secure_base_url }}" target="_blank">
                                {{ str_replace('www.', '', parse_url($storeView->storeConfig->secure_base_url)['host']) }}
                            </a>
                        </td>
                    @else
                        <td>
                            {{ $storeView->code }}
                        </td>
                    @endisset
                </tr>
            @endforeach
        @endforeach
    </table>

</x-catalog::app-layout>
