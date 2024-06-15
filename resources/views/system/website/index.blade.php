@extends('catalog::layouts.master')
@section('title', 'Websites')

@section('content')
    <table class="table table-sm align-middle table-hover fw-lighter">
        @foreach ($websites as $website)
            <tr>
                <td>{{ $website->id }}</td>
                <td>{{ $website->name }}</td>
                <td>{{ $website->code }}</td>
                {{-- <td>{{ $website->default_group_id }}</td> --}}
            </tr>
        @endforeach
    </table>
@endsection
