<div wire:init="loadData" class="mx-4 my-2">

    <h1 class="mt-1 text-2xl font-medium text-gray-900">
        {{ $system->ten_cty }}
    </h1>

    <table class="table-sm table-hover mt-6 table align-middle leading-relaxed text-gray-500">
        <tr>
            <td>{{ 'Khoá số liệu:' }}</td>
            <td>
                <input type="date" placeholder="dd/mm/yyyy" wire:model="ngayKhoaSo" wire:change="updateKhoaSo()" />
            </td>
            <td>
                <p class="m-auto inline-block cursor-default rounded-t-lg px-3 py-0">
                    <svg wire:loading class="-ml-1 mr-3 h-5 w-5 animate-spin text-blue-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <!-- Checkmark - hiển thị khi KHÔNG loading -->
                    @php
                        $checkmarkId = Str::uuid();
                    @endphp

                    <svg wire:loading.remove
                        class="animate-fade-out-{{ $checkmarkId }} -ml-1 mr-3 h-5 w-5 text-green-500"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <style>
                        .animate-fade-out-{{ $checkmarkId }} {
                            animation: fadeOut 0.5s ease-out 0.5s forwards;
                        }

                        @keyframes fadeOut {
                            from {
                                opacity: 1;
                                visibility: visible;
                            }

                            to {
                                opacity: 0;
                                visibility: hidden;
                            }
                        }
                    </style>
                </p>
            </td>
        </tr>
    </table>
</div>
