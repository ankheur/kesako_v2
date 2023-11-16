<?php

use App\Filament\Resources\AuteurResource;
use App\Models\Auteur;
use function Pest\Laravel\{get};

test('Page Index auteurs fonctionnelle', function () {
    get(AuteurResource::getUrl('index'))->assertOk();
});

test('Page Create auteur fonctionnelle', function () {
    get(AuteurResource::getUrl('create'))->assertOk();
});

test('Page Edit auteur fonctionnelle', function () {
    get(AuteurResource::getUrl('edit', [
        'record' => Auteur::factory()->create(),
    ]))->assertOk();
});
