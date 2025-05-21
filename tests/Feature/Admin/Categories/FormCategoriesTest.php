<?php

declare(strict_types=1);

use App\Filament\Resources\CategorieResource\Pages\CreateCategorie;
use App\Filament\Resources\CategorieResource\Pages\EditCategorie;
use App\Models\Categorie;

use function Pest\Livewire\livewire;

test('Une Catégorie peut être créée', function () {
    $newData = Categorie::factory()->make();

    $categorie_array = [
        'denomination' => $newData->denomination,
        'description' => $newData->description,
        'type' => $newData->type,
        'slug' => $newData->slug,
        'published_at' => $newData->published_at,
    ];

    livewire(CreateCategorie::class)
        ->fillForm($categorie_array)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Categorie::class, $categorie_array);
});

test('La validation des champs fonctionne', function () {
    $fiche = Categorie::factory()->create();
    $newData = Categorie::factory()->make();

    // A la création de l'fiche
    livewire(CreateCategorie::class)
        ->fillForm([
            'denomination' => null,
            'slug' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'denomination' => 'required',
            'slug' => 'required',
        ]);

    // A l'édition de l'fiche
    livewire(EditCategorie::class, ['record' => $fiche->getRouteKey()])
        ->fillForm([
            'denomination' => null,
            'slug' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'denomination' => 'required',
            'slug' => 'required',
        ]);
});
