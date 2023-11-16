<?php

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use function Pest\Laravel\{get};

test('Page Index articles fonctionnelle', function () {
    get(ArticleResource::getUrl('index'))->assertOk();
});

test('Page Create article fonctionnelle', function () {
    get(ArticleResource::getUrl('create'))->assertOk();
});

test('Page Edit article fonctionnelle', function () {
    get(ArticleResource::getUrl('edit', [
        'record' => Article::factory()->create(),
    ]))->assertOk();
});

/*test('Page View article fonctionnelle', function () {
    get(ArticleResource::getUrl('view', [
        'record' => Article::factory()->create(),
    ]))->assertOk();
});*/
