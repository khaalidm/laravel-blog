<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(2, 6));
        foreach ($paragraphs as $para) {
            $body .= "<p>{$para}</p>";
        }

        return [
            'user_id'       => User::factory(),
            'category_id'   => Category::factory(),
            'title'         => $this->faker->realText(50),
            'description'   => $this->faker->realText(120),
            'body'          => $body,
            'active'        => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()

        ];
    }
}
