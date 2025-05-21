<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource;
use App\Models\Tag;

use function Pest\Laravel\{get};

test('Page Index tags fonctionnelle', function () {
    get(TagResource::getUrl('index'))->assertOk();
});

test('Page Create tag fonctionnelle', function () {
    get(TagResource::getUrl('create'))->assertOk();
});

test('Page Edit tag fonctionnelle', function () {
    get(TagResource::getUrl('edit', [
        'record' => Tag::factory()->create(),
    ]))->assertOk();
});

/*test('Page View tag fonctionnelle', function () {
    get(TagResource::getUrl('view', [
        'record' => Tag::factory()->create(),
    ]))->assertOk();
});*/
