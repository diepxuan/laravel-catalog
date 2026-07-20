<div class="relative w-full" x-data="chiphiInputComponent(@js($cpList), @js($value), @js($search))" x-init="initComponent()"
    @click.outside="showDropdown = false" @keydown.escape.window="showDropdown = false">
    <div class="relative">
        <input type="text" x-model="search" @focus="openDropdown()" @click="openDropdown()"
            @change="commitSearch()" @keydown="handleKeydown($event)" placeholder="{{ $placeholder }}"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-14 text-sm text-gray-700 shadow-sm transition-all placeholder:text-gray-400 hover:border-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-autocomplete="list" />

        <div class="absolute right-2 top-1/2 flex -translate-y-1/2 items-center gap-1">
            <button type="button" x-show="selectedValue" @click="clear()"
                class="rounded-full p-0.5 text-gray-400 hover:text-gray-600" style="display: none;">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <div x-show="showDropdown && filtered.length > 0" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full overflow-hidden rounded-md border border-gray-300 bg-white shadow-lg"
        style="display: none;" role="listbox" aria-label="Danh sách chi phí mua hàng">
        <ul class="max-h-64 overflow-y-auto">
            <template x-for="(cp, index) in filtered" :key="cp.ma_cp">
                <li @mousedown.prevent="selectCp(cp)" @click.prevent @mouseenter="highlightedIndex = index"
                    :class="{'bg-blue-50': highlightedIndex === index, 'hover:bg-blue-50': highlightedIndex !== index}"
                    class="cursor-pointer border-b border-gray-100 px-3 py-2 transition-colors last:border-b-0"
                    role="option" :aria-selected="highlightedIndex === index" :id="optionId(index)">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium text-gray-800" x-text="cp.ten_cp"></p>
                            <p class="truncate text-xs text-gray-500">
                                <template x-if="cp.tk_cp">
                                    <span x-text="'TK: ' + cp.tk_cp"></span>
                                </template>
                            </p>
                        </div>
                        <span class="shrink-0 rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600"
                            x-text="cp.ma_cp"></span>
                    </div>
                </li>
            </template>
        </ul>
    </div>

    <div x-show="showDropdown && filtered.length === 0 && search.trim().length > 0"
        class="absolute z-50 mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-center text-xs text-gray-500 shadow-lg"
        style="display: none;">
        Không tìm thấy
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            if (typeof window.chiphiInputComponent !== 'undefined') {
                return;
            }

            window.chiphiInputComponent = function(cpList, initialValue = null, initialSearch = '') {
                return {
                    cpList: cpList || [],
                    selectedValue: initialValue || null,
                    search: initialSearch || '',
                    showDropdown: false,
                    filtered: [],
                    highlightedIndex: -1,
                    filterTimer: null,
                    optionPrefix: 'chiphi-option-' + Math.random().toString(36).slice(2),

                    initComponent() {
                        this.cpList = this.cpList.map((cp) => ({
                            ...cp,
                            _search: this.normalizeText([cp.ma_cp, cp.ten_cp, cp.tk_cp].join(' ')),
                        }));

                        this.filtered = this.filterCps(this.search);

                        this.$wire.$watch('search', (value) => {
                            this.search = value || '';
                            this.selectedValue = this.$wire.get('value') || null;
                        });

                        this.$watch('search', (value) => {
                            clearTimeout(this.filterTimer);
                            this.filterTimer = setTimeout(() => {
                                this.filtered = this.filterCps(value);
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
                        this.filtered = this.filterCps(this.search);
                        this.showDropdown = true;
                    },

                    commitSearch() {
                        const exact = this.cpList.find((cp) => String(cp.ma_cp).toLowerCase() === this.search.toLowerCase());
                        if (exact) {
                            this.selectCp(exact);
                            return;
                        }

                        const top = this.filterCps(this.search)[0];
                        if (top) {
                            this.selectCp(top);
                            return;
                        }

                        this.selectedValue = null;
                        this.$wire.clear();
                    },

                    clear() {
                        this.selectedValue = null;
                        this.search = '';
                        this.filtered = [];
                        this.showDropdown = false;
                        this.$wire.clear();
                    },

                    selectCp(cp) {
                        this.selectedValue = cp.ma_cp;
                        this.search = cp.ten_cp || cp.ma_cp;
                        this.showDropdown = false;
                        this.$wire.selectCp(cp.ma_cp, cp.ten_cp || cp.ma_cp);
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
                                    this.selectCp(this.filtered[this.highlightedIndex]);
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

                    filterCps(query) {
                        const q = this.normalizeText(query);
                        if (!q) {
                            return this.cpList.slice(0, 20);
                        }

                        return this.cpList.filter((cp) => cp._search.includes(q)).slice(0, 20);
                    }
                };
            };
        })();
    </script>
@endpush
