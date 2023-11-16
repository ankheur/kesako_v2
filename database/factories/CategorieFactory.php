<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class CategorieFactory extends Factory
{
    protected $model = Categorie::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'icone' => 'categorie-icones/' . $this->faker->word() . '.png',
            'description' => $this->faker->paragraph(),
            'published_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function published(Carbon $date = null): self
    {
        return $this->state(
            fn (array $attributes) => ['published_at' => $date ?? Carbon::now()]
        );
    }
}
