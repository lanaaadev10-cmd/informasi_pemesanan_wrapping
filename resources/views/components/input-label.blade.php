{{--
    COMPONENT: input-label
    Label field input form, tema racing dark.
    Sudah sesuai tema, hanya dibersihkan dari Breeze default.

    Penggunaan:
        <x-input-label for="email" value="Email" />
        <x-input-label for="name">Nama Lengkap</x-input-label>
--}}
@props(['value'])

<label {{ $attributes->merge(['class' => 'label-form']) }}>
    {{ $value ?? $slot }}
</label>
