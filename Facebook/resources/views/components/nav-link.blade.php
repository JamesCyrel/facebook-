@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-1 rounded-md bg-blue-100 text-sm font-medium leading-5 text-blue-800 hover:text-blue-900 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-1 rounded-md text-sm font-medium leading-5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
