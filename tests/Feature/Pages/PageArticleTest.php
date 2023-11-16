<?php


use App\Models\Article;
use App\Models\Auteur;
use App\Models\Categorie;
use function Pest\Laravel\{get};

test('Affiche une erreur si l’article n’est pas publié', function () {
    $article = Article::factory()
        ->create();

    get(route('article.show', $article))
        ->assertNotFound();
});

test('Affiche le contenu de l’article', function () {
    $article = Article::factory()
        ->for(Categorie::factory())
        ->published()
        ->create();

    get(route('article.show', $article))
        ->assertOk()
        ->assertViewIs('article')
        ->assertViewHas('article')
        ->assertSeeText([
            $article->titre,
            $article->soustitre,
            $article->description,
            $article->contenu
        ])
        ->assertSee($article->illustration);
});

test('Affiche les informations de la catégorie', function() {
    $article = Article::factory()
        ->for(Categorie::factory())
        ->published()
        ->create();

    get(route('article.show', $article))
        ->assertOk()
        ->assertSeeText($article->categorie->titre);
});

