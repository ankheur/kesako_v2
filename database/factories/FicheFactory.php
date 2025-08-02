<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StatusFiche;
use App\Enums\TypeFiche;
use App\Models\Fiche;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fiche>
 */
final class FicheFactory extends Factory
{
    protected $model = Fiche::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titre = $this->faker->sentence(3, false);

        return [
            'titre' => $titre,
            'soustitre' => $this->faker->optional(0.7)->sentence(4, false),
            'slug' => $this->faker->slug(),
            'illustration' => $this->faker->optional(0.8)->randomElement([
                'illustrations/fiche-1.jpg',
                'illustrations/fiche-2.jpg',
                'illustrations/fiche-3.jpg',
                'illustrations/fiche-4.jpg',
            ]),
            'illustration_alt' => function (array $attributes): ?string {
                /** @var string|null $illustration */
                $illustration = $attributes['illustration'] ?? null;
                /** @var string $titre */
                $titre = $attributes['titre'];

                return $illustration !== null ? 'Illustration de '.$titre : null;
            },
            'description' => $this->faker->paragraph(2),
            'contenu' => $this->generateRichContent(),
            'type_fiche' => $this->faker->randomElement(TypeFiche::cases()),
            'status' => StatusFiche::BROUILLON,
            'published_at' => null,
            'featured_at' => null,
        ];
    }

    /**
     * Indique que la fiche est publiée
     */
    public function published(?DateTimeInterface $date = null): static
    {
        return $this->state(fn(): array => [
            'status' => StatusFiche::PUBLIE,
            'published_at' => $date ?? now(),
        ]);
    }

    /**
     * Indique que la fiche est en relecture
     */
    public function inReview(): static
    {
        return $this->state(fn(): array => [
            'status' => StatusFiche::EN_REVIEW,
        ]);
    }

    /**
     * Indique que la fiche est en vedette
     */
    public function featured(?DateTimeInterface $date = null): static
    {
        return $this->state(fn(): array => [
            'featured_at' => $date ?? now(),
            'status' => StatusFiche::PUBLIE,
            'published_at' => $date ?? now(),
        ]);
    }

    /**
     * Fiche de type spécifique
     */
    public function ofType(TypeFiche $type): static
    {
        return $this->state(fn(): array => [
            'type_fiche' => $type,
            'contenu' => $this->generateContent(),
        ]);
    }

    /**
     * Génère du contenu riche HTML
     */
    private function generateRichContent(): string
    {
        $paragraphs = [];

        for ($i = 0; $i < $this->faker->numberBetween(3, 8); $i++) {
            $paragraphs[] = '<p>'.$this->faker->paragraph(6).'</p>';

            // Parfois ajouter un titre
            if ($this->faker->boolean(30)) {
                $paragraphs[] = '<h2>'.$this->faker->sentence(3, false).'</h2>';
            }

            // Parfois ajouter une liste
            if ($this->faker->boolean(20)) {
                $items = [];
                for ($j = 0; $j < $this->faker->numberBetween(2, 5); $j++) {
                    $items[] = '<li>'.$this->faker->sentence().'</li>';
                }
                $paragraphs[] = '<ul>'.implode('', $items).'</ul>';
            }
        }

        return implode("\n", $paragraphs);
    }

    /**
     * Génère du contenu
     */
    private function generateContent(): string
    {
        return implode("\n", [
            '<h2>Bla</h2>',
            '<p>'.$this->faker->paragraph(4).'</p>',
            '<h2>Blabla</h2>',
            '<p>'.$this->faker->paragraph(6).'</p>',
            '<h2>Blablabla</h2>',
            '<p>'.$this->faker->paragraph(4).'</p>',
        ]);
    }
}
