@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-stone-500 text-start text-base font-medium text-neutral-900 bg-stone-100 focus:outline-none focus:text-neutral-950 focus:bg-stone-200 focus:border-stone-800 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:text-gray-200 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 dark:text-gray-200 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
