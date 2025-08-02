<?php

declare(strict_types=1);

use App\Filament\Resources\FicheResource;
use App\Models\Fiche;

use function Pest\Laravel\get;

test('Page Index fiches fonctionnelle', function () {
    get(FicheResource::getUrl('index'))->assertOk();
});

test('Page Create fiche fonctionnelle', function () {
    get(FicheResource::getUrl('create'))->assertOk();
});

test('Page Edit fiche fonctionnelle', function () {
    get(FicheResource::getUrl('edit', [
        'record' => Fiche::factory()->create(),
    ]))->assertOk();
});

/*test('Page View fiche fonctionnelle', function () {
    get(FicheResource::getUrl('view', [
        'record' => Fiche::factory()->create(),
    ]))->assertOk();
});*/
