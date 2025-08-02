<?php

declare(strict_types=1);

use App\Models\Fiche;

use function Pest\Laravel\get;

test('Homepage fonctionnelle', function () {
    get(route('pages.home'))
        ->assertOk();
});

test('Page show fiche fonctionnelle', function () {
    $fiche = Fiche::factory()
        ->published()
        ->create();

    get(route('fiche.show', $fiche))
        ->assertOk();
});
