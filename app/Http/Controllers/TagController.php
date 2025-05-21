<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Tag;
use Illuminate\View\View;

final class TagController
{
    public function show(Tag $tag): View
    {
        if (! $tag->published_at) {
            abort(404);
        }

        /* $tag->visit()->hourlyInterval(); */

        return view('tag', [
            'tag' => $tag,
            'domaines' => Domaine::published()->get(),
        ]);
    }
}
