<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeCategorie: string
{
    case PROFESSION = 'profession';
    case EPOQUE = 'epoque';
    case NATIONALITE = 'nationalite';
    case MOUVEMENT = 'mouvement';
    case GENRE = 'genre';
    case LIEU = 'lieu';

    /**
     * Retourne tous les types sous forme de tableau pour les selects
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }

    public function label(): string
    {
        return match ($this) {
            self::PROFESSION => 'Profession',
            self::EPOQUE => 'Époque',
            self::NATIONALITE => 'Nationalité',
            self::MOUVEMENT => 'Mouvement',
            self::GENRE => 'Genre',
            self::LIEU => 'Lieu',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PROFESSION => 'Métier, fonction ou statut social',
            self::EPOQUE => 'Période historique, siècle ou ère',
            self::NATIONALITE => 'Origine géographique, peuple ou nation',
            self::MOUVEMENT => 'Courant artistique, philosophique ou politique',
            self::GENRE => 'Genre artistique, littéraire ou musical',
            self::LIEU => 'Lieu géographique, ville, région ou pays',
        };
    }

    public function couleur(): string
    {
        return match ($this) {
            self::PROFESSION => '#3B82F6',    // Bleu
            self::EPOQUE => '#10B981',        // Vert
            self::NATIONALITE => '#EF4444',   // Rouge
            self::MOUVEMENT => '#8B5CF6',     // Violet
            self::GENRE => '#F59E0B',         // Orange
            self::LIEU => '#6B7280',          // Gris
        };
    }

    public function icone(): string
    {
        return match ($this) {
            self::PROFESSION => 'briefcase',
            self::EPOQUE => 'calendar',
            self::NATIONALITE => 'flag',
            self::MOUVEMENT => 'trending-up',
            self::GENRE => 'tag',
            self::LIEU => 'map-pin',
        };
    }

    /**
     * Exemples de catégories pour ce type
     *
     * @return array<int, string>
     */
    public function getExemples(): array
    {
        return match ($this) {
            self::PROFESSION => ['Écrivain', 'Empereur', 'Peintre', 'Compositeur', 'Philosophe'],
            self::EPOQUE => ['XIXe siècle', 'Antiquité', 'Renaissance', 'Moyen Âge', 'XXe siècle'],
            self::NATIONALITE => ['Français', 'Romain', 'Italien', 'Allemand', 'Grec'],
            self::MOUVEMENT => ['Romantisme', 'Impressionnisme', 'Baroque', 'Classicisme', 'Réalisme'],
            self::GENRE => ['Roman', 'Poésie', 'Symphonie', 'Peinture à l\'huile', 'Tragédie'],
            self::LIEU => ['France', 'Paris', 'Rome', 'Europe', 'Méditerranée'],
        };
    }
}
