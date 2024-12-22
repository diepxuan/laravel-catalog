<x-catalog::app-layout>
    <x-catalog::head-title>{{ __('Hệ Thống') }}</x-catalog::head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hệ Thống') }}
        </h2>
        <p>{{ __('Khoá Số Liệu') }}</p>
    </x-slot>


    <h1 class="mt-1 text-2xl font-medium text-gray-900">
        {{ $system->ten_cty }}
    </h1>

    <table class="table table-sm align-middle table-hover mt-6 text-gray-500 leading-relaxed">
        <tr>
            <td>{{ 'Khoá số liệu:' }}</td>
            <td>
                <form action="{{ route('system.update', $system->ma_cty) }}" method="POST">
                    @method('PUT') @csrf
                    <input type="date" value="{{ $system->khoaSo }}" placeholder="dd/mm/yyyy"
                        onchange="this.form.submit()" name="khoaso" />
                </form>
            </td>
        </tr>
    </table>
</x-catalog::app-layout>
