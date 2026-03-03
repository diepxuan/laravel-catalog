@props(['disabled' => false, 'class' => '', 'placeholder' => 'Nhập mã tài khoản...'])

<div class="relative" x-data="{ 
        showDropdown: @entangle('showDropdown'), 
        selectedIndex: @entangle('selectedIndex'),
        hideWithDelay() {
            setTimeout(() => { this.showDropdown = false }, 200)
        }
    }"
    x-on:keydown.escape.window="showDropdown = false"
    x-on:hide-dropdown.window="setTimeout(() => { showDropdown = false }, $event.detail.delay)">
    
    <!-- Input field -->
    <div class="relative">
        <input 
            {{ $disabled ? 'disabled' : '' }} 
            type="text"
            placeholder="{{ $placeholder }}"
            class="block w-full rounded-md border-gray-300 py-0 pr-8 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $class }}"
            wire:model.live.debounce.300ms="search"
            wire:click="showDropdown"
            wire:keydown.arrow-down.prevent="handleKeydown('ArrowDown')"
            wire:keydown.arrow-up.prevent="handleKeydown('ArrowUp')"
            wire:keydown.enter.prevent="handleKeydown('Enter')"
            wire:keydown.escape="handleKeydown('Escape')"
            @click="showDropdown = true"
            @blur="hideWithDelay()" />
        
        <!-- Search icon -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Dropdown -->
    <div x-show="showDropdown" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg max-h-60 overflow-auto"
         style="display: none;">
        
        @if(count($filteredTks) > 0)
            <ul class="py-1">
                @foreach($filteredTks as $index => $tk)
                    <li>
                        <a href="#" 
                           class="flex items-center justify-between px-4 py-2.5 text-sm {{ $index === $selectedIndex ? 'bg-indigo-50' : 'hover:bg-gray-50' }}"
                           @click.prevent="$wire.selectTk(@js($tk)); showDropdown = false;"
                           @mouseenter="selectedIndex = {{ $index }}">
                            <div class="flex items-center gap-3">
                                <!-- Account code badge -->
                                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 font-mono">
                                    {{ $tk['tk'] }}
                                </span>
                                <!-- Account name -->
                                <span class="text-gray-700">{{ $tk['ten_tk'] }}</span>
                            </div>
                            <!-- Selected indicator -->
                            @if($pTk == $tk['tk'])
                                <svg class="h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            @if(count($filteredTks) >= 10)
                <div class="border-t border-gray-100 px-4 py-2 bg-gray-50">
                    <p class="text-xs text-gray-500">Hiển thị 10 kết quả đầu tiên. Vui lòng nhập cụ thể hơn...</p>
                </div>
            @endif
        @else
            <div class="px-4 py-8 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Không tìm thấy tài khoản nào</p>
                <p class="text-xs text-gray-400">Thử tìm kiếm với từ khóa khác</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[x-data]')) {
                Alpine.store('dropdown', { show: false });
            }
        });
    });
</script>
@endpush
