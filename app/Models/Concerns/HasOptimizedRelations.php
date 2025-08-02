<?php

declare(strict_types=1);

namespace App\Models\Concerns;

/**
 * Trait pour optimiser le chargement des relations
 */
trait HasOptimizedRelations
{
    /**
     * Charge les relations optimisées pour l'affichage
     */
    public function loadOptimizedRelations(): static
    {
        $relations = $this->getOptimizedRelations();

        if (! empty($relations)) {
            return $this->load($relations);
        }

        return $this;
    }

    /**
     * Retourne les relations à charger de manière optimisée
     * Doit être surchargée dans chaque model
     *
     * @return array<int, string>
     */
    protected function getOptimizedRelations(): array
    {
        return [];
    }
}
