<?php

declare(strict_types=1);

use App\Filament\Resources\CategorieResource;
use App\Models\Categorie;

use function Pest\Laravel\{get};

test('Page Index categories fonctionnelle', function () {
    get(CategorieResource::getUrl('index'))->assertOk();
});

test('Page Create categorie fonctionnelle', function () {
    get(CategorieResource::getUrl('create'))->assertOk();
});

test('Page Edit categorie fonctionnelle', function () {
    get(CategorieResource::getUrl('edit', [
        'record' => Categorie::factory()->create(),
    ]))->assertOk();
});

/*test('Page View categorie fonctionnelle', function () {
    get(CategorieResource::getUrl('view', [
        'record' => Categorie::factory()->create(),
    ]))->assertOk();
});*/
