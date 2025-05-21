<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $this->actingAs($user, 'filament');

        $this->initializeFilamentPanel();
    }

    protected function initializeFilamentPanel(): void
    {
        if (Filament::getCurrentPanel() === null) {
            /** @var Panel $defaultPanel */
            $defaultPanel = Filament::getPanel('admin'); // Replace 'admin' if needed
            Filament::setCurrentPanel($defaultPanel);
        }
    }
}
