<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Fiche;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class FicheBreadcrumb extends Component
{
    public function __construct(
        public Fiche $fiche,
    ) {}

    public function render(): View
    {
        return view('components.fiche-breadcrumb');
    }
}
