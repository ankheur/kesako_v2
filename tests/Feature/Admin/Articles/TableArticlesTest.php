<?php

use App\Filament\Resources\ArticleResource\Pages\ListArticles;
use App\Models\Article;
use function Pest\Livewire\livewire;

test('La table liste les articles', function () {
    $articles = Article::factory()->count(10)->create();

    livewire(ListArticles::class)
        ->assertCanSeeTableRecords($articles);
});
