<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulinaryImage extends Model
{
    /** @use HasFactory<\Database\Factories\CulinaryImageFactory> */
    use HasFactory;

    protected $fillable = [
        'culinary_place_id',
        'image',
    ];

    public function place()
    {
        return $this->belongsTo(CulinaryPlace::class, 'culinary_place_id');
    }
}
