@push('title')
    {{ $slot ?? config('app.name', 'Laravel') }}
@endpush

