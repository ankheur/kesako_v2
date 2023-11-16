<?php

use App\Filament\Resources\CategorieResource\Pages\ListCategories;
use App\Models\Categorie;
use function Pest\Livewire\livewire;

test('La table liste les catégories', function () {
    $articles = Categorie::factory()->count(10)->create();

    livewire(ListCategories::class)
        ->assertCanSeeTableRecords($articles);
});
