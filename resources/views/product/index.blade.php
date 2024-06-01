@extends('catalog::layouts.master')
@section('title', 'Hàng hoá vật tư')

@section('content')
    <style type="text/css">
    </style>

    <div class="list-group list-group-flush border-top border-start">
        @foreach ($products as $product)
            <div class="list-group-item list-group-item-action lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <form action="{{ route('catalog.update', $product->id) }}" method="POST">
                        @method('PUT') @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="status"
                                {{ $product->status ? 'checked' : '' }} onchange="this.form.submit()" />
                            <strong class="mb-1">
                                {{ $product->name }}
                                <a href="{{ route('catalog.show', $product->id) }}" class="text-decoration-none"
                                    target="_blank">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </strong>
                        </div>
                    </form>
                    <small>{{ $product->quantity ?: 0 }}</small>
                </div>
                <div class="mt-1 small row">
                    <small class="col-md-6">
                        @php
                            if ($product->cat) {
                                $path = "{$product->cat->urlPath}/{$product->urlKey}";
                            } else {
                                $path = $product->urlKey;
                            }
                        @endphp
                        <a href="https://www.diepxuan.com/{{ $path }}.html" class="text-decoration-none"
                            target="_blank">
                            <i class="bi bi-link"></i>
                            {{ $product->sku }}
                        </a>
                    </small>
                    <small class="col-md-2 text-end">{{ $product->price }}</small>
                    <small class="col-md-4">{{ implode(' | ', $product->catIds) }}</small>
                </div>
            </div>
        @endforeach
    </div>

    {{-- <table class="table table-hover table-condensed table-sm text-monospace small">
        <tbody>
            @foreach ($products as $product)
                <tr id="{{ "$product->simbaId" }}" @class(['prod', 'disable' => $product->status])>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?: 'missing' }}</td>
                    <td>{{ $product->cat ? $product->cat->magento_id : 'NaN' }}</td>
                    <td>{{ $product->quantity ?: 0 }}</td>
                    <td>
                        @isset($product->cat)
                            <a href="https://www.diepxuan.com/{{ $product->cat->urlPath }}/{{ $product->urlKey }}.html"
                                target="_blank">
                                {{ $product->sku }}
                            </a>
                        @else
                            <a href="https://www.diepxuan.com/{{ $product->urlKey }}.html" target="_blank">
                                {{ $product->sku }}
                            </a>
                        @endisset
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
@endsection
