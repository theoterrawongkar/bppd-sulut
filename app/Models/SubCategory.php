<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    /** @use HasFactory<\Database\Factories\SubCategoryFactory> */
    use HasFactory, Sluggable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function culinaryPlaces()
    {
        return $this->hasMany(CulinaryPlace::class);
    }

    public function tourPlaces()
    {
        return $this->hasMany(TourPlace::class);
    }

    public function eventPlaces()
    {
        return $this->hasMany(EventPlace::class);
    }
}
