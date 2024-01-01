<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        if(!$article->published_at) {
            abort(404);
        }

        $article->visit();

        return view('article', [
            'article' => $article,
            'categories' => Categorie::published()->get(),
            'popularPosts' => Article::popularThisMonth()->limit(3)->get()
        ]);
    }
}
