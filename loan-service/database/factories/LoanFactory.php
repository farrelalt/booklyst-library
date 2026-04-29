<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => rand(1, 10),
            'book_id' => rand(1, 20),

            'loan_date' => now(),

            'return_date' => null,

            'status' => fake()->randomElement([
                'borrowed',
                'returned',
                'overdue'
            ]),
        ];
    }
}
