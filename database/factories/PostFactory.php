<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(),
            'slug'        => $this->faker->unique()->slug(),
            'description' => $this->faker->paragraph(),
            'category_id' => Category::factory(), // auto create category if not provided
        ];
    }
}
