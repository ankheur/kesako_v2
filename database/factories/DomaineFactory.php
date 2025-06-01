<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Domaine;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

final class DomaineFactory extends Factory
{
    protected $model = Domaine::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'icone' => 'domaine-icones/'.$this->faker->word().'.png',
            'description' => $this->faker->paragraph(),
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
