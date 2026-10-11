<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * Model TanggalDiblokir (Penutupan Tanggal/Slot Libur Workshop)
 *
 * @property int $id
 * @property \Carbon\Carbon $date Tanggal yang diblokir
 * @property string|null $reason Alasan penutupan tanggal
 * @property bool $is_active Status aktif pemblokiran
 * @property int|null $created_by ID user pembuat blokir
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class TanggalDiblokir extends Model
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
        static::saved(function (TanggalDiblokir $item) {
            Cache::forget("slot_quota_{$item->date->format('Y-m-d')}");
            Cache::forget("blocked_date_{$item->date->format('Y-m-d')}");
        });

        static::deleted(function (TanggalDiblokir $item) {
            Cache::forget("slot_quota_{$item->date->format('Y-m-d')}");
            Cache::forget("blocked_date_{$item->date->format('Y-m-d')}");
        });
    }
}
