@props(['disabled' => false, 'class' => '', 'placeholder' => 'Nhập mã tài khoản...', 'filter' => ''])

@php
    // Parse "key=value" filter expressions from Simba's LookupWhereCondition (e.g. "tk_cn=1").
    $parsedFilters = [];
    foreach (explode(' ', trim((string) $filter)) as $part) {
        if ('' === $part) {
            continue;
        }
        if (str_contains($part, '=')) {
            [$key, $value] = explode('=', $part, 2);
            $parsedFilters[trim($key)] = trim($value);
        }
    }
@endphp

<div class="relative" x-data="tkInputComponent(@js($glDmTks), @js($pTk), @js($parsedFilters))" x-init="initComponent()"
    @click.outside="showDropdown = false" @keydown.escape.window="showDropdown = false">

    <!-- Input field -->
    <div class="relative">
        <input {{ $disabled ? 'disabled' : '' }} type="text" placeholder="{{ $placeholder }}" x-model="search"
            @focus="openDropdown()" @click="openDropdown()" @change="commitSearch()"
            @keydown="handleKeydown($event)"
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
                        <a href="#" @mousedown.prevent="selectAccount(acc)" @click.prevent @keydown.enter="selectAccount(acc)"
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
             * @param {Array}  accounts       - List of accounts [{tk, ten_tk, ...}]
             * @param {string} initialSearch  - Initial value for the input
             * @param {Object} filters        - Client-side filters parsed from `key=value` (e.g. {tk_cn: '1'})
             * @returns {Object} Alpine component data
             */
            window.tkInputComponent = function(accounts, initialSearch = '', filters = {}) {
                return {
                    accounts: accounts,
                    filters: filters || {},
                    search: initialSearch || '',
                    showDropdown: false,
                    filtered: [],
                    highlightedIndex: -1,
                    filterTimer: null,

                    /**
                     * Apply the static filters (e.g. tk_cn=1) declared by the parent form.
                     * Mirrors Simba's LookupWhereCondition so reports only show relevant accounts.
                     */
                    applyFilters(list) {
                        const filters = this.filters || {};
                        const keys = Object.keys(filters);
                        if (0 === keys.length) {
                            return list;
                        }
                        return list.filter((acc) => keys.every((k) => {
                            const expected = String(filters[k]);
                            const actual = String(acc[k] ?? '');
                            return actual === expected;
                        }));
                    },

                    initComponent() {
                        this.accounts = this.accounts.map((acc) => ({
                            ...acc,
                            _search: this.normalizeText([acc.tk, acc.ten_tk].join(' ')),
                        }));

                        this.filtered = this.filterAccounts(this.search);

                        // Wait briefly after typing before filtering the client-side list.
                        this.$watch('search', (value) => {
                            clearTimeout(this.filterTimer);
                            this.filterTimer = setTimeout(() => {
                                this.filtered = this.filterAccounts(value);
                                this.highlightedIndex = -1; // Reset highlight khi filter thay đổi
                            }, 500);
                        });
                    },

                    normalizeText(value) {
                        return String(value || '')
                            .normalize('NFD')
                            .replace(/[\u0300-\u036f]/g, '')
                            .replace(/[đĐ]/g, 'd')
                            .toLowerCase()
                            .trim()
                            .replace(/\s+/g, ' ');
                    },

                    openDropdown() {
                        this.filtered = this.filterAccounts(this.search);
                        this.showDropdown = true;
                    },

                    commitSearch() {
                        const exact = this.accounts.find((acc) => String(acc.tk).toLowerCase() === this.search.toLowerCase());
                        if (exact) {
                            this.selectAccount(exact);
                            return;
                        }

                        const top = this.filterAccounts(this.search)[0];
                        if (top) {
                            this.selectAccount(top);
                            return;
                        }

                        this.$wire.set('pTk', this.search || null);
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
                                } else {
                                    this.commitSearch();
                                    this.showDropdown = false;
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
                        this.search = acc.tk;
                        this.showDropdown = false;
                        this.$wire.set('pTk', acc.tk);
                    },

                    filterAccounts(query) {
                        const base = this.applyFilters(this.accounts);
                        if (!query) return base.slice(0, 10);

                        const q = this.normalizeText(query);
                        return base.filter(acc => acc._search.includes(q)).slice(0, 10);
                    }
                }
            }
        })();
    </script>
@endpush
