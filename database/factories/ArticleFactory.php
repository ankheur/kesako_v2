<?php

namespace Database\Factories;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'categorie_id' => $this->faker->randomDigitNotNull(),
            'titre' => $this->faker->sentence(),
            'soustitre' => $this->faker->sentence(),
            'illustration' => 'article-illustrations/illustration.jpg',
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'contenu' => $this->faker->paragraph(),
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
