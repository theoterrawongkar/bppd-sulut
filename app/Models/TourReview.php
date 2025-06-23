<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourReview extends Model
{
    /** @use HasFactory<\Database\Factories\TourReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_place_id',
        'rating',
        'comment',
    ];

    public function place()
    {
        return $this->belongsTo(TourPlace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
