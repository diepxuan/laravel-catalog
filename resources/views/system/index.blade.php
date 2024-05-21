@extends('catalog::layouts.master')

@section('content')
    <style type="text/css">
        table {
            border-style: solid;
            border-color: rgb(193, 193, 193);
        }
    </style>
    <table>
        {{-- @dd($system) --}}
        {{-- @dd($system->siSetup) --}}
        <tbody>
            <tr>
                <td colspan="2">
                    <center>{{ $system->ten_cty }}</center>
                </td>
            </tr>
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
        </tbody>
    </table>
@endsection
