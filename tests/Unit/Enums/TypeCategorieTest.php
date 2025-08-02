<?php

declare(strict_types=1);

use App\Enums\TypeCategorie;

/**
 * Test 1 : Vérifie que chaque case de l'enum a la bonne valeur string
 */
test('TypeCategorie has correct values', function (): void {
    expect(TypeCategorie::PROFESSION->value)->toBe('profession')
        ->and(TypeCategorie::EPOQUE->value)->toBe('epoque')
        ->and(TypeCategorie::NATIONALITE->value)->toBe('nationalite')
        ->and(TypeCategorie::MOUVEMENT->value)->toBe('mouvement')
        ->and(TypeCategorie::GENRE->value)->toBe('genre')
        ->and(TypeCategorie::LIEU->value)->toBe('lieu');
});

/**
 * Test 2 : Vérifie que les labels d'affichage sont corrects
 */
test('TypeCategorie has correct labels', function (): void {
    expect(TypeCategorie::PROFESSION->label())->toBe('Profession')
        ->and(TypeCategorie::EPOQUE->label())->toBe('Époque')
        ->and(TypeCategorie::NATIONALITE->label())->toBe('Nationalité')
        ->and(TypeCategorie::MOUVEMENT->label())->toBe('Mouvement')
        ->and(TypeCategorie::GENRE->label())->toBe('Genre')
        ->and(TypeCategorie::LIEU->label())->toBe('Lieu');
});

/**
 * Test 3 : Vérifie que les descriptions explicatives sont présentes
 */
test('TypeCategorie has correct descriptions', function (): void {
    expect(TypeCategorie::PROFESSION->description())->toBe('Métier, fonction ou statut social')
        ->and(TypeCategorie::EPOQUE->description())->toBe('Période historique, siècle ou ère')
        ->and(TypeCategorie::NATIONALITE->description())->toBe('Origine géographique, peuple ou nation')
        ->and(TypeCategorie::MOUVEMENT->description())->toBe('Courant artistique, philosophique ou politique')
        ->and(TypeCategorie::GENRE->description())->toBe('Genre artistique, littéraire ou musical')
        ->and(TypeCategorie::LIEU->description())->toBe('Lieu géographique, ville, région ou pays');
});

/**
 * Test 4 : Vérifie que les couleurs sont des codes hex valides
 */
test('TypeCategorie has correct colors', function (): void {
    expect(TypeCategorie::PROFESSION->couleur())->toBe('#3B82F6')
        ->and(TypeCategorie::EPOQUE->couleur())->toBe('#10B981')
        ->and(TypeCategorie::NATIONALITE->couleur())->toBe('#EF4444')
        ->and(TypeCategorie::MOUVEMENT->couleur())->toBe('#8B5CF6')
        ->and(TypeCategorie::GENRE->couleur())->toBe('#F59E0B')
        ->and(TypeCategorie::LIEU->couleur())->toBe('#6B7280');
});

/**
 * Test 5 : Vérifie que les icônes sont définies correctement
 * Important pour l'affichage dans l'interface (filtres, badges)
 */
/*test('TypeCategorie has correct icons', function (): void {
    expect(TypeCategorie::PROFESSION->icone())->toBe('briefcase')
        ->and(TypeCategorie::EPOQUE->icone())->toBe('calendar')
        ->and(TypeCategorie::NATIONALITE->icone())->toBe('flag')
        ->and(TypeCategorie::MOUVEMENT->icone())->toBe('trending-up')
        ->and(TypeCategorie::GENRE->icone())->toBe('tag')
        ->and(TypeCategorie::LIEU->icone())->toBe('map-pin');
});*/

/**
 * Test 6 : Vérifie que la méthode options() retourne le bon format
 * Crucial pour les selects Filament lors de la création de catégories
 */
test('TypeCategorie options returns correct array structure', function (): void {
    $options = TypeCategorie::options();

    expect($options)->toBeArray()
        ->and($options)->toHaveCount(6)
        ->and(array_keys($options))->toEqual([
            'profession', 'epoque', 'nationalite', 'mouvement', 'genre', 'lieu',
        ])
        ->and($options['profession'])->toBe('Profession')
        ->and($options['epoque'])->toBe('Époque')
        ->and($options['nationalite'])->toBe('Nationalité')
        ->and($options['mouvement'])->toBe('Mouvement')
        ->and($options['genre'])->toBe('Genre')
        ->and($options['lieu'])->toBe('Lieu');
});

/**
 * Test 7 : Vérifie que tous les types ont des exemples et que ces exemples sont cohérents
 */
test('TypeCategorie exemples returns valid arrays', function (): void {
    foreach (TypeCategorie::cases() as $type) {
        $exemples = $type->getExemples();

        expect($exemples)->toBeArray()
            ->and($exemples)->not->toBeEmpty()
            ->and($exemples)->toHaveCount(5); // Chaque type a exactement 5 exemples

        // Vérifier que tous les exemples sont des strings non vides
        foreach ($exemples as $exemple) {
            expect($exemple)->toBeString()->not->toBeEmpty();
        }
    }
});

/**
 * Test 8 : Vérifie la cohérence des exemples par type
 * Test que les exemples correspondent bien au type de catégorie
 */
test('TypeCategorie exemples are coherent with type', function (): void {
    // PROFESSION doit contenir des métiers
    $professionExemples = TypeCategorie::PROFESSION->getExemples();
    expect($professionExemples)->toContain('Écrivain')
        ->and($professionExemples)->toContain('Empereur')
        ->and($professionExemples)->toContain('Peintre');

    // EPOQUE doit contenir des périodes historiques
    $epoqueExemples = TypeCategorie::EPOQUE->getExemples();
    expect($epoqueExemples)->toContain('XIXe siècle')
        ->and($epoqueExemples)->toContain('Antiquité')
        ->and($epoqueExemples)->toContain('Renaissance');

    // NATIONALITE doit contenir des nationalités
    $nationaliteExemples = TypeCategorie::NATIONALITE->getExemples();
    expect($nationaliteExemples)->toContain('Français')
        ->and($nationaliteExemples)->toContain('Romain')
        ->and($nationaliteExemples)->toContain('Italien');

    // MOUVEMENT doit contenir des courants artistiques
    $mouvementExemples = TypeCategorie::MOUVEMENT->getExemples();
    expect($mouvementExemples)->toContain('Romantisme')
        ->and($mouvementExemples)->toContain('Impressionnisme')
        ->and($mouvementExemples)->toContain('Baroque');

    // GENRE doit contenir des genres artistiques
    $genreExemples = TypeCategorie::GENRE->getExemples();
    expect($genreExemples)->toContain('Roman')
        ->and($genreExemples)->toContain('Poésie')
        ->and($genreExemples)->toContain('Symphonie');

    // LIEU doit contenir des lieux géographiques
    $lieuExemples = TypeCategorie::LIEU->getExemples();
    expect($lieuExemples)->toContain('France')
        ->and($lieuExemples)->toContain('Paris')
        ->and($lieuExemples)->toContain('Rome');
});

/**
 * Test 9 : Vérifie que tous les types sont bien définis (aucun manquant)
 */
test('TypeCategorie has all expected cases', function (): void {
    $cases = TypeCategorie::cases();

    expect($cases)->toHaveCount(6);

    $values = array_map(fn (TypeCategorie $type): string => $type->value, $cases);
    expect($values)->toEqual([
        'profession', 'epoque', 'nationalite', 'mouvement', 'genre', 'lieu',
    ]);
});
