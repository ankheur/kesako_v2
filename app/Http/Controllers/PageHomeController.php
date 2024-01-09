<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;

class PageHomeController extends Controller
{
    public function __invoke()
    {
        $categories = Categorie::published()->get();
        $articles = Article::published()->orderByDesc('published_at')->with(['categorie:id,slug,titre'])->get();

        return view('home', [
            'categories' => $categories,
            'popularPosts' => Article::popularToday()->limit(3)->get(),
            'articles' => $articles,
        ]);
    }
}
