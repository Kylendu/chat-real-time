@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full px-4 py-3 text-start text-sm font-medium rounded-lg bg-blue-50 text-blue-700 border border-blue-200 transition-all duration-200'
            : 'block w-full px-4 py-3 text-start text-sm font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
