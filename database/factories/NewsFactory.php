<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'photo_path' => null,
            'title' => fake()->sentence(6),
            'content' => fake()->paragraphs(3, true),
            'publish_date' => fake()->dateTimeBetween('-30 days', '+10 days')->format('Y-m-d'),
        ];
    }

    public function published(): self
    {
        return $this->state(fn () => [
            'publish_date' => now()->subDay()->toDateString(),
        ]);
    }

    public function scheduled(): self
    {
        return $this->state(fn () => [
            'publish_date' => now()->addDay()->toDateString(),
        ]);
    }
}
