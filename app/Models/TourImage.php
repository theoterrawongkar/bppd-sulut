<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourImage extends Model
{
    /** @use HasFactory<\Database\Factories\TourImageFactory> */
    use HasFactory;

    protected $fillable = [
        'tour_place_id',
        'image',
    ];

    public function place()
    {
        return $this->belongsTo(TourPlace::class, 'tour_place_id');
    }
}
