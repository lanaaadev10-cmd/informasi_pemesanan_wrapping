{{--
    COMPONENT: auth-session-status
    Menampilkan pesan status sesi (mis. "Link reset dikirim").
    Tema disesuaikan: green-400 untuk dark background.
    Removed: dark:text-green-400 (tidak relevan, selalu dark bg)

    Penggunaan:
        <x-auth-session-status :status="session('status')" />
--}}
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-400']) }}>
        {{ $status }}
    </div>
@endif
