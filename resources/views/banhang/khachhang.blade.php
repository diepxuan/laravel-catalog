<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Khách hàng - Bán hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Khách hàng' }}
        </h2>
        <p>{{ 'Danh sách khách hàng' }}</p>
    </x-slot>

    <table class="w-full border border-gray-300 text-xs">
        <thead>
            <tr>
                <th></th>
                <th class="border border-gray-300 px-1 text-left">Mã khách hàng</th>
                <th class="border border-gray-300 px-1 text-left">Tên khách hàng</th>
                {{-- <th class="border border-gray-300 text-left px-1">Mã số thuế</th> --}}
                <th class="border border-gray-300 px-1 text-left">Địa chỉ</th>
                <th class="border border-gray-300 px-1 text-left">Điện thoại</th>
                <th class="border border-gray-300 px-1 text-left">Người giao dịch</th>
                <th class="border border-gray-300 px-1 text-left">Hình thức TT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($arDmKhs ?? [] as $arDmKh)
                <tr class="hover:bg-sky-100">
                    <td class="border border-gray-300 px-1">{{ $loop->iteration }}</td>
                    <td class="border border-gray-300 px-1">{{ $arDmKh->ma_kh }}</td>
                    <td class="border border-gray-300 px-1">
                        <pre>{{ $arDmKh->ten_kh }}</pre>
                    </td>
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
