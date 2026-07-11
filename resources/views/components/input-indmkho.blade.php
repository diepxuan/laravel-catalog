@props(['disabled' => false])

<div class="relative" x-data="khoInputComponent(@js($inDmKhos), @js($pMa_kho))" x-init="initComponent()"
    @click.outside="showDropdown = false" @keydown.escape.window="showDropdown = false">
    <div class="relative">
        <input {{ $disabled ? 'disabled' : '' }} type="text" x-model="search" @focus="openDropdown()"
            @click="openDropdown()" @change="commitSearch()" @keydown="handleKeydown($event)"
            class="block w-full rounded-md border-gray-300 py-1 pr-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-autocomplete="list" />

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <div x-show="showDropdown && filtered.length > 0" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg"
        style="display: none;" role="listbox" aria-label="Danh sách kho">
        <ul class="py-1">
            <template x-for="(item, index) in filtered" :key="item.ma_kho">
                <li @mousedown.prevent="selectItem(item)" @click.prevent @mouseenter="highlightedIndex = index"
                    :class="{'bg-indigo-50': highlightedIndex === index, 'hover:bg-gray-50': highlightedIndex !== index}"
                    class="cursor-pointer px-4 py-2.5 text-sm" role="option"
                    :aria-selected="highlightedIndex === index" :id="optionId(index)">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex shrink-0 items-center rounded-md bg-indigo-50 px-2 py-0.5 font-mono text-xs font-medium text-indigo-700"
                            x-text="item.ma_kho"></span>
                        <span class="truncate text-gray-700" x-text="item.ten_kho"></span>
                    </div>
                </li>
            </template>
        </ul>
    </div>

    <div x-show="showDropdown && filtered.length === 0 && search.trim().length > 0"
        class="absolute z-50 mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-center text-xs text-gray-500 shadow-lg"
        style="display: none;">
        Không tìm thấy kho
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            if (typeof window.khoInputComponent !== 'undefined') {
                return;
            }

            window.khoInputComponent = function(items, initialSearch = '') {
                return {
                    items: items || [],
                    search: initialSearch || '',
                    showDropdown: false,
                    filtered: [],
                    highlightedIndex: -1,
                    filterTimer: null,
                    optionPrefix: 'kho-option-' + Math.random().toString(36).slice(2),

                    initComponent() {
                        this.items = this.items.map((item) => ({
                            ...item,
                            _search: this.normalizeText([item.ma_kho, item.ten_kho].join(' ')),
                        }));

                        this.filtered = this.filterItems(this.search);

                        this.$watch('search', (value) => {
                            clearTimeout(this.filterTimer);
                            this.filterTimer = setTimeout(() => {
                                this.filtered = this.filterItems(value);
                                this.highlightedIndex = -1;
                            }, 150);
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

                    optionId(index) {
                        return this.optionPrefix + '-' + index;
                    },

                    openDropdown() {
                        this.filtered = this.filterItems(this.search);
                        this.showDropdown = true;
                    },

                    commitSearch() {
                        const exact = this.items.find((item) => String(item.ma_kho).toLowerCase() === this.search.toLowerCase());
                        if (exact) {
                            this.selectItem(exact);
                            return;
                        }

                        const top = this.filterItems(this.search)[0];
                        if (top) {
                            this.selectItem(top);
                            return;
                        }

                        this.$wire.set('pMa_kho', this.search || null);
                    },

                    selectItem(item) {
                        this.search = item.ma_kho;
                        this.showDropdown = false;
                        this.$wire.set('pMa_kho', item.ma_kho);
                    },

                    handleKeydown(event) {
                        if (!this.showDropdown) {
                            if (['ArrowDown', 'ArrowUp'].includes(event.key)) {
                                event.preventDefault();
                                this.openDropdown();
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
                                    this.selectItem(this.filtered[this.highlightedIndex]);
                                } else {
                                    this.commitSearch();
                                    this.showDropdown = false;
                                }
                                break;
                        }
                    },

                    scrollToHighlighted() {
                        this.$nextTick(() => {
                            const el = document.getElementById(this.optionId(this.highlightedIndex));
                            if (el) {
                                el.scrollIntoView({ block: 'nearest' });
                            }
                        });
                    },

                    filterItems(query) {
                        const q = this.normalizeText(query);
                        if (!q) {
                            return this.items.slice(0, 20);
                        }

                        return this.items.filter((item) => item._search.includes(q)).slice(0, 20);
                    }
                };
            };
        })();
    </script>
@endpush
