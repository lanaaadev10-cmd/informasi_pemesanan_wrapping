{{--
    COMPONENT: responsive-nav-link
    Link navigasi mobile drawer pada navbar Breeze.
    Disesuaikan: indigo → racing-orange, dihapus semua dark: classes.
--}}
@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-racing-orange text-start text-base font-medium text-racing-orangeLight bg-racing-orange/10 focus:outline-none transition duration-150 ease-in-out'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 hover:border-racing-orange/50 focus:outline-none focus:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
