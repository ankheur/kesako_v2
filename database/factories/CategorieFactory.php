<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TypeCategorie;
use App\Models\Categorie;
use App\Models\Fiche;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categorie>
 */
final class CategorieFactory extends Factory
{
    protected $model = Categorie::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var TypeCategorie $typeCategorie */
        $typeCategorie = $this->faker->randomElement(TypeCategorie::cases());
        $exemples = $typeCategorie->getExemples();
        /** @var string $titre */
        $titre = $this->faker->randomElement($exemples);

        return [
            'titre' => $titre,
            'slug' => $this->faker->slug(),
            'type_categorie' => $typeCategorie,
            'description' => $this->faker->sentence(10),
            'fiche_id' => null,
            'ordre' => $this->faker->numberBetween(1, 20),
            'published_at' => null,
        ];
    }

    /**
     * Indique que la catégorie est publiée
     */
    public function published(?DateTimeInterface $date = null): static
    {
        return $this->state(fn(): array => [
            'published_at' => $date ?? now(),
        ]);
    }

    /**
     * Catégorie de type spécifique
     */
    public function ofType(TypeCategorie $type): static
    {
        return $this->state(function () use ($type): array {
            /** @var string $titre */
            $titre = $this->faker->randomElement($type->getExemples());

            return [
                'type_categorie' => $type,
                'titre' => $titre,
                'slug' => $this->faker->slug(),
            ];
        });
    }

    /**
     * Catégorie avec fiche explicative
     */
    public function withFiche(): static
    {
        return $this->state(fn(): array => [
            'fiche_id' => Fiche::factory()->published(),
        ]);
    }
}
