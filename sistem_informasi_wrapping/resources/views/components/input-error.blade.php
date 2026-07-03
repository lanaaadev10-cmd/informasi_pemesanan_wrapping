@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-2 mt-1.5 text-xs text-red-400 animate-fade-in']) }}>
        <span class="shrink-0 mt-[1px]">⚠️</span>
        <span>
            @foreach ((array) $messages as $message)
                {{ $message }}@if (!$loop->last)<br>@endif
            @endforeach
        </span>
    </div>
@endif
