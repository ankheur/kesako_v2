<?php

declare(strict_types=1);

use App\Filament\Resources\AuteurResource\Pages\ListAuteurs;
use App\Models\Auteur;

use function Pest\Livewire\livewire;

test('La table liste les auteurs', function () {
    $auteurs = Auteur::factory()->count(10)->create();

    livewire(ListAuteurs::class)
        ->assertCanSeeTableRecords($auteurs);
});

test('La table affiche le nom et prénom de l’auteur dans une colonne', function () {
    $auteur = Auteur::factory()->create();

    livewire(ListAuteurs::class)
        ->assertTableColumnFormattedStateSet('nom', $auteur->nom.' '.$auteur->prenom, record: $auteur)
        ->assertTableColumnFormattedStateNotSet('nom', $auteur->nom, record: $auteur);
});
