<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eigo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genre',
        'year_released',
        'rating',
        'times_watched',
        'recommended'
    ];

    protected $casts = [
        'year_released' => 'integer',
        'rating' => 'integer',
        'times_watched' => 'integer',
        'recommended' => 'integer',
    ];
}
