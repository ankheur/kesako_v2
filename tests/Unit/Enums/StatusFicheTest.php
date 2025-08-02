<?php

declare(strict_types=1);

use App\Enums\StatusFiche;

/**
 * Test 1 : Vérifie que chaque case de l'enum a la bonne valeur string
 */
test('StatusFiche has correct values', function (): void {
    expect(StatusFiche::BROUILLON->value)->toBe('brouillon')
        ->and(StatusFiche::EN_REVIEW->value)->toBe('en_review')
        ->and(StatusFiche::PUBLIE->value)->toBe('publie');
});

/**
 * Test 2 : Vérifie que les labels d'affichage sont corrects
 */
test('StatusFiche has correct labels', function (): void {
    expect(StatusFiche::BROUILLON->label())->toBe('Brouillon')
        ->and(StatusFiche::EN_REVIEW->label())->toBe('En relecture')
        ->and(StatusFiche::PUBLIE->label())->toBe('Publié');
});

/**
 * Test 3 : Vérifie que les descriptions sont présentes et cohérentes
 */
test('StatusFiche has correct descriptions', function (): void {
    expect(StatusFiche::BROUILLON->description())->toBe('Fiche en cours de rédaction')
        ->and(StatusFiche::EN_REVIEW->description())->toBe('Fiche prête, en attente de validation')
        ->and(StatusFiche::PUBLIE->description())->toBe('Fiche validée et visible publiquement');
});

/**
 * Test 4 : Vérifie que les couleurs sont des codes hexadécimaux valides
 */
test('StatusFiche has correct colors', function (): void {
    expect(StatusFiche::BROUILLON->couleur())->toBe('#6B7280')
        ->and(StatusFiche::EN_REVIEW->couleur())->toBe('#F59E0B')
        ->and(StatusFiche::PUBLIE->couleur())->toBe('#10B981');
});

/**
 * Test 5 : Vérifie que les icônes sont définies correctement
 * Important pour l'affichage dans l'interface (statuts, badges)
 */
test('StatusFiche has correct icons', function (): void {
    expect(StatusFiche::BROUILLON->icone())->toBe('edit')
        ->and(StatusFiche::EN_REVIEW->icone())->toBe('clock')
        ->and(StatusFiche::PUBLIE->icone())->toBe('check-circle');
});

/**
 * Test 6 : Vérifie que la méthode options() retourne le bon format
 * Crucial pour les selects Filament lors de la gestion des statuts
 */
test('StatusFiche options returns correct array structure', function (): void {
    $options = StatusFiche::options();

    expect($options)->toBeArray()
        ->and($options)->toHaveCount(3)
        ->and(array_keys($options))->toEqual([
            'brouillon', 'en_review', 'publie',
        ])
        ->and($options['brouillon'])->toBe('Brouillon')
        ->and($options['en_review'])->toBe('En relecture')
        ->and($options['publie'])->toBe('Publié');
});

/**
 * Test 7 : Vérifie que public() retourne uniquement les statuts visibles publiquement
 */
test('StatusFiche public returns only public statuses', function (): void {
    $publicStatuses = StatusFiche::public();

    expect($publicStatuses)->toBeArray()
        ->and($publicStatuses)->toHaveCount(1)
        ->and($publicStatuses)->toContain(StatusFiche::PUBLIE)
        ->and($publicStatuses)->not->toContain(StatusFiche::BROUILLON)
        ->and($publicStatuses)->not->toContain(StatusFiche::EN_REVIEW);
});

/**
 * Test 8 : Vérifie que editable() retourne les statuts modifiables
 */
test('StatusFiche editable returns editable statuses', function (): void {
    $editableStatuses = StatusFiche::editable();

    expect($editableStatuses)->toBeArray()
        ->and($editableStatuses)->toHaveCount(2)
        ->and($editableStatuses)->toContain(StatusFiche::BROUILLON)
        ->and($editableStatuses)->toContain(StatusFiche::EN_REVIEW)
        ->and($editableStatuses)->not->toContain(StatusFiche::PUBLIE);
});

/**
 * Test 9 : Vérifie que allowedTransitions() retourne les bonnes transitions
 */
test('StatusFiche allowedTransitions returns correct transitions', function (): void {
    // Depuis BROUILLON
    $brouillonTransitions = StatusFiche::BROUILLON->allowedTransitions();
    expect($brouillonTransitions)->toBeArray()
        ->and($brouillonTransitions)->toHaveCount(2)
        ->and($brouillonTransitions)->toContain(StatusFiche::EN_REVIEW)
        ->and($brouillonTransitions)->toContain(StatusFiche::PUBLIE)
        ->and($brouillonTransitions)->not->toContain(StatusFiche::BROUILLON);

    // Depuis EN_REVIEW
    $reviewTransitions = StatusFiche::EN_REVIEW->allowedTransitions();
    expect($reviewTransitions)->toBeArray()
        ->and($reviewTransitions)->toHaveCount(2)
        ->and($reviewTransitions)->toContain(StatusFiche::BROUILLON)
        ->and($reviewTransitions)->toContain(StatusFiche::PUBLIE)
        ->and($reviewTransitions)->not->toContain(StatusFiche::EN_REVIEW);

    // Depuis PUBLIE
    $publieTransitions = StatusFiche::PUBLIE->allowedTransitions();
    expect($publieTransitions)->toBeArray()
        ->and($publieTransitions)->toHaveCount(2)
        ->and($publieTransitions)->toContain(StatusFiche::BROUILLON)
        ->and($publieTransitions)->toContain(StatusFiche::EN_REVIEW)
        ->and($publieTransitions)->not->toContain(StatusFiche::PUBLIE);
});

/**
 * Test 10 : Vérifie que canTransitionTo() fonctionne correctement
 */
test('StatusFiche canTransitionTo works correctly', function (): void {
    // Depuis BROUILLON
    expect(StatusFiche::BROUILLON->canTransitionTo(StatusFiche::EN_REVIEW))->toBeTrue()
        ->and(StatusFiche::BROUILLON->canTransitionTo(StatusFiche::PUBLIE))->toBeTrue()
        ->and(StatusFiche::BROUILLON->canTransitionTo(StatusFiche::BROUILLON))->toBeFalse();

    // Depuis EN_REVIEW
    expect(StatusFiche::EN_REVIEW->canTransitionTo(StatusFiche::BROUILLON))->toBeTrue()
        ->and(StatusFiche::EN_REVIEW->canTransitionTo(StatusFiche::PUBLIE))->toBeTrue()
        ->and(StatusFiche::EN_REVIEW->canTransitionTo(StatusFiche::EN_REVIEW))->toBeFalse();

    // Depuis PUBLIE
    expect(StatusFiche::PUBLIE->canTransitionTo(StatusFiche::BROUILLON))->toBeTrue()
        ->and(StatusFiche::PUBLIE->canTransitionTo(StatusFiche::EN_REVIEW))->toBeTrue()
        ->and(StatusFiche::PUBLIE->canTransitionTo(StatusFiche::PUBLIE))->toBeFalse();
});

/**
 * Test 11 : Vérifie que getTransitionOptions() retourne le bon format
 */
test('StatusFiche getTransitionOptions returns correct format', function (): void {
    // Depuis BROUILLON
    $brouillonOptions = StatusFiche::BROUILLON->getTransitionOptions();
    expect($brouillonOptions)->toBeArray()
        ->and($brouillonOptions)->toHaveCount(2)
        ->and($brouillonOptions)->toHaveKeys(['en_review', 'publie'])
        ->and($brouillonOptions['en_review'])->toBe('En relecture')
        ->and($brouillonOptions['publie'])->toBe('Publié');

    // Depuis EN_REVIEW
    $reviewOptions = StatusFiche::EN_REVIEW->getTransitionOptions();
    expect($reviewOptions)->toBeArray()
        ->and($reviewOptions)->toHaveCount(2)
        ->and($reviewOptions)->toHaveKeys(['brouillon', 'publie'])
        ->and($reviewOptions['brouillon'])->toBe('Brouillon')
        ->and($reviewOptions['publie'])->toBe('Publié');

    // Depuis PUBLIE
    $publieOptions = StatusFiche::PUBLIE->getTransitionOptions();
    expect($publieOptions)->toBeArray()
        ->and($publieOptions)->toHaveCount(2)
        ->and($publieOptions)->toHaveKeys(['brouillon', 'en_review'])
        ->and($publieOptions['brouillon'])->toBe('Brouillon')
        ->and($publieOptions['en_review'])->toBe('En relecture');
});

/**
 * Test 12 : Vérifie que les couleurs sont des codes hexadécimaux valides
 */
test('StatusFiche colors are valid hex codes', function (): void {
    foreach (StatusFiche::cases() as $status) {
        $couleur = $status->couleur();
        expect($couleur)->toMatch('/^#[0-9A-F]{6}$/');
    }
});

/**
 * Test 13 : Vérifie la cohérence métier des méthodes public() et editable()
 */
test('StatusFiche public and editable methods are consistent', function (): void {
    $publicStatuses = StatusFiche::public();
    $editableStatuses = StatusFiche::editable();
    $allStatuses = StatusFiche::cases();

    // Conversion en valeurs string pour la comparaison
    $publicValues = array_map(fn (StatusFiche $status): string => $status->value, $publicStatuses);
    $editableValues = array_map(fn (StatusFiche $status): string => $status->value, $editableStatuses);
    $allValues = array_map(fn (StatusFiche $status): string => $status->value, $allStatuses);

    // Aucun statut ne peut être à la fois public et editable
    $intersection = array_intersect($publicValues, $editableValues);
    expect($intersection)->toBeEmpty();

    // Tous les statuts doivent être soit public soit editable
    $combined = array_merge($publicStatuses, $editableStatuses);
    expect(count($combined))->toBe(count($allStatuses));
});

/**
 * Test 14 : Vérifie la logique des transitions de workflow
 */
test('StatusFiche transitions follow logical workflow', function (): void {
    // Workflow classique : BROUILLON -> EN_REVIEW -> PUBLIE
    expect(StatusFiche::BROUILLON->canTransitionTo(StatusFiche::EN_REVIEW))->toBeTrue();
    expect(StatusFiche::EN_REVIEW->canTransitionTo(StatusFiche::PUBLIE))->toBeTrue();

    // Possibilité de revenir en arrière (corrections, dépublication)
    expect(StatusFiche::PUBLIE->canTransitionTo(StatusFiche::EN_REVIEW))->toBeTrue();
    expect(StatusFiche::PUBLIE->canTransitionTo(StatusFiche::BROUILLON))->toBeTrue();
    expect(StatusFiche::EN_REVIEW->canTransitionTo(StatusFiche::BROUILLON))->toBeTrue();

    // Raccourci direct BROUILLON -> PUBLIE (pour les admins)
    expect(StatusFiche::BROUILLON->canTransitionTo(StatusFiche::PUBLIE))->toBeTrue();
});

/**
 * Test 15 : Vérifie qu'aucune transition vers soi-même n'est possible
 */
test('StatusFiche cannot transition to itself', function (): void {
    foreach (StatusFiche::cases() as $status) {
        expect($status->canTransitionTo($status))->toBeFalse(
            "Le statut {$status->value} ne devrait pas pouvoir transitionner vers lui-même"
        );
    }
});
