<?php

use App\Models\Article;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Relations\MorphMany;

test('Ne retourne que les articles publiés en utilisant le scope published', function () {
    Article::factory()->published()->create();
    Article::factory()->create();

    expect(Article::published()->get())
        ->toHaveCount(1)
        ->first()->id->toEqual(1);
});

test('L’article a une catégorie', function () {
    $article = Article::factory()
        ->for(Categorie::factory())
        ->create();

    expect($article->categorie)
        ->toBeInstanceOf(Categorie::class);
});

test('L’article compte les visites', function () {
    $article = Article::factory()
        ->for(Categorie::factory())
        ->create();

    expect($article->visits())
        ->toBeInstanceOf(MorphMany::class);
});
