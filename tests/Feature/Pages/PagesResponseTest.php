<?php

use App\Models\Article;
use App\Models\Auteur;
use App\Models\Categorie;
use function Pest\Laravel\{get};

test('Homepage fonctionnelle', function () {
    get(route('pages.home'))
        ->assertOk();
});

test('Page article fonctionnelle', function () {
    $article = Article::factory()
        ->for(Categorie::factory())
        ->published()
        ->create();

    get(route('article.show', $article))
        ->assertOk();
});
