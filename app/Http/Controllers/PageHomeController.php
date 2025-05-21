<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\View\View;

final class PageHomeController
{
    public function __invoke(): View
    {
        $domaines = Domaine::published()->get();
        $fiches = Fiche::published()->orderByDesc('published_at')->limit(12)->get();

        return view('home', [
            'domaines' => $domaines,
            /* 'popularPosts' => Fiche::popularToday()->limit(3)->get(), */
            'fiches' => $fiches,
        ]);
    }
}
