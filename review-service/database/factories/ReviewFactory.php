<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'member_id' => $this->faker->numberBetween(1, 10),
            'book_id'   => $this->faker->numberBetween(1, 20),
            'rating'    => $this->faker->numberBetween(1, 5),
            'comment'   => $this->faker->sentence(10),
        ];
    }
}