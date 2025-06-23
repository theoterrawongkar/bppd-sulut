<?php

namespace Database\Factories;

use App\Models\TourPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TourOperatingHour>
 */
class TourOperatingHourFactory extends Factory
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
            'day' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            'open_time' => '08:00',
            'close_time' => '18:00',
            'is_open' => true,
        ];
    }
}
