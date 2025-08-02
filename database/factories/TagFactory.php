<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Fiche;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tag>
 */
final class TagFactory extends Factory
{
    protected $model = Tag::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var string $nom */
        $nom = $this->faker->randomElement([
            'Empereurs romains',
            'Rois de France',
            'Poésie romantique',
            'Impressionnisme',
            'Révolutions françaises',
            'Compositeurs classiques',
            'Philosophes antiques',
            'Peintres italiens',
            'Jazz américain',
            'Architecture gothique',
        ]);

        return [
            'nom' => $nom,
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(8),
            'fiche_id' => null,
            'ordre' => $this->faker->numberBetween(1, 100),
            'is_featured' => false,
            'is_active' => true,
        ];
    }

    /**
     * Tag en vedette
     */
    public function featured(): static
    {
        return $this->state(fn(): array => [
            'is_featured' => true,
            'ordre' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Tag inactif
     */
    public function inactive(): static
    {
        return $this->state(fn(): array => [
            'is_active' => false,
        ]);
    }

    /**
     * Tag avec fiche collection
     */
    public function withFiche(): static
    {
        return $this->state(fn(): array => [
            'fiche_id' => Fiche::factory()->published(),
        ]);
    }
}
