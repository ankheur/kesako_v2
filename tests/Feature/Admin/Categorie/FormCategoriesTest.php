<?php

use App\Filament\Resources\CategorieResource\Pages\CreateCategorie;
use App\Filament\Resources\CategorieResource\Pages\EditCategorie;
use App\Models\Categorie;
use function Pest\Livewire\livewire;
use Illuminate\Http\UploadedFile;

test('Une catégorie peut être créée', function () {
    $newData = Categorie::factory()->make();
    $file = UploadedFile::fake()->image('test.png');

    livewire(CreateCategorie::class)
        ->fillForm([
            'titre' => $newData->titre,
            'slug' => $newData->slug,
            'icone' => $file,
            'description' => $newData->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Categorie::class, [
        'titre' => $newData->titre,
        'slug' => $newData->slug,
        'icone' => 'categorie-icones/test.png',
        'description' => $newData->description,
    ]);
});

test('La validation des champs fonctionne', function () {
    $categorie = Categorie::factory()->create();
    $newData = Categorie::factory()->make();

    // A la création de l'article
    livewire(CreateCategorie::class)
        ->fillForm([
            'titre' => null,
            'slug' => null,
            'icone' => null
        ])
        ->call('create')
        ->assertHasFormErrors([
            'titre' => 'required',
            'slug' => 'required',
            'icone' => 'required'
        ]);

    // A l'édition de l'article
    livewire(EditCategorie::class, ['record' => $categorie->getRouteKey()])
        ->fillForm([
            'titre' => null,
            'slug' => null,
            'icone' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'titre' => 'required',
            'slug' => 'required',
            'icone' => 'required',
        ]);
});
