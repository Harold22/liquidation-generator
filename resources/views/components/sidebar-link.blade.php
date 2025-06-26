@props(['active' => false, 'icon' => null])

@php
    $classes = $active
        ? 'bg-blue-200 text-blue-700 dark:bg-blue-800 dark:text-white border-l-4 border-blue-500'
        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-blue-400';
@endphp

<a {{ $attributes->merge(['class' => "$classes flex items-center group space-x-3 px-4 py-2 rounded-md transition-all duration-200"]) }}>
    <div class="flex-shrink-0 p-1 rounded-md group-hover:bg-blue-100 dark:group-hover:bg-blue-700">
        {!! $icon !!}
    </div>
    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-all duration-300">
        {{ $slot }}
    </span>
</a>
