{{--
    COMPONENT: nav-link
    Link navigasi inline pada navbar Breeze.
    Disesuaikan: indigo → racing-orange, dihapus semua dark: classes.
--}}
@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-racing-orange text-sm font-medium leading-5 text-white focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-white hover:border-racing-orange/50 focus:outline-none focus:text-white focus:border-racing-orange/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
