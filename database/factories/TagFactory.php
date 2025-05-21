<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'denomination' => fake()->word(),
            'description' => fake()->paragraph(),
            'slug' => fake()->slug(),
            'published_at' => fake()->dateTime(),
        ];
    }
}
