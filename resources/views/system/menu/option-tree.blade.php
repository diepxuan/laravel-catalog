@foreach ($items as $item)
    <option value="{{ $item->id }}">
        {{ str_repeat(' ', $level ?? 0) }}
        @if($level ?? 0 > 0)
            ↳ 
        @endif
        {{ $item->name }}
        @if($item->route)
            ({{ $item->route }})
        @endif
    </option>
    @if (!empty($item->children))
        @include('catalog::system.menu.option-tree', [
            'items' => $item->children,
            'level' => ($level ?? 0) + 1,
        ])
    @endif
@endforeach
