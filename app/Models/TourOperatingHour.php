<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourOperatingHour extends Model
{
    /** @use HasFactory<\Database\Factories\TourOperatingHourFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tour_place_id',
        'day',
        'open_time',
        'close_time',
        'is_open',
    ];

    public function place()
    {
        return $this->belongsTo(TourPlace::class, 'tour_place_id');
    }
}
