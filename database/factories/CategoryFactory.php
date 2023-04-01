<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'       => User::factory(),
            'name'          => fake()->text(40),
            'description'   => fake()->text(80),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ];
    }
}
