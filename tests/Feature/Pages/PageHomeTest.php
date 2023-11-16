<?php

use App\Models\Article;
use App\Models\Auteur;
use App\Models\Categorie;

use function Pest\Laravel\{get};

test('Affichage de la page d’accueil avec articles et catégories principales', function () {
    $categorie = Categorie::factory()->published()->create();
    $article = Article::factory()
        ->for($categorie)
        ->published()
        ->create();

    get(route('pages.home'))
        ->assertOk()
        /*->assertViewIs('home')*/
        ->assertSeeText($categorie->titre)
        ->assertSeeText($article->titre);
});

test('Affichage seulement des catégories de niveau 1 publiées', function () {
    $categorieOne = Categorie::factory()->published()->create();
    $categorieTwo = Categorie::factory()->create();

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText($categorieOne->titre)
        ->assertDontSeeText($categorieTwo->titre);
});

test('Affichage des derniers articles publiés par ordre chronologique', function () {
    $articleOne = Article::factory()
        ->for(Categorie::factory())
        ->published(\Carbon\Carbon::yesterday())
        ->create();
    $articleTwo = Article::factory()
        ->for(Categorie::factory())
        ->published()
        ->create();
    $articleThree = Article::factory()->create();

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeTextInOrder([$articleTwo->titre, $articleOne->titre])
        ->assertDontSeeText($articleThree->titre);
});
