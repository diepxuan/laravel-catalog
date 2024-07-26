@extends('catalog::layouts.master')
@section('title', 'Websites')

@section('content')
    <table class="table table-sm align-middle table-hover fw-lighter">
        <tr>
            <th>web id</th>
            <th>web name</th>
            <th>web code</th>
            <th>store name</th>
            <th>store code</th>
        </tr>
        @foreach ($websites as $website)
            <tr>
                <td>{{ $website->id }}</td>
                <td>{{ $website->name }}</td>
                <td>{{ $website->code }}</td>
                {{-- <td>{{ $website->default_group_id }}</td> --}}
            </tr>
            @foreach ($website->storeViews as $storeView)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    {{-- <td>{{ $storeView->id }}</td> --}}
                    <td>{{ $storeView->name }}</td>
                    <td>{{ $storeView->code }}</td>
                    {{-- <td>{{ $storeView->website_id }}</td> --}}
                    {{-- <td>{{ $storeView->store_group_id }}</td> --}}
                    {{-- <td>{{ $storeView->is_active }}</td> --}}
                    {{-- <td>{{ $storeView->extension_attributes }}</td> --}}
                </tr>
            @endforeach
        @endforeach

    </table>
@endsection
