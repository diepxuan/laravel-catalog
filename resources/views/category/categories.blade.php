<div wire:init="loadData" class="relative ml-4 my-2">
    <div class="flex w-100 items-center">
        <x-input-switch :checked="$enabled" wire:change="toggleEnabled" />
        <div class="inline-flex">
            @if ($category->urlPath)
                <a href="https://www.diepxuan.com{{ $category->urlPath }}.html" class="mx-4 text-decoration-none"
                    target="_blank">
                    <strong>{{ $category->name }}</strong>
                    <small>{{ $category->sku }}</small>
                </a>
            @else
                <div class="mx-4">
                    <strong>{{ $category->name }}</strong>
                    <small>{{ $category->sku }}</small>
                </div>
            @endif
            @foreach ($magento as $m2child)
                <span class="mx-2">
                    <small>{{ $m2child->id }}({{ $m2child->path }})</small>
                </span>
            @endforeach
        </div>
    </div>
    @if ($childrens->isNotEmpty())
        <div class="ms-4 border-l border-gray-300">
            @foreach ($childrens as $child)
                @livewire('catalog::category.categories', ['category' => $child], key($child->id))
            @endforeach
        </div>
    @endif
</div>
