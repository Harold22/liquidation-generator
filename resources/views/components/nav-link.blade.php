@props(['active'])

@php
    $classes = $active
        ? 'block px-4 py-2 rounded-md bg-blue-100 text-blue-700 font-semibold'
        : 'block px-4 py-2 rounded-md text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-gray-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
