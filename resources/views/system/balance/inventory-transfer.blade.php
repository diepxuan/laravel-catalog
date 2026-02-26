<x-catalog::head-title>{{ __('"$title"') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('"$title"') }}
    </h2>
</x-slot>

<div class="mx-4 my-2">


    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Chuyển Tồn Kho Sang Năm Sau
    </h2>

    <div class="space-y-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Chuyển tồn kho sang năm sau
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Chuyển tồn kho từ năm hiện tại sang năm mới.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <div class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Kho
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option>Kho chính</option>
                                <option>Kho phụ</option>
                                <option>Kho thành phẩm</option>
                            </select>
                        </div>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <div class="text-sm font-medium text-gray-500">
                            Ngày chuyển
                        </div>
                        <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="date" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="2026-12-31">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Thực hiện chuyển kho
                </button>
            </div>
        </div>
    </div>
</div>
