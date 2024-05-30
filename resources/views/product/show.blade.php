@extends('catalog::layouts.master')
@section('title', $product->sku)

@section('content')
    <style type="text/css">
    </style>
    {{-- @dd($product->simba) --}}
    <form action="{{ route('catalog.update', $product->id) }}" method="POST">
        @method('PUT') @csrf
        <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="status" id="status"
                        {{ $product->status ? 'checked' : '' }} onchange="this.form.submit()" />
                    <label class="form-check-label" for="status">
                        Status
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="sku" class="col-sm-2 col-form-label col-form-label-sm">Mã vật tư</label>
            <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="sku" disabled
                    value="{{ $product->sku }}" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label col-form-label-sm">Tên vật tư</label>
            <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="name" name="name"
                    value="{{ $product->name }}" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="price" class="col-sm-2 col-form-label col-form-label-sm">Giá tiền</label>
            <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="price" name="price"
                    value="{{ $product->price }}" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="category" class="col-sm-2 col-form-label col-form-label-sm">Nhóm vật tư</label>
            <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="category" name="category"
                    value="{{ $product->category }}" />
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
@endsection