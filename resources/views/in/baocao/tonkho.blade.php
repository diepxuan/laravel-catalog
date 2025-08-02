<div>
    <x-head-title>{{ 'Báo cáo tồn kho - Vật Tư' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Báo cáo' }}
        </h2>
        <p>{{ 'Tồn kho' }}</p>
    </x-slot>

    <x-nav-tabs default-tab="filter">
        <x-slot:nav>
            <li class="mr-2">
                <a href="#" x-on:click.prevent="activeTab = 'filter'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'filter', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'filter' }"
                    class="inline-block rounded-t-lg border-b-2 p-4">
                    Điều kiện lọc
                </a>
            </li>
            <li class="mr-2">
                <a href="#" x-on:click.prevent="activeTab = 'content'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'content', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'content' }"
                    class="inline-block rounded-t-lg border-b-2 p-4">
                    Kết quả
                </a>
            </li>
        </x-slot:nav>

        <x-slot:content>
            <div x-show="activeTab === 'filter'" class="rounded-lg">

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right"></label>
                    <div class="col-span-2 inline-flex items-center">
                        <span class="pr-2">Đầu kỳ</span>
                        <x-input-switch wire:model="pDk_Ck" />
                        <span class="pl-2">Cuối kỳ</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Ngày</label>
                    <div class="col-span-2">
                        <x-input-date class="px-2 py-1" wire:model="pNgay" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã kho</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_kho" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã vật tư</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_vt" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Nhóm vật tư</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_nhvt" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã ngoại tệ</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_Nt" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã TKVT</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-taikhoan wire:model="pTk_vt" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Mã Lô</label>
                    <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_lo" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right">Đơn vị tính</label>
                    {{-- <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_lo" /> --}}
                    <x-select class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_lo"
                        :options="[
                            0 => 'Đơn vị tính kho',
                            1 => 'Đơn vị tính mua',
                            2 => 'Đơn vị tính bán',
                        ]" />
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right"></label>
                    <div class="col-span-2 inline-flex items-center">
                        <x-input-switch wire:model="pQuaToiThieu" />
                        <span class="pl-2">Tồn nhỏ hơn mức tối thiểu</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <label class="text-right"></label>
                    <div class="col-span-2 inline-flex items-center">
                        <x-input-switch wire:model="pQuaToiDa" />
                        <span class="pl-2">Tồn lớn hơn mức tối đa</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1 pt-1">
                    <label class="text-right"></label>
                    <div class="col-span-2 inline-flex items-center">
                        <x-button-loading class="rounded-md bg-blue-600 px-2 py-1 text-white hover:bg-blue-700"
                            wire:click="submit" x-on:click="activeTab = 'content'">
                            Thực hiện
                        </x-button-loading>
                    </div>
                </div>

            </div>

            <div x-show="activeTab === 'content'" class="w-full overflow-x-auto rounded-lg py-2">
                <p class="whitespace-pre font-mono text-xs leading-tight">{!! $inDmVts !!}</p>
            </div>
        </x-slot:content>

    </x-nav-tabs>

</div>
