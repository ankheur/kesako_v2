<?php

declare(strict_types=1);

use App\Enums\TypeFiche;

/**
 * Test 1 : Vérifie que chaque case de l'enum a la bonne valeur string
 */
test('TypeFiche has correct values', function (): void {
    expect(TypeFiche::BIOGRAPHIE->value)->toBe('biographie');
    expect(TypeFiche::OEUVRE->value)->toBe('oeuvre');
    expect(TypeFiche::THEME->value)->toBe('theme');
    expect(TypeFiche::EVENEMENT->value)->toBe('evenement');
    expect(TypeFiche::CONCEPT->value)->toBe('concept');
    expect(TypeFiche::CHRONOLOGIE->value)->toBe('chronologie');
});

/**
 * Test 2 : Vérifie que les labels d'affichage sont corrects
 */
test('TypeFiche has correct labels', function (): void {
    expect(TypeFiche::BIOGRAPHIE->label())->toBe('Biographie');
    expect(TypeFiche::OEUVRE->label())->toBe('Œuvre');
    expect(TypeFiche::THEME->label())->toBe('Thème');
    expect(TypeFiche::EVENEMENT->label())->toBe('Événement');
    expect(TypeFiche::CONCEPT->label())->toBe('Concept');
    expect(TypeFiche::CHRONOLOGIE->label())->toBe('Chronologie');
});

/**
 * Test 3 : Vérifie que les descriptions explicatives sont présentes
 */
test('TypeFiche has correct descriptions', function (): void {
    expect(TypeFiche::BIOGRAPHIE->description())->toBe('Fiche dédiée à une personnalité')
        ->and(TypeFiche::OEUVRE->description())->toBe('Fiche dédiée à une création artistique, littéraire ou musicale')
        ->and(TypeFiche::THEME->description())->toBe('Fiche dédiée à un mouvement, courant ou sujet d\'étude')
        ->and(TypeFiche::EVENEMENT->description())->toBe('Fiche dédiée à un événement historique')
        ->and(TypeFiche::CONCEPT->description())->toBe('Fiche dédiée à une notion, idée ou principe')
        ->and(TypeFiche::CHRONOLOGIE->description())->toBe('Fiche présentant une succession chronologique');
});

/**
 * Test 4 : Vérifie que les couleurs sont des codes hex valides
 */
test('TypeFiche has correct colors', function (): void {
    expect(TypeFiche::BIOGRAPHIE->couleur())->toBe('#3B82F6')
        ->and(TypeFiche::OEUVRE->couleur())->toBe('#8B5CF6')
        ->and(TypeFiche::THEME->couleur())->toBe('#10B981')
        ->and(TypeFiche::EVENEMENT->couleur())->toBe('#EF4444')
        ->and(TypeFiche::CONCEPT->couleur())->toBe('#F59E0B')
        ->and(TypeFiche::CHRONOLOGIE->couleur())->toBe('#6B7280');
});

/**
 * Test 5 : Vérifie que les icônes sont définies
 */
/*test('TypeFiche has correct icons', function (): void {
    expect(TypeFiche::BIOGRAPHIE->icone())->toBe('user')
        ->and(TypeFiche::OEUVRE->icone())->toBe('book')
        ->and(TypeFiche::THEME->icone())->toBe('tag')
        ->and(TypeFiche::EVENEMENT->icone())->toBe('calendar')
        ->and(TypeFiche::CONCEPT->icone())->toBe('lightbulb')
        ->and(TypeFiche::CHRONOLOGIE->icone())->toBe('clock');
});*/

/**
 * Test 6 : Vérifie que la méthode options() retourne le bon format
 * Crucial pour les selects Filament (value => label)
 */
test('TypeFiche options returns correct array structure', function (): void {
    $options = TypeFiche::options();

    expect($options)->toBeArray()
        ->and($options)->toHaveCount(6)
        ->and(array_keys($options))->toEqual([
            'biographie', 'oeuvre', 'theme', 'evenement', 'concept', 'chronologie',
        ])
        ->and($options['biographie'])->toBe('Biographie')
        ->and($options['oeuvre'])->toBe('Œuvre')
        ->and($options['theme'])->toBe('Thème')
        ->and($options['evenement'])->toBe('Événement')
        ->and($options['concept'])->toBe('Concept')
        ->and($options['chronologie'])->toBe('Chronologie');
});

/**
 * Test 7 : Vérifie que toutes les règles de validation sont cohérentes
 * Important pour la validation future des fiches selon leur type
 */
test('TypeFiche validation rules have required structure', function (): void {
    foreach (TypeFiche::cases() as $type) {
        $rules = $type->getValidationRules();

        expect($rules)->toBeArray()
            ->and($rules)->toHaveKeys(['categories_min', 'categories_max', 'suggested_types', 'description'])
            ->and($rules['categories_min'])->toBeInt()->toBeGreaterThan(0)
            ->and($rules['categories_max'])->toBeInt()->toBeGreaterThan($rules['categories_min'])
            ->and($rules['suggested_types'])->toBeArray()->not->toBeEmpty()
            ->and($rules['description'])->toBeString()->not->toBeEmpty();
    }
});

/**
 * Test 8 : Vérifie la cohérence des règles de validation spécifiques
 * Test que les contraintes métier sont logiques
 */
test('TypeFiche validation rules are coherent', function (): void {
    // Biographie doit avoir moins de catégories qu'une œuvre (plus spécialisée) et doit suggérer PROFESSION
    $biographieRules = TypeFiche::BIOGRAPHIE->getValidationRules();
    $oeuvreRules = TypeFiche::OEUVRE->getValidationRules();

    expect($biographieRules['categories_max'])->toBeLessThanOrEqual($oeuvreRules['categories_max'])
        ->and($biographieRules['suggested_types'])->toContain('PROFESSION');

    // Chronologie doit avoir le minimum de catégories (très spécialisée)
    $chronologieRules = TypeFiche::CHRONOLOGIE->getValidationRules();
    expect($chronologieRules['categories_max'])->toBeLessThanOrEqual(4);

    // Événement doit suggérer EPOQUE
    $evenementRules = TypeFiche::EVENEMENT->getValidationRules();
    expect($evenementRules['suggested_types'])->toContain('EPOQUE');
});
