<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class BlockedDate extends Model
{
    use HasFactory;

    protected $table = 'blocked_dates';

    protected $fillable = [
        'date',
        'reason',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function isBlocked(string $date): bool
    {
        return static::whereDate('date', $date)
            ->where('is_active', true)
            ->exists();
    }

    public static function getBlockedReason(string $date): ?string
    {
        return static::whereDate('date', $date)
            ->where('is_active', true)
            ->value('reason');
    }

    protected static function booted(): void
    {
        static::saved(function (BlockedDate $item) {
            Cache::forget("slot_quota_{$item->date->format('Y-m-d')}");
            Cache::forget("blocked_date_{$item->date->format('Y-m-d')}");
        });

        static::deleted(function (BlockedDate $item) {
            Cache::forget("slot_quota_{$item->date->format('Y-m-d')}");
            Cache::forget("blocked_date_{$item->date->format('Y-m-d')}");
        });
    }
}
