<?php

declare(strict_types=1);

use App\Filament\Resources\AuteurResource\Pages\CreateAuteur;
use App\Filament\Resources\AuteurResource\Pages\EditAuteur;
use App\Models\Auteur;
use Illuminate\Http\UploadedFile;

use function Pest\Livewire\livewire;

test('Un auteur peut être créé', function () {
    $newData = Auteur::factory()->make();
    $file = UploadedFile::fake()->image('test.png');

    livewire(CreateAuteur::class)
        ->fillForm([
            'nom' => $newData->nom,
            'prenom' => $newData->prenom,
            'email' => $newData->email,
            'password' => $newData->password,
            'image_profil' => $file,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Auteur::class, [
        'nom' => $newData->nom,
        'prenom' => $newData->prenom,
        'email' => $newData->email,
        'password' => $newData->password,
        'image_profil' => 'auteur-profil/test.png',
    ]);
});

test('La validation des champs fonctionne', function () {
    $auteur = Auteur::factory()->create();
    $newData = Auteur::factory()->make();

    // A la création de l'auteur
    livewire(CreateAuteur::class)
        ->fillForm([
            'nom' => null,
            'prenom' => null,
            'email' => null,
            'password' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

    // A l'édition de l'auteur
    livewire(EditAuteur::class, ['record' => $auteur->getRouteKey()])
        ->fillForm([
            'nom' => null,
            'prenom' => null,
            'email' => null,
            'password' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
});
