<x-catalog::head-title>{{ __('Nhập/Chuyển Số Dư') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Hệ Thống') }}
    </h2>
    <p>{{ __('Nhập/Chuyển Số Dư') }}</p>
</x-slot>

<div class="mx-4 my-2">
    <div class="space-y-6">
        <div class="border-l-4 border-blue-400 bg-blue-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Trang quản lý nhập và chuyển số dư đầu kỳ, tồn kho, công nợ và các giá trị khác.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Số dư đầu kỳ tài khoản -->
            <a href="{{ route('system.balance.account-opening') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-green-100 p-2">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Số dư đầu kỳ tài khoản</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập số dư đầu kỳ cho các tài khoản kế toán</p>
            </a>

            <!-- Chuyển số dư sang năm sau -->
            <a href="{{ route('system.balance.transfer') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-blue-100 p-2">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Chuyển số dư sang năm sau</h5>
                </div>
                <p class="font-normal text-gray-700">Chuyển số dư tài khoản từ năm hiện tại sang năm mới</p>
            </a>

            <!-- Công nợ phải thu -->
            <a href="{{ route('system.balance.accounts-receivable') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-yellow-100 p-2">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Công nợ phải thu</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập số dư đầu kỳ công nợ phải thu từ khách hàng</p>
            </a>

            <!-- Công nợ phải trả -->
            <a href="{{ route('system.balance.accounts-payable') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-red-100 p-2">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Công nợ phải trả</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập số dư đầu kỳ công nợ phải trả cho nhà cung cấp</p>
            </a>

            <!-- Tồn kho đầu kỳ -->
            <a href="{{ route('system.balance.inventory-opening') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-purple-100 p-2">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Tồn kho đầu kỳ</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập tồn kho đầu kỳ cho các mặt hàng trong kho</p>
            </a>

            <!-- Tồn kho NTXT -->
            <a href="{{ route('system.balance.inventory-opening-ntxt') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-indigo-100 p-2">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Tồn kho NTXT</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập tồn kho đầu kỳ NTXT (Nguyên vật liệu, thành phẩm, xuất trả)
                </p>
            </a>

            <!-- Chuyển tồn kho sang năm sau -->
            <a href="{{ route('system.balance.inventory-transfer') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-teal-100 p-2">
                        <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Chuyển tồn kho sang năm sau</h5>
                </div>
                <p class="font-normal text-gray-700">Chuyển tồn kho từ năm hiện tại sang năm mới</p>
            </a>

            <!-- Sản phẩm dở dang -->
            <a href="{{ route('system.balance.work-in-progress') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-orange-100 p-2">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Sản phẩm dở dang</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập giá trị sản phẩm dở dang cuối kỳ</p>
            </a>

            <!-- Phát sinh luỹ kế -->
            <a href="{{ route('system.balance.cumulative-transactions') }}"
                class="block rounded-lg border border-gray-200 bg-white p-6 shadow hover:bg-gray-50">
                <div class="mb-4 flex items-center">
                    <div class="rounded-lg bg-pink-100 p-2">
                        <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h5 class="ml-3 text-xl font-bold tracking-tight text-gray-900">Phát sinh luỹ kế</h5>
                </div>
                <p class="font-normal text-gray-700">Nhập phát sinh luỹ kế từ khi khởi động hệ thống</p>
            </a>
        </div>
    </div>
</div>
