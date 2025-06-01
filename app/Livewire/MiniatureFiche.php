<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Fiche;
use Livewire\Component;

final class MiniatureFiche extends Component
{
    public Fiche $fiche;

    public function render()
    {
        return view('livewire.miniature-fiche');
    }
}
