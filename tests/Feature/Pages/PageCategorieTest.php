<?php


use App\Models\Article;
use App\Models\Categorie;
use function Pest\Laravel\{get};

test('Affiche une erreur si la catégorie n’est pas publiée', function () {
    $categorie = Categorie::factory()
        ->create();

    get(route('categorie.show', $categorie))
        ->assertNotFound();
});

test('Affiche le contenu de l’article', function () {
    $categorie = Categorie::factory()
        ->published()
        ->create();

    get(route('categorie.show', $categorie))
        ->assertOk()
        ->assertViewIs('categorie')
        ->assertViewHas('categorie')
        ->assertSeeText([
            $categorie->titre,
            $categorie->description
        ])
        ->assertSee($categorie->icone);
});

test('Affiche les informations des articles lié', function() {
    $categorie = Categorie::factory()->published()->create();
    $article = Article::factory()
        ->for($categorie)
        ->published()
        ->create();

    get(route('categorie.show', $categorie))
        ->assertOk()
        ->assertSeeText($article->titre);
});
