<?php

use App\Models\Article;
use App\Models\Auteur;
use App\Models\Categorie;

test('Ne retourne que les categories publiées en utilisant le scope published', function () {
    Categorie::factory()->published()->create();
    Categorie::factory()->create();

    expect(Categorie::published()->get())
        ->toHaveCount(1)
        ->first()->id->toEqual(1);
});

test('La catégorie a des articles', function () {
    $categorie = Categorie::factory()
        ->has(Article::factory()->count(3))
        ->create();

    expect($categorie->articles)
        ->toHaveCount(3)
        ->each()->toBeInstanceOf(Article::class);
});
