<?php

declare(strict_types=1);

use App\Filament\Resources\FicheResource\Pages\CreateFiche;
use App\Filament\Resources\FicheResource\Pages\EditFiche;
use App\Models\Fiche;
use Illuminate\Http\UploadedFile;

use function Pest\Livewire\livewire;

test('Un fiche peut être créé', function () {
    $newData = Fiche::factory()->make();
    $illustration = UploadedFile::fake()->image('illustration.png');

    $fiche_array = [
        'titre' => $newData->titre,
        'soustitre' => $newData->soustitre,
        'slug' => $newData->slug,
        /* 'illustration' => $illustration, */
        'type' => $newData->type,
        'description' => $newData->description,
        'contenu' => $newData->contenu,
        'statut' => $newData->statut,
    ];

    livewire(CreateFiche::class)
        ->fillForm($fiche_array)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Fiche::class, $fiche_array);
});

test('La validation des champs fonctionne', function () {
    $fiche = Fiche::factory()->create();
    $newData = Fiche::factory()->make();

    $fiche_array = [
        'titre' => null,
        'slug' => null,
        'description' => null,
        'contenu' => null,
        'type' => null,
        'statut' => null,
    ];

    // A la création de l'fiche
    livewire(CreateFiche::class)
        ->fillForm($fiche_array)
        ->call('create')
        ->assertHasFormErrors([
            'titre' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'contenu' => 'required',
            'type' => 'required',
            'statut' => 'required',
        ]);

    // A l'édition de l'fiche
    livewire(EditFiche::class, ['record' => $fiche->getRouteKey()])
        ->fillForm($fiche_array)
        ->call('save')
        ->assertHasFormErrors([
            'titre' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'contenu' => 'required',
            'type' => 'required',
            'statut' => 'required',
        ]);
});
