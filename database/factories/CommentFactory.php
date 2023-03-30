<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'post_id'       => Post::factory(),
            'comment'       => fake()->text(),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ];
    }

    //$table->id();
    //            $table->foreignId('user_id');
    //            $table->foreignId('post_id');
    //            $table->text('comment');
    //            $table->timestamps();
}
