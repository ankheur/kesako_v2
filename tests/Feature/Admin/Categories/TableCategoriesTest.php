<?php

declare(strict_types=1);

use App\Filament\Resources\CategorieResource\Pages\ListCategories;
use App\Models\Categorie;

use function Pest\Livewire\livewire;

test('La table liste les categories', function () {
    $fiches = Categorie::factory()->count(10)->create();

    livewire(ListCategories::class)
        ->assertCanSeeTableRecords($fiches);
});
