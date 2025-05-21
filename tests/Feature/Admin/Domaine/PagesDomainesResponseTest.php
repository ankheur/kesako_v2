<?php

declare(strict_types=1);

use App\Filament\Resources\DomaineResource;
use App\Models\Domaine;

use function Pest\Laravel\get;

test('Page Index domaines fonctionnelle', function () {
    get(DomaineResource::getUrl('index'))->assertSuccessful();
});

test('Page Create domaine fonctionnelle', function () {
    get(DomaineResource::getUrl('create'))->assertSuccessful();
});

test('Page Edit domaine fonctionnelle', function () {
    get(DomaineResource::getUrl('edit', [
        'record' => Domaine::factory()->create(),
    ]))->assertSuccessful();
});
