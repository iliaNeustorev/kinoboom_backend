<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(3, true),
            'slug' => $this->faker->word(3, true),
            'description' => $this->faker->sentence(10),
            'picture' => 'nopicture.png',
            'video' => 'novideo.png',
            'rating' => $this->faker->randomFloat(2, 1, 666666,66),
            'year_release' => $this->faker->randomNumber(2,3)           
        ];
    }
}
