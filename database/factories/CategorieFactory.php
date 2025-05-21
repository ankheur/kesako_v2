<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TypeCategorie;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CategorieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Categorie::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'denomination' => fake()->word(),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(TypeCategorie::toArray()),
            'slug' => fake()->slug(),
            'published_at' => fake()->dateTime(),
        ];
    }
}
