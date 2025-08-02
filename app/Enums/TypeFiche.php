<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeFiche: string
{
    case BIOGRAPHIE = 'biographie';
    case OEUVRE = 'oeuvre';
    case THEME = 'theme';
    case EVENEMENT = 'evenement';
    case CONCEPT = 'concept';
    case CHRONOLOGIE = 'chronologie';

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
            self::BIOGRAPHIE => 'Biographie',
            self::OEUVRE => 'Œuvre',
            self::THEME => 'Thème',
            self::EVENEMENT => 'Événement',
            self::CONCEPT => 'Concept',
            self::CHRONOLOGIE => 'Chronologie',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::BIOGRAPHIE => 'Fiche dédiée à une personnalité',
            self::OEUVRE => 'Fiche dédiée à une création artistique, littéraire ou musicale',
            self::THEME => 'Fiche dédiée à un mouvement, courant ou sujet d\'étude',
            self::EVENEMENT => 'Fiche dédiée à un événement historique',
            self::CONCEPT => 'Fiche dédiée à une notion, idée ou principe',
            self::CHRONOLOGIE => 'Fiche présentant une succession chronologique',
        };
    }

    public function couleur(): string
    {
        return match ($this) {
            self::BIOGRAPHIE => '#3B82F6',    // Bleu
            self::OEUVRE => '#8B5CF6',        // Violet
            self::THEME => '#10B981',         // Vert
            self::EVENEMENT => '#EF4444',     // Rouge
            self::CONCEPT => '#F59E0B',       // Orange
            self::CHRONOLOGIE => '#6B7280',   // Gris
        };
    }

    public function icone(): string
    {
        return match ($this) {
            self::BIOGRAPHIE => 'user',
            self::OEUVRE => 'book',
            self::THEME => 'tag',
            self::EVENEMENT => 'calendar',
            self::CONCEPT => 'lightbulb',
            self::CHRONOLOGIE => 'clock',
        };
    }

    /**
     * Règles de validation pour ce type de fiche
     *
     * @return array<string, mixed>
     */
    public function getValidationRules(): array
    {
        return match ($this) {
            self::BIOGRAPHIE => [
                'categories_min' => 1,
                'categories_max' => 8,
                'suggested_types' => ['PROFESSION', 'NATIONALITE', 'EPOQUE'],
                'description' => 'Une biographie doit avoir au minimum une profession',
            ],
            self::OEUVRE => [
                'categories_min' => 2,
                'categories_max' => 10,
                'suggested_types' => ['GENRE', 'EPOQUE', 'MOUVEMENT'],
                'description' => 'Une œuvre doit avoir au minimum un genre et une époque',
            ],
            self::EVENEMENT => [
                'categories_min' => 1,
                'categories_max' => 8,
                'suggested_types' => ['EPOQUE', 'LIEU'],
                'description' => 'Un événement doit avoir au minimum une époque',
            ],
            self::THEME => [
                'categories_min' => 1,
                'categories_max' => 6,
                'suggested_types' => ['MOUVEMENT', 'EPOQUE'],
                'description' => 'Un thème doit être contextualisé temporellement',
            ],
            self::CONCEPT => [
                'categories_min' => 1,
                'categories_max' => 5,
                'suggested_types' => ['MOUVEMENT', 'PROFESSION'],
                'description' => 'Un concept doit être rattaché à un domaine de connaissance',
            ],
            self::CHRONOLOGIE => [
                'categories_min' => 1,
                'categories_max' => 4,
                'suggested_types' => ['EPOQUE', 'LIEU'],
                'description' => 'Une chronologie doit être contextualisée',
            ],
        };
    }
}
