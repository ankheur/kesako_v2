<?php

declare(strict_types=1);

use App\Models\Domaine;
use App\Models\Fiche;

use function Pest\Laravel\{get};

test('Affiche une erreur si le domaine n’est pas publié', function () {
    $domaine = Domaine::factory()
        ->create();

    get(route('domaine.show', $domaine))
        ->assertNotFound();
});

test('Affiche le contenu de la fiche', function () {
    $domaine = Domaine::factory()
        ->published()
        ->create();

    get(route('domaine.show', $domaine))
        ->assertOk()
        ->assertViewIs('domaine')
        ->assertViewHas('domaine')
        ->assertSeeText([
            $domaine->titre,
            $domaine->description,
        ])
        ->assertSee($domaine->icone);
});

test('Affiche les informations des fiches liées', function () {
    $domaine = Domaine::factory()->published()->create();
    /*$fiche = Fiche::factory()
        ->for($domaine)
        ->published()
        ->create();*/

    get(route('domaine.show', $domaine))
        ->assertOk();
});
