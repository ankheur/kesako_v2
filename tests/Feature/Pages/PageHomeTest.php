<?php

declare(strict_types=1);

use App\Models\Domaine;
use App\Models\Fiche;

use function Pest\Laravel\get;

test('Affichage de la page d’accueil avec fiches et catégories principales', function () {
    $domaine = Domaine::factory()->published()->create();
    $fiche = Fiche::factory()
        ->published()
        ->create();

    get(route('pages.home'))
        ->assertOk()
        /* ->assertViewIs('home') */
        ->assertSeeText($domaine->titre)
        ->assertSeeText($fiche->titre);
});

test('Affichage seulement des catégories de niveau 1 publiées', function () {
    $domaineOne = Domaine::factory()->published()->create();
    $domaineTwo = Domaine::factory()->create();

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText($domaineOne->titre)
        ->assertDontSeeText($domaineTwo->titre);
});

test('Affichage des derniers fiches publiés par ordre chronologique', function () {
    $ficheOne = Fiche::factory()
        ->published(Carbon\Carbon::yesterday())
        ->create();
    $ficheTwo = Fiche::factory()
        ->published()
        ->create();
    $ficheThree = Fiche::factory()->create();

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeTextInOrder([$ficheTwo->titre, $ficheOne->titre])
        ->assertDontSeeText($ficheThree->titre);
});
