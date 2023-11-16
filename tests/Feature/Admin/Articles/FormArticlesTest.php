<?php

use App\Filament\Resources\ArticleResource\Pages\CreateArticle;
use App\Filament\Resources\ArticleResource\Pages\EditArticle;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\UploadedFile;
use function Pest\Livewire\livewire;

test('Un article peut être créé', function () {
    $newData = Article::factory()->for(Categorie::factory())->make();
    $illustration = UploadedFile::fake()->image('illustration.png');

    livewire(CreateArticle::class)
        ->fillForm([
            'titre' => $newData->titre,
            'soustitre' => $newData->soustitre,
            'slug' => $newData->slug,
            /*'illustration' => $illustration,*/
            'description' => $newData->description,
            'categorie_id' => $newData->categorie->getKey(),
            'contenu' => $newData->contenu
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Article::class, [
        'titre' => $newData->titre,
        'soustitre' => $newData->soustitre,
        'slug' => $newData->slug,
        /*'illustration' => 'article-illustrations/illustration.png',*/
        'description' => $newData->description,
        'categorie_id' => $newData->categorie->getKey(),
        'contenu' => $newData->contenu,
    ]);
});

test('La validation des champs fonctionne', function () {
    $article = Article::factory()->create();
    $newData = Article::factory()->make();

    // A la création de l'article
    livewire(CreateArticle::class)
        ->fillForm([
            'categorie_id' => null,
            'titre' => null,
            'slug' => null,
            'description' => null,
            'contenu' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'categorie_id' => 'required',
            'titre' => 'required',
            'slug' => 'required',
            'description' =>'required',
            'contenu' => 'required',
        ]);

    // A l'édition de l'article
    livewire(EditArticle::class, ['record' => $article->getRouteKey()])
        ->fillForm([
            'categorie_id' => null,
            'titre' => null,
            'slug' => null,
            'description' => null,
            'contenu' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'categorie_id' => 'required',
            'titre' => 'required',
            'slug' => 'required',
            'description' =>'required',
            'contenu' => 'required',
        ]);
});
