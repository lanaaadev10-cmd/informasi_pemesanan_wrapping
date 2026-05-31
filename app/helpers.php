<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('resolveImageUrl')) {
    function resolveImageUrl($path, $fallback = null): ?string
    {
        if (empty($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        $altPath = 'images/' . $path;
        if (Storage::disk('public')->exists($altPath)) {
            return asset('storage/' . $altPath);
        }

        return $fallback;
    }
}
