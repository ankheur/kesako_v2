<?php

declare(strict_types=1);

use App\Filament\Resources\DomaineResource\Pages\CreateDomaine;
use App\Filament\Resources\DomaineResource\Pages\EditDomaine;
use App\Models\Domaine;
use Illuminate\Http\UploadedFile;

use function Pest\Livewire\livewire;

test('Un domaine peut être créée', function () {
    $newData = Domaine::factory()->make();
    $file = UploadedFile::fake()->image('test.png');

    livewire(CreateDomaine::class)
        ->fillForm([
            'titre' => $newData->titre,
            'slug' => $newData->slug,
            'icone' => $file,
            'description' => $newData->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Domaine::class, [
        'titre' => $newData->titre,
        'slug' => $newData->slug,
        'icone' => 'domaine-icones/test.png',
        'description' => $newData->description,
    ]);
});

test('La validation des champs fonctionne', function () {
    $domaine = Domaine::factory()->create();
    $newData = Domaine::factory()->make();

    // A la création de l'fiche
    livewire(CreateDomaine::class)
        ->fillForm([
            'titre' => null,
            'slug' => null,
            'icone' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'titre' => 'required',
            'slug' => 'required',
            'icone' => 'required',
        ]);

    // A l'édition de l'fiche
    livewire(EditDomaine::class, ['record' => $domaine->getRouteKey()])
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
