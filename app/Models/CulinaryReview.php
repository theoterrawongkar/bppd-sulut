<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulinaryReview extends Model
{
    /** @use HasFactory<\Database\Factories\CulinaryReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'culinary_place_id',
        'rating',
        'comment',
    ];

    public function place()
    {
        return $this->belongsTo(CulinaryPlace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
