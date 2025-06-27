<?php

namespace Database\Factories;

use App\Models\TourPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TourImage>
 */
class TourImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tour_place_id' => TourPlace::inRandomOrder()->first()->id,
            'image' => 'sample/placeholder.webp',
        ];
    }
}
