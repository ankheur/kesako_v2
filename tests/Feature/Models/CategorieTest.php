<?php

declare(strict_types=1);

use App\Models\Categorie;

test('to array', function () {
    $categorie = Categorie::factory()->create()->fresh();

    expect(array_keys($categorie->toArray()))->toEqual([
        'id',
        'denomination',
        'slug',
        'type',
        'published_at',
        'created_at',
        'updated_at',
        'description',
    ]);
});
