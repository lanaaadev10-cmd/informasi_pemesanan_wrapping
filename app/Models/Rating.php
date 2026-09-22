<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'id_user',
        'id_pesanan',
        'id_layanan',
        'order_ref',
        'rating',
        'ulasan',
        'balasan_admin',
        'dibalas_at',
        'is_tampil',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_tampil' => 'boolean',
        'dibalas_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('testimoni_ratings');
        });
        static::deleted(function () {
            Cache::forget('testimoni_ratings');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    public function medias()
    {
        return $this->hasMany(RatingMedia::class, 'id_rating', 'id');
    }

    public function getTipeRatingAttribute(): string
    {
        return $this->id_pesanan ? 'Pesanan' : 'Layanan';
    }
}
