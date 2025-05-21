<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource\Pages\CreateTag;
use App\Filament\Resources\TagResource\Pages\EditTag;
use App\Models\Tag;

use function Pest\Livewire\livewire;

test('Un Tag peut être créé', function () {
    $newData = Tag::factory()->make();

    livewire(CreateTag::class)
        ->fillForm([
            'denomination' => $newData->denomination,
            'slug' => $newData->slug,
            'published_at' => $newData->published_at,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Tag::class, [
        'denomination' => $newData->denomination,
        'slug' => $newData->slug,
        'published_at' => $newData->published_at,
    ]);
});

test('La validation des champs fonctionne', function () {
    $fiche = Tag::factory()->create();
    $newData = Tag::factory()->make();

    // A la création de l'fiche
    livewire(CreateTag::class)
        ->fillForm([
            'denomination' => null,
            'slug' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'denomination' => 'required',
            'slug' => 'required',
        ]);

    // A l'édition de l'fiche
    livewire(EditTag::class, ['record' => $fiche->getRouteKey()])
        ->fillForm([
            'denomination' => null,
            'slug' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'denomination' => 'required',
            'slug' => 'required',
        ]);
});
