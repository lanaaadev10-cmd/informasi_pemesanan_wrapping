<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingMedia extends Model
{
    use HasFactory;

    protected $table = 'rating_medias';

    protected $fillable = [
        'id_rating',
        'path',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class, 'id_rating', 'id');
    }
}
