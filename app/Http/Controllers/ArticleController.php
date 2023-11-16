<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        if(!$article->published_at) {
            abort(404);
        }

        return view('article', [
            'article' => $article
        ]);
    }
}
