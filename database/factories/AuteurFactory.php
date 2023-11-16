<?php

namespace Database\Factories;

use App\Models\Auteur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AuteurFactory extends Factory
{
    protected $model = Auteur::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->name(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'azerty',
            'image_profil' => 'profil.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
