<div x-data="yearWorkedInput({{ $selectedYear }}, {{ $firstYear }})"
    @click.outside="closeDropdown()"
    class="relative inline-flex">
    <button
        type="button"
        @click="toggle()"
        class="inline-flex items-center rounded border border-transparent px-1.5 py-0.5 text-xs text-gray-500 transition hover:border-gray-300 hover:bg-gray-100 hover:text-gray-800"
        title="{{ __('Chọn năm làm việc') }}"
    >
        [ <span x-text="selectedYear"></span> ]
    </button>

    <div
        x-show="open"
        x-cloak
        class="absolute left-0 top-full z-50 mt-1 w-52 rounded-lg border border-gray-200 bg-white p-2 shadow-lg"
    >
        <div class="max-h-64 overflow-y-auto">
            <div class="grid grid-cols-3 gap-1">
                <template x-for="year in years" :key="year">
                    <button
                        type="button"
                        @click="selectYear(year)"
                        :class="selectedYear === year
                            ? 'border-blue-500 bg-blue-50 text-blue-800'
                            : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50'"
                        class="rounded border px-2 py-1.5 text-center text-xs font-medium transition"
                    >
                        <span x-text="year"></span>
                    </button>
                </template>
            </div>
        </div>

        <p x-show="errorMessage" x-text="errorMessage"
            class="mt-2 text-xs text-red-600"></p>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        if (typeof window.yearWorkedInput !== 'undefined') {
            return;
        }

        window.yearWorkedInput = function (selectedYear, firstYear) {
            return {
                selectedYear,
                firstYear,
                open: false,
                errorMessage: '',
                years: [],

                init() {
                    const currentYear = new Date().getFullYear();
                    this.years = Array.from(
                        { length: currentYear + 1 - firstYear + 1 },
                        (_, index) => firstYear + index
                    );
                },

                toggle() {
                    this.open = !this.open;
                    this.errorMessage = '';
                },

                closeDropdown() {
                    this.open = false;
                    this.errorMessage = '';
                },

                selectYear(year) {
                    const currentYear = new Date().getFullYear();
                    if (year < this.firstYear || year > currentYear + 1) {
                        this.errorMessage = 'Năm làm việc không hợp lệ.';

                        return;
                    }

                    this.open = false;
                    this.errorMessage = '';

                    const headers = {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    };

                    fetch('/simba/si/vch/year/select', {
                        method: 'POST',
                        headers,
                        body: JSON.stringify({ year }),
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Lỗi chọn năm làm việc.');
                            }

                            return response.json();
                        })
                        .then((payload) => {
                            this.selectedYear = payload.year;
                        })
                        .catch((error) => {
                            this.errorMessage = error.message || 'Lỗi chọn năm làm việc.';
                        });
                },
            };
        };
    })();
</script>
@endpush
