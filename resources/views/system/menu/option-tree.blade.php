@foreach ($items as $item)
    <option value="{{ $item['id'] }}">{{ str_repeat('— ', $level ?? 0) }}{{ $item['display'] }}</option>
    @if (!empty($item['children']))
        @include('catalog::system.menu.option-tree', [
            'items' => $item['children'],
            'level' => ($level ?? 0) + 1,
        ])
    @endif
@endforeach
