<?php

declare(strict_types=1);

use App\Models\Domaine;
use App\Models\Fiche;

test('Ne retourne que les domaines publiés en utilisant le scope published', function () {
    Domaine::factory()->published()->create();
    Domaine::factory()->create();

    expect(Domaine::published()->get())
        ->toHaveCount(1)
        ->first()->id->toEqual(1);
});

/*test('Le domaine a des fiches', function () {
    $domaine = Domaine::factory()
        ->has(Fiche::factory()->count(3))
        ->create();

    expect($domaine->fiches)
        ->toHaveCount(3)
        ->each()->toBeInstanceOf(Fiche::class);
});*/
