@extends('catalog::layouts.master')
@section('title', 'Hệ thống')

@section('content')
    <table class="table table-sm align-middle table-hover">
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
                    <input type="date" value="{{ $system->khoaSo }}" placeholder="dd/mm/yyyy" onchange="this.form.submit()"
                        name="khoaso" />
                </form>
            </td>
        </tr>
    </table>
@endsection
