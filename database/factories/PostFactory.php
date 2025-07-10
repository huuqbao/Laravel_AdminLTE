<?php

namespace Database\Factories; // ✅ Bắt buộc phải có

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;

        return [
            'user_id' => 1, // Or use User::factory() if needed
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => $this->faker->text(100),
            'content' => $this->faker->paragraphs(3, true),
            'publish_date' => now(),
            'status' => 0,
        ];
    }
}
