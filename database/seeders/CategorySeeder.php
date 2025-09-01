<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First create some categories
        $categories = Category::factory(200)->create();

        // Then create posts assigned to random categories
        Post::factory(2000)->create([
            'category_id' => fn() => $categories->random()->id,
        ]);
    }
}
