<?php

declare(strict_types=1);

use App\Models\Domaine;
use App\Models\Fiche;

use function Pest\Laravel\get;

test('Affiche une erreur si la fiche n’est pas publié', function () {
    $fiche = Fiche::factory()
        ->create();

    get(route('fiche.show', $fiche))
        ->assertNotFound();
});

test('Affiche le contenu de la fiche', function () {
    $fiche = Fiche::factory()
        ->published()
        ->create();

    get(route('fiche.show', $fiche))
        ->assertOk()
        ->assertViewIs('fiche')
        ->assertViewHas('fiche')
        ->assertSeeText([
            $fiche->titre,
            $fiche->soustitre,
            $fiche->description,
            $fiche->contenu,
        ])
        ->assertSee($fiche->illustration);
});

test('Affiche les informations du domaine', function () {
    $fiche = Fiche::factory()
        ->published()
        ->create();

    get(route('fiche.show', $fiche))
        ->assertOk();
    /* ->assertSeeText($fiche->domaine->titre); */
});
