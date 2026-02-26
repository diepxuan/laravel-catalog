<x-catalog::head-title>{{ __('"$title"') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('"$title"') }}
    </h2>
</x-slot>

<div class="mx-4 my-2">


    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tồn Kho NTXT Đầu Kỳ
    </h2>

    <div class="space-y-6">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        NTXT: Nguyên vật liệu, Thành phẩm, Xuất trả. Nhập tồn kho đầu kỳ cho các loại hàng tồn kho này.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Nhập tồn kho NTXT đầu kỳ
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Nhập tồn kho đầu kỳ cho nguyên vật liệu, thành phẩm và hàng xuất trả.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <div class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Loại hàng tồn kho
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option>Nguyên vật liệu</option>
                                <option>Thành phẩm</option>
                                <option>Hàng xuất trả</option>
                            </select>
                        </div>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Mã hàng
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Nhập mã hàng">
                        </div>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Số lượng
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="number" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu tồn kho NTXT
                </button>
            </div>
        </div>
    </div>
</div>
