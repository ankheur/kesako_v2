<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StatutFiche;
use App\Enums\TypeFiche;
use App\Models\Fiche;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FicheFactory extends Factory
{
    protected $model = Fiche::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(),
            'soustitre' => $this->faker->sentence(),
            'illustration' => 'fiche-illustrations/illustration.jpg',
            'alt_illustration' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'contenu' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(TypeFiche::toArray()),
            'statut' => $this->faker->randomElement(StatutFiche::toArray()),
            'published_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function published(?Carbon $date = null): self
    {
        return $this->state(
            fn (array $attributes): array => ['published_at' => $date ?? Carbon::now()]
        );
    }
}
