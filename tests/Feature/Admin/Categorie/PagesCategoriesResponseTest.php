<?php

use App\Filament\Resources\CategorieResource;
use App\Models\Categorie;
use function Pest\Laravel\{get};

test('Page Index catégories fonctionnelle', function () {
    get(CategorieResource::getUrl('index'))->assertOk();
});

test('Page Create catégorie fonctionnelle', function () {
    get(CategorieResource::getUrl('create'))->assertOk();
});

test('Page Edit catégorie fonctionnelle', function () {
    get(CategorieResource::getUrl('edit', [
        'record' => Categorie::factory()->create(),
    ]))->assertOk();
});
