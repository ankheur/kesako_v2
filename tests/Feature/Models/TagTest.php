<?php

declare(strict_types=1);

use App\Models\Tag;

test('to array', function () {
    $tag = Tag::factory()->create()->fresh();

    expect(array_keys($tag->toArray()))->toEqual([
        'id',
        'denomination',
        'slug',
        'published_at',
        'created_at',
        'updated_at',
        'description',
    ]);
});
