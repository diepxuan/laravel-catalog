@props(['options' => [], 'selected' => null, 'placeholder' => null, 'class' => ''])

<select {!! $attributes->merge([
    'class' => "rounded-md shadow-sm $class",
]) !!}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected($value == $selected)>
            {{ $label }}
        </option>
    @endforeach
</select>
