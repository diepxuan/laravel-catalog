<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Nhà cung cấp - Mua hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Nhà cung cấp' }}
        </h2>
        <p>{{ 'Danh sách nhà cung cấp' }}</p>
    </x-slot>

    <p class="whitespace-pre font-mono text-xs leading-tight">{!! $arDmKhs->toMarkdownTable(
        [
            'ma_kh' => 'Mã',
            'ten_kh' => 'Tên nhà cung cấp',
            'dia_chi' => 'Địa chỉ',
            'dien_thoai' => 'Điện thoại',
            'nguoi_gd' => 'Người giao dịch',
            'ma_httt_po' => 'Hình thức TT',
        ],
        true,
    ) !!}</p>
    {{-- <table class="w-full text-xs">
        <thead>
            <tr>
                <th></th>
                <th class="px-1 text-left">Mã khách hàng</th>
                <th class="px-1 text-left">Tên khách hàng</th>
                <th class="px-1 text-left">Địa chỉ</th>
                <th class="px-1 text-left">Điện thoại</th>
                <th class="px-1 text-left">Người giao dịch</th>
                <th class="px-1 text-left">Hình thức TT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($arDmKhs ?? [] as $arDmKh)
                <tr class="hover:bg-sky-100">
                    <td class="px-1">{{ $loop->iteration }}</td>
                    <td class="px-1">{{ $arDmKh->ma_kh }}</td>
                    <td class="px-1">
                        <pre>{{ $arDmKh->ten_kh }}</pre>
                    </td>
                    <td class="px-1">{{ $arDmKh->dia_chi }}</td>
                    <td class="px-1">{{ $arDmKh->dien_thoai }}</td>
                    <td class="px-1">{{ $arDmKh->nguoi_gd }}</td>
                    <td class="px-1">{{ $arDmKh->ma_httt_po }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

</div>
