{{--
    COMPONENT: text-input
    Input teks standar, tema racing dark.
    Menggantikan tema Breeze default (gray/indigo/dark:).

    Penggunaan:
        <x-text-input id="email" type="email" name="email" :value="old('email')" required />
        <x-text-input class="mt-2" ... />   ← tambahan class via merge
--}}
@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2.5 bg-racing-input border border-white/10 rounded-xl
                    text-white text-sm placeholder-gray-600 outline-none transition-all
                    focus:ring-1 focus:ring-racing-orangeLight focus:border-racing-orangeLight
                    disabled:opacity-50 disabled:cursor-not-allowed',
    ]) }}
>
