<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusFiche: string
{
    case BROUILLON = 'brouillon';
    case EN_REVIEW = 'en_review';
    case PUBLIE = 'publie';

    /**
     * Statuts visibles publiquement
     *
     * @return array<int, StatusFiche>
     */
    public static function public(): array
    {
        return [self::PUBLIE];
    }

    /**
     * Statuts modifiables par les rédacteurs
     *
     * @return array<int, StatusFiche>
     */
    public static function editable(): array
    {
        return [self::BROUILLON, self::EN_REVIEW];
    }

    /**
     * Retourne tous les statuts sous forme de tableau pour les selects
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }

    public function label(): string
    {
        return match ($this) {
            self::BROUILLON => 'Brouillon',
            self::EN_REVIEW => 'En relecture',
            self::PUBLIE => 'Publié',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::BROUILLON => 'Fiche en cours de rédaction',
            self::EN_REVIEW => 'Fiche prête, en attente de validation',
            self::PUBLIE => 'Fiche validée et visible publiquement',
        };
    }

    public function couleur(): string
    {
        return match ($this) {
            self::BROUILLON => '#6B7280',     // Gris
            self::EN_REVIEW => '#F59E0B',     // Orange
            self::PUBLIE => '#10B981',        // Vert
        };
    }

    public function icone(): string
    {
        return match ($this) {
            self::BROUILLON => 'edit',
            self::EN_REVIEW => 'clock',
            self::PUBLIE => 'check-circle',
        };
    }

    /**
     * Transitions autorisées depuis ce statut
     *
     * @return array<int, StatusFiche>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::BROUILLON => [self::EN_REVIEW, self::PUBLIE],
            self::EN_REVIEW => [self::BROUILLON, self::PUBLIE],
            self::PUBLIE => [self::BROUILLON, self::EN_REVIEW],
        };
    }

    /**
     * Vérifie si la transition vers un statut est autorisée
     */
    public function canTransitionTo(self $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions(), true);
    }

    /**
     * Retourne les transitions possibles sous forme de tableau
     *
     * @return array<string, string>
     */
    public function getTransitionOptions(): array
    {
        $options = [];

        foreach ($this->allowedTransitions() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }
}
