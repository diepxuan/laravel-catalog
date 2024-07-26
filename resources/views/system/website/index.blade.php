@extends('catalog::layouts.master')
@section('title', 'Websites')

@section('content')
    <table class="table table-sm align-middle table-hover fw-lighter">
        <tr>
            <th>web name</th>
            <th>store name</th>
            <th></th>
        </tr>
        @foreach ($websites as $website)
            <tr>
                <td>{{ $website->name }}</td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($website->storeViews as $storeView)
                <tr>
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
@endsection
