<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TypeLien;
use App\Models\Element;
use App\Models\Fiche;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Element>
 */
final class ElementFactory extends Factory
{
    protected $model = Element::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fiche_id' => Fiche::factory(),
            'nom' => $this->faker->sentence(2, false),
            'complement' => $this->faker->optional(0.6)->randomElement([
                $this->faker->year(),
                'Réalisateur',
                'Acteur principal',
                'Compositeur',
                'Auteur',
            ]),
            'image' => $this->faker->optional(0.7)->randomElement([
                'elements/element-1.jpg',
                'elements/element-2.jpg',
                'elements/element-3.jpg',
            ]),
            'image_alt' => function (array $attributes): ?string {
                /** @var string|null $image */
                $image = $attributes['image'] ?? null;
                /** @var string $nom */
                $nom = $attributes['nom'];

                return $image !== null ? 'Image de '.$nom : null;
            },
            'fiche_liee_id' => null,
            'lien_externe' => null,
            'type_lien' => null,
            'ordre' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Élément avec fiche liée
     */
    public function withFiche(): static
    {
        return $this->state(fn(): array => [
            'fiche_liee_id' => Fiche::factory()->published(),
        ]);
    }

    /**
     * Élément avec lien externe
     */
    public function withLink(string $url, ?TypeLien $type = null): static
    {
        return $this->state(fn(): array => [
            'lien_externe' => $url,
            'type_lien' => $type ?? TypeLien::detectFromUrl($url),
        ]);
    }

    /**
     * Élément musical avec Spotify
     */
    public function spotify(): static
    {
        return $this->withLink(
            'https://open.spotify.com/track/'.$this->faker->bothify('???????'),
            TypeLien::SPOTIFY
        );
    }

    /**
     * Élément avec YouTube
     */
    public function youtube(): static
    {
        return $this->withLink(
            'https://www.youtube.com/watch?v='.$this->faker->bothify('???????????'),
            TypeLien::YOUTUBE
        );
    }

    /**
     * Élément avec Wikipedia
     */
    public function wikipedia(): static
    {
        /** @var array<string> $wordsArray */
        $wordsArray = $this->faker->words(2);
        $words = implode(' ', $wordsArray);

        return $this->withLink(
            'https://fr.wikipedia.org/wiki/'.Str::slug($words),
            TypeLien::WIKIPEDIA
        );
    }

    /**
     * Élément pour œuvre musicale
     */
    public function musical(): static
    {
        return $this->state(fn(): array => [
            'nom' => $this->faker->randomElement([
                'Symphonie No. 9',
                'Clair de Lune',
                'La Marseillaise',
                'Ave Maria',
                'Boléro',
            ]),
            'complement' => $this->faker->year(),
        ]);
    }

    /**
     * Élément pour film/théâtre
     */
    public function theatrical(): static
    {
        return $this->state(fn(): array => [
            'nom' => $this->faker->name(),
            'complement' => $this->faker->randomElement([
                'Réalisateur',
                'Acteur principal',
                'Scénariste',
                'Producteur',
            ]),
        ]);
    }
}
