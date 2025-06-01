<?php

declare(strict_types=1);

use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\Database\Eloquent\Relations\MorphMany;

test('to array', function () {
    $fiche = Fiche::factory()->create()->fresh();

    expect(array_keys($fiche->toArray()))->toEqual([
        'id',
        'titre',
        'soustitre',
        'illustration',
        'slug',
        'description',
        'contenu',
        'published_at',
        'deleted_at',
        'created_at',
        'updated_at',
        'type',
        'statut',
        'alt_illustration',
        'portfolio',
    ]);
});

test('Ne retourne que les fiches publiés en utilisant le scope published', function () {
    Fiche::factory()->published()->create();
    Fiche::factory()->create();

    expect(Fiche::published()->get())
        ->toHaveCount(1)
        ->first()->id->toEqual(1);
});

/*test('La fiche a un domaine', function () {
    $fiche = Fiche::factory()
        ->for(Domaine::factory())
        ->create();

    expect($fiche->domaine)
        ->toBeInstanceOf(Domaine::class);
});*/

/*test('L’fiche compte les visites', function () {
    $fiche = Fiche::factory()
        ->for(Domaine::factory())
        ->create();

    expect($fiche->visits())
        ->toBeInstanceOf(MorphMany::class);
});*/
