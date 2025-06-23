<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtistProfile>
 */
class ArtistProfileFactory extends Factory
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
            'stage_name' => fake()->name(),
            'owner_email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'portfolio_path' => 'portfolios/sample.pdf',
            'field' => fake()->randomElement(['Seni Rupa', 'Seni Musik', 'Seni Tari', 'Seni Teater', 'Seni Sastra', 'Seni Media/Audiovisual']),
            'description' => fake()->paragraph(),
            'instagram_link' => 'https://instagram.com/' . fake()->userName(),
            'facebook_link' => 'https://facebook.com/' . fake()->userName(),
            'status' => fake()->randomElement(['Menunggu Persetujuan', 'Ditolak', 'Diterima', 'Berhenti Permanen']),
        ];
    }
}
