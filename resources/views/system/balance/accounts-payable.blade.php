<x-catalog::head-title>{{ __('"$title"') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('"$title"') }}
    </h2>
</x-slot>

<div class="mx-4 my-2">


    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Công Nợ Phải Trả
    </h2>

    <div class="space-y-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Nhập số dư đầu kỳ công nợ phải trả
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Nhập số dư đầu kỳ công nợ phải trả cho nhà cung cấp.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <div class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Nhà cung cấp
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option>Công ty Vật tư A</option>
                                <option>Công ty Nguyên liệu B</option>
                                <option>Nhà cung cấp C</option>
                            </select>
                        </div>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Số tiền phải trả
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="number" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu công nợ
                </button>
            </div>
        </div>
    </div>
</div>
