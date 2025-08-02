<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Domaine;
use App\Models\Fiche;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Domaine>
 */
final class DomaineFactory extends Factory
{
    protected $model = Domaine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var string $titre */
        $titre = $this->faker->randomElement([
            'Histoire',
            'Littérature',
            'Musique',
            'Sciences',
            'Arts',
            'Philosophie',
            'Politique',
            'Géographie',
        ]);

        return [
            'titre' => $titre,
            'slug' => $this->faker->slug(),
            'icone' => 'domaines/'.Str::slug($titre).'.svg',
            'description' => $this->faker->sentence(12),
            'couleur' => $this->faker->hexColor(),
            'fiche_id' => null,
            'published_at' => null,
            'ordre' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Indique que le domaine est publié
     */
    public function published(?DateTimeInterface $date = null): static
    {
        return $this->state(fn(): array => [
            'published_at' => $date ?? now(),
        ]);
    }

    /**
     * Indique que le domaine est en vedette (ordre prioritaire)
     */
    public function featured(): static
    {
        return $this->state(fn(): array => [
            'ordre' => 0,
            'published_at' => now(),
        ]);
    }

    /**
     * Domaine avec fiche explicative
     */
    public function withFiche(): static
    {
        return $this->state(fn(): array => [
            'fiche_id' => Fiche::factory()->published(),
        ]);
    }
}
