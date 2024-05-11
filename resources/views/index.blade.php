@extends('catalog::layouts.master')

@section('content')
    <style>
        body {
            font-size: 0.75rem
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        body {
            counter-reset: Serial;
            /* Set the Serial counter to 0 */
        }

        table {
            border-collapse: separate;
        }

        tr td:first-child:before {
            counter-increment: Serial;
            /* Increment the Serial counter */
            content: counter(Serial);
            /* Display the counter */
        }

        button.sync {
            border: solid 1px rgb(184, 63, 63);
            border-radius: 10%;
            color: rgb(184, 63, 63);
        }

        button.sync:hover {
            cursor: pointer;
            background: rgb(184, 63, 63);
            color: rgb(241, 225, 216)
        }

        td a {
            text-decoration-line: none;
            color: rgb(10, 149, 22);
        }
    </style>
    <table class="table table-hover table-condensed table-sm text-monospace small">
        <tbody>
            @foreach ($products as $product)
                @if ($mProducts)
                    @php
                        $mProduct = null;
                        $mProduct = $mProducts->first(function ($item, $key) use ($mProducts, $product, &$mProduct) {
                            if ($item->sku == $product->ma_vt) {
                                $mProduct = $mProducts->pull($key);
                                return true;
                            }
                            return false;
                        });
                    @endphp
                    {{-- {{ debug($mProduct) }} --}}
                @endif
                <tr id="{{ "$product->ma_cty|$product->ma_vt" }}">
                    <td></td>
                    <td>{{ $product->ma_vt }}</td>
                    <td>{{ $product->ten_vt }}</td>
                    <td>{{ $product->ma_nhvt }}</td>
                    @if (isset($mProduct))
                        <td>
                            <a href="{{ "https://www.diepxuan.com/catalog/product/view/id/$mProduct->id" }}">
                                {{ $mProduct->id }}
                            </a>
                        </td>
                        <td>
                            <a href="https://www.diepxuan.com/{{ $mProduct->custom_attributes->url_key }}.html">
                                {{ $mProduct->custom_attributes->url_key }}
                            </a>
                        </td>
                    @else
                        <td>
                            <form method="post" action="{{ route('catalog.store') }}"
                                id="form{{ "$product->ma_cty|$product->ma_vt" }}" name="magento_form">
                                @method('POST') @csrf
                                <input type="hidden" value="magento2" name="type" />
                                <input type="hidden" value="{{ $product->ma_vt }}" name="sku" />
                                <input type="hidden" value="{{ $product->ten_vt }}" name="name" />
                                <button type="submit" class="sync">sync</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
            {{-- {{ debug($mProducts) }} --}}
        </tbody>
    </table>
    @if ($mProducts)
        <script>
            function submitForm($id) {
                var data = new FormData(document.getElementById($id));

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "{{ route('catalog.store') }}");

                xhr.onload = () => {
                    // console.log(xhr.response);
                };

                xhr.send(data);
                return false;
            }
            // window.onload = function() {
            //     $forms = document.getElementsByName('magento_form')
            //     for (const $form of $forms) {
            //         setTimeout(function() {
            //             submitForm($form.id);
            //         }, 3000);
            //     }
            // }
        </script>
    @endif
@endsection
