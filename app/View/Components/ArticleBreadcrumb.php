<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use phpDocumentor\Reflection\Types\Integer;

class ArticleBreadcrumb extends Component
{
    public function __construct(
        public $article,
    ) {}

    public function render(): View
    {
        return view('components.article-breadcrumb');
    }
}
