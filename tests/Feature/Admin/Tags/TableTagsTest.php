<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource\Pages\ListTags;
use App\Models\Tag;

use function Pest\Livewire\livewire;

test('La table liste les tags', function () {
    $fiches = Tag::factory()->count(10)->create();

    livewire(ListTags::class)
        ->assertCanSeeTableRecords($fiches);
});
