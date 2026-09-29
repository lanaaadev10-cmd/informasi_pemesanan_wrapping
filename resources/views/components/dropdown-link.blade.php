{{--
    COMPONENT: dropdown-link
    Item tautan di dalam x-dropdown menu.
    Tema: Dark racing theme (text-gray-300 hover:text-white hover:bg-racing-cardLight).
--}}
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2.5 text-start text-xs font-medium text-gray-300 hover:text-white hover:bg-racing-cardLight focus:outline-none focus:bg-racing-cardLight transition duration-150 ease-in-out']) }}>
    {{ $slot }}
</a>
