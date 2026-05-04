@props(['disabled' => false, 'class' => '', 'placeholder' => 'Nhập mã tài khoản...'])

<div class="relative" x-data="tkInputComponent(@js($glDmTks), @js($pTk))" x-init="initComponent()"
    @click.outside="showDropdown = false" @keydown.escape.window="showDropdown = false">

    <!-- Input field -->
    <div class="relative">
        <input {{ $disabled ? 'disabled' : '' }} type="text" placeholder="{{ $placeholder }}" x-model="search"
            @focus="showDropdown = true" @click="showDropdown = true"
            @keydown="handleKeydown($event)" wire:model.live.debounce.300ms="pTk"
            class="{{ $class }} block w-full rounded-md border-gray-300 py-1 pr-8 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-autocomplete="list" />

        <!-- Search icon -->
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Dropdown -->
    <div x-show="showDropdown" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg"
        style="display: none;" role="listbox" aria-label="Danh sách tài khoản">

        <template x-if="filtered.length > 0">
            <ul class="py-1">
                <template x-for="(acc, index) in filtered">
                    <li>
                        <a href="#" @click.prevent="selectAccount(acc)" @keydown.enter="selectAccount(acc)"
                            @mouseenter="highlightedIndex = index"
                            :class="{'bg-indigo-50': highlightedIndex === index, 'hover:bg-gray-50': highlightedIndex !== index}"
                            class="flex items-center justify-between px-4 py-2.5 text-sm cursor-pointer outline-none focus:bg-indigo-50"
                            role="option" :aria-selected="highlightedIndex === index" :id="'option-' + index">
                            <div class="flex items-center gap-3">
                                <!-- Account code badge -->
                                <span
                                    class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 font-mono text-xs font-medium text-indigo-700"
                                    x-text="acc.tk"></span>
                                <!-- Account name -->
                                <span class="text-gray-700" x-text="acc.ten_tk"></span>
                            </div>
                        </a>
                    </li>
                </template>
            </ul>
            <template x-if="filtered.length >= 10">
                <div class="border-t border-gray-100 bg-gray-50 px-4 py-2">
                    <p class="text-xs text-gray-500">Hiển thị 10 kết quả đầu tiên. Vui lòng nhập cụ thể hơn...</p>
                </div>
            </template>
        </template>

        <template x-if="filtered.length === 0 && search.length > 0">
            <div class="px-4 py-8 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Không tìm thấy tài khoản nào</p>
                <p class="text-xs text-gray-400">Thử tìm kiếm với từ khóa khác</p>
            </div>
        </template>
    </div>
</div>
@push('scripts')
    <script>
        (function() {
            // Guard: Only define once
            if (typeof window.tkInputComponent !== 'undefined') {
                return;
            }

            /**
             * Alpine.js component for account input with search
             *
             * @param {Array} accounts - List of accounts [{tk, ten_tk}]
             * @returns {Object} Alpine component data
             */
            window.tkInputComponent = function(accounts, initialSearch = '') {
                return {
                    accounts: accounts,
                    search: initialSearch || '',
                    showDropdown: false,
                    filtered: [],
                    highlightedIndex: -1,

                    initComponent() {
                        // Watch search and filter instantly (no delay, client-side)
                        this.$watch('search', (value) => {
                            this.filtered = this.filterAccounts(value);
                            this.highlightedIndex = -1; // Reset highlight khi filter thay đổi
                        });
                    },

                    handleKeydown(event) {
                        if (!this.showDropdown) {
                            if (['ArrowDown', 'ArrowUp'].includes(event.key)) {
                                this.showDropdown = true;
                                this.highlightedIndex = 0;
                            }
                            return;
                        }

                        switch (event.key) {
                            case 'ArrowDown':
                                event.preventDefault();
                                this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.filtered.length - 1);
                                this.scrollToHighlighted();
                                break;

                            case 'ArrowUp':
                                event.preventDefault();
                                this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                                this.scrollToHighlighted();
                                break;

                            case 'Enter':
                                event.preventDefault();
                                if (this.highlightedIndex >= 0 && this.filtered[this.highlightedIndex]) {
                                    this.selectAccount(this.filtered[this.highlightedIndex]);
                                }
                                break;

                            case 'Escape':
                                this.showDropdown = false;
                                break;
                        }
                    },

                    scrollToHighlighted() {
                        this.$nextTick(() => {
                            const el = document.getElementById('option-' + this.highlightedIndex);
                            if (el) {
                                el.scrollIntoView({ block: 'nearest' });
                            }
                        });
                    },

                    selectAccount(acc) {
                        // Điền mã tài khoản vào input
                        this.search = acc.tk;
                        // Đóng dropdown
                        this.showDropdown = false;
                        // Không lưu ra param ngoài - chỉ hiển thị trong input
                    },

                    filterAccounts(query) {
                        if (!query) return this.accounts.slice(0, 10);

                        const q = query.toLowerCase();
                        return this.accounts.filter(acc => {
                            const tk = acc.tk.toString().toLowerCase();
                            const ten = acc.ten_tk.toLowerCase();
                            return tk === q || ten === q || tk.includes(q) || ten.includes(q);
                        }).slice(0, 10);
                    }
                }
            }
        })();
    </script>
@endpush
