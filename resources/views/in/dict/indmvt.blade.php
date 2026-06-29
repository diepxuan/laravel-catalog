<div class="flex w-full flex-col gap-4">
    <x-head-title>{{ 'Hàng hóa, vật tư' }}</x-head-title>

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Hàng hóa, vật tư</h2>
                <p class="text-sm text-gray-500">INDMVT - Danh mục vật tư - menu IN 14.90.02</p>
            </div>
        </div>
    </x-slot>

    <livewire:catalog::in.dict.indmvt-rename />
    <livewire:catalog::in.dict.indmvt-form />
    <livewire:catalog::in.dict.indmvt-list />

    <button
        type="button"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-4 right-4 z-40 rounded-full border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-lg hover:bg-gray-50"
    >
        Lên đầu
    </button>

    <script>
        document.addEventListener('livewire:init', () => {
            if (window.__indmvtListenersRegistered) {
                return;
            }
            window.__indmvtListenersRegistered = true;

            Livewire.on('indmvt-form-opened', () => {
                requestAnimationFrame(() => {
                    document.getElementById('indmvt-form')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });

            Livewire.on('indmvt-rename-opened', () => {
                requestAnimationFrame(() => {
                    document.getElementById('indmvt-rename-form')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });
        });
    </script>
</div>
