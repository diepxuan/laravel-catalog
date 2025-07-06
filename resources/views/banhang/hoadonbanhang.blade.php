<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Hoá đơn bán hàng - Bán hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Hoá đơn bán hàng' }}
        </h2>
        <p>{{ 'Danh sách hoá đơn bán hàng' }}</p>
    </x-slot>

    <x-nav-tabs default-tab="filter">
        <x-slot:nav>
            <li class="mr-2">
                <a href="#" x-on:click.prevent="activeTab = 'filter'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'filter', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'profile' }"
                    class="inline-block rounded-t-lg border-b-2 p-4">
                    Điều kiện lọc
                </a>
            </li>
            @if (!empty($soPhs))
                <li class="mr-2">
                    <a href="#" x-on:click.prevent="activeTab = 'content'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'content', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'billing' }"
                        class="inline-block rounded-t-lg border-b-2 p-4">
                        Kết quả
                    </a>
                </li>
            @endif
        </x-slot:nav>

        <x-slot:content>
            <div x-show="activeTab === 'filter'" class="rounded-lg">

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Ngày chứng từ</label>
                    <div class="col-span-2">
                        @livewire('catalog::component.timer')
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã khách hàng</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-khachhang wire:model="pMa_Kh" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Hình thức thanh toán</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-taikhoan wire:model="pTk" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã kho</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_Kho" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã vật tư</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_Vt" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã bộ phận</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_Bp" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã ngoại tệ</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_Nt" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">&nbsp;</label>
                    <button class="rounded-md bg-blue-600 px-2 py-1 text-white hover:bg-blue-700" wire:click="submit">
                        Thực hiện
                    </button>
                </div>
            </div>

            <div x-show="activeTab === 'content'" class="rounded-lg">
                @if (!empty($soPhs))
                    <table class="w-full border border-gray-300 text-xs">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="border border-gray-300 px-1 text-left">Ngày chứng từ</th>
                                <th class="border border-gray-300 px-1 text-left">Số chứng từ</th>
                                <th class="border border-gray-300 px-1 text-left">Tên khách hàng</th>
                                <th class="border border-gray-300 px-1 text-left">Tiền hàng</th>
                                <th class="border border-gray-300 px-1 text-left">Chiết khấu</th>
                                <th class="border border-gray-300 px-1 text-left">Vat</th>
                                <th class="border border-gray-300 px-1 text-left">Thanh toán</th>
                                <th class="border border-gray-300 px-1 text-left">Mã NT</th>
                                <th class="border border-gray-300 px-1 text-left">TK PT</th>
                                <th class="border border-gray-300 px-1 text-left">Diễn giải</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd($soPhCt) --}}
                            @foreach ($soPhs ?? [] as $soPh)
                                <tr class="hover:bg-sky-100">
                                    <td class="border border-gray-300 px-1">{{ $loop->iteration }}</td>
                                    <td class="border border-gray-300 px-1">{{ $soPh->ngay_ct }}</td>
                                    <td class="border border-gray-300 px-1">{{ $soPh->ma_ct }}</td>
                                    <td class="border border-gray-300 px-1">
                                        <pre>{{ $soPh->ten_kh }}</pre>
                                    </td>
                                    <td class="border border-gray-200 px-1 text-right">@currency($soPh->t_tien2)</td>
                                    <td class="border border-gray-200 px-1 text-right">@currency($soPh->t_ck)</td>
                                    <td class="border border-gray-200 px-1 text-right">@currency($soPh->t_thue)</td>
                                    <td class="border border-gray-200 px-1 text-right">@currency($soPh->t_tt)</td>
                                    <td class="border border-gray-300 px-1">{{ $soPh->ma_nt }}</td>
                                    <td class="border border-gray-300 px-1">{{ $soPh->tk_pt }}</td>
                                    <td class="border border-gray-300 px-1">{{ $soPh->dien_giai }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </x-slot:content>

    </x-nav-tabs>

</div>
