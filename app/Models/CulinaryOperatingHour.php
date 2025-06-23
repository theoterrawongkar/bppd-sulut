<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulinaryOperatingHour extends Model
{
    /** @use HasFactory<\Database\Factories\CulinaryOperatingHourFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'culinary_place_id',
        'day',
        'open_time',
        'close_time',
        'is_open'
    ];

    public function place()
    {
        return $this->belongsTo(CulinaryPlace::class, 'culinary_place_id');
    }
}
