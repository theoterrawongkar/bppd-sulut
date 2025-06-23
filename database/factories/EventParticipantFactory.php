<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\EventPlace;
use App\Models\ArtistProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventParticipant>
 */
class EventParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'event_place_id' => EventPlace::inRandomOrder()->first()->id,
            'artist_profile_id' => ArtistProfile::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['Menunggu Persetujuan', 'Diterima', 'Ditolak']),
        ];
    }
}
