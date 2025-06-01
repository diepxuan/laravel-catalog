<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Khách hàng - Bán hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Khách hàng' }}
        </h2>
        <p>{{ 'Danh sách khách hàng' }}</p>
    </x-slot>

    <table class="w-full mt-3 text-xs border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 text-left px-1">Mã khách hàng</th>
                <th class="border border-gray-300 text-left px-1">Tên khách hàng</th>
                {{-- <th class="border border-gray-300 text-left px-1">Mã số thuế</th> --}}
                <th class="border border-gray-300 text-left px-1">Địa chỉ</th>
                <th class="border border-gray-300 text-left px-1">Điện thoại</th>
                <th class="border border-gray-300 text-left px-1">Người giao dịch</th>
                <th class="border border-gray-300 text-left px-1">Hình thức TT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($arDmKhs??[] as $arDmKh)
            <tr class="hover:bg-sky-100">
                <td class="border border-gray-300 px-1">{{ $arDmKh->ma_kh }}</td>
                <td class="border border-gray-300 px-1"><pre>{{ $arDmKh->ten_kh }}</pre></td>
                {{-- <td class="border border-gray-300 px-1">{{ $arDmKh->ma_so_thue }}</td> --}}
                <td class="border border-gray-300 px-1">{{ $arDmKh->dia_chi }}</td>
                <td class="border border-gray-300 px-1">{{ $arDmKh->dien_thoai }}</td>
                <td class="border border-gray-300 px-1">{{ $arDmKh->nguoi_gd }}</td>
                <td class="border border-gray-300 px-1">{{ $arDmKh->ma_httt }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
