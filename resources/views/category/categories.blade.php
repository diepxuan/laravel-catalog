<div class="list-group list-group-flush border-top border-start">
    <div class="d-flex w-100 align-items-center justify-content-between">
        <x-input class="form-check-input" type="checkbox" role="switch" />
        @if ($category->urlPath)
            <a href="https://www.diepxuan.com{{ $category->urlPath }}.html" class="text-decoration-none" target="_blank">
                <strong class="mb-1">{{ $category->name }}</strong>
                <small class="mb-1"> {{ $category->sku }}</small>
            </a>
        @else
            <strong class="mb-1">{{ $category->name }}</strong>
            <small class="mb-1"> {{ $category->sku }}</small>
        @endif
    </div>
    @if ($childrens->isNotEmpty())
        <div class="ps-4 border-start">
            @foreach ($childrens as $child)
                @livewire('catalog::category.categories', ['category' => $child])
            @endforeach
        </div>
    @endif
</div>
