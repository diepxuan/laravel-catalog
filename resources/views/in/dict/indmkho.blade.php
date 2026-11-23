<div>
    <x-head-title>{{ 'Kho hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Danh sách kho hàng' }}
        </h2>
        <p>{{ 'Danh sách' }}</p>
    </x-slot>

    <div class="w-full overflow-x-auto">
        {{-- <div class="whitespace-pre font-mono text-xs leading-tight">{!! $inDmKhos->toMarkdownTable(
            [
                'ma_kho' => 'Mã kho',
                'ten_kho' => 'Tên kho',
                'kho_dl' => 'Kho đại lý',
                'tk_dl' => 'TK đại lý',
                'dia_chi' => 'Địa chỉ',
                'nguoi_lh' => 'Người liên hệ',
                'tel' => 'Số điện thoại',
                'fax' => 'Số fax',
                'email' => 'Email',
                'stt_ntxt' => 'Số thứ tự NTXT',
                'ksd' => 'KSD',
                'cdate' => 'Ngày tạo',
                'cuser' => 'Người tạo',
                'ldate' => 'Ngày sửa',
                'luser' => 'Người sửa',
            ],
            true,
        ) !!}
        </div> --}}

        <table class="border border-gray-200 font-mono text-xs">
            <thead>
                <tr>
                    <th></th>
                    <th class="border border-gray-200 px-1 text-left">Mã kho</th>
                    <th class="border border-gray-200 px-1 text-left">Tên kho</th>
                    <th class="border border-gray-200 px-1 text-left">Kho đại lý</th>
                    <th class="border border-gray-200 px-1 text-left">TK đại lý</th>
                    <th class="border border-gray-200 px-1 text-left">Địa chỉ</th>
                    <th class="border border-gray-200 px-1 text-left">Người liên hệ</th>
                    <th class="border border-gray-200 px-1 text-left">Số điện thoại</th>
                    <th class="border border-gray-200 px-1 text-left">Số fax</th>
                    <th class="border border-gray-200 px-1 text-left">Email</th>
                    <th class="border border-gray-200 px-1 text-left">Số thứ tự NTXT</th>
                    <th class="border border-gray-200 px-1 text-left">KSD</th>
                    <th class="border border-gray-200 px-1 text-left">Ngày tạo</th>
                    <th class="border border-gray-200 px-1 text-left">Người tạo</th>
                    <th class="border border-gray-200 px-1 text-left">Ngày sửa</th>
                    <th class="border border-gray-200 px-1 text-left">Người sửa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inDmKhos ?? [] as $inDmKho)
                    <tr class="hover:bg-sky-100">
                        <td class="border border-gray-200 px-1 text-right">{{ $loop->iteration }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->ma_kho }}</td>
                        <td class="whitespace-pre border border-gray-200 px-1">{{ $inDmKho->ten_kho }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->kho_dl }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->tk_dl }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->dia_chi }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->nguoi_lh }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->tel }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->fax }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->email }}</td>
                        <td class="border border-gray-200 px-1 text-center">{{ $inDmKho->stt_ntxt }}</td>
                        <td class="border border-gray-200 px-1 text-center">{{ $inDmKho->ksd }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->cdate }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->cuser }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->ldate }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmKho->luser }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
