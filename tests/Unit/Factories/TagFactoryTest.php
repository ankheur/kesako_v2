<?php

declare(strict_types=1);

use App\Models\Fiche;
use App\Models\Tag;

/**
 * Test 1 : Vérifie que la factory peut créer des tags avec les attributs de base
 */
test('TagFactory peut créer un tag de base', function (): void {
    $tag = Tag::factory()->create();

    expect($tag)->toBeInstanceOf(Tag::class)
        ->and($tag->exists)->toBeTrue()
        ->and($tag->nom)->toBeString()->not->toBeEmpty()
        ->and($tag->slug)->toBeString()->not->toBeEmpty()
        ->and($tag->description)->toBeString()->not->toBeEmpty()
        ->and($tag->fiche_id)->toBeNull()
        ->and($tag->ordre)->toBeInt()->toBeBetween(1, 100)
        ->and($tag->is_featured)->toBeFalse()
        ->and($tag->is_active)->toBeTrue();
});

/**
 * Test 2 : Vérifie que la factory génère des noms prédéfinis variés
 */
test('TagFactory génère des noms prédéfinis variés', function (): void {
    $tags = Tag::factory()->count(20)->create();

    /** @var Illuminate\Support\Collection<int, string> $noms */
    $noms = $tags->pluck('nom');

    $validNoms = [
        'Empereurs romains',
        'Rois de France',
        'Poésie romantique',
        'Impressionnisme',
        'Révolutions françaises',
        'Compositeurs classiques',
        'Philosophes antiques',
        'Peintres italiens',
        'Jazz américain',
        'Architecture gothique',
    ];

    // Tous les noms doivent être dans la liste prédéfinie
    $noms->each(function (string $nom) use ($validNoms): void {
        expect($nom)->toBeIn($validNoms);
    });

    // On devrait avoir une variété de noms sur 20 tags
    $nomsUniques = $noms->unique();
    expect($nomsUniques->count())->toBeGreaterThan(5);
});

/**
 * Test 3 : Vérifie la création d'un tag en vedette
 */
test('TagFactory peut créer un tag en vedette', function (): void {
    $tag = Tag::factory()->featured()->create();

    expect($tag->is_featured)->toBeTrue()
        ->and($tag->ordre)->toBeInt()->toBeBetween(1, 10)
        ->and($tag->is_active)->toBeTrue(); // Reste actif par défaut
});

/**
 * Test 4 : Vérifie la création d'un tag inactif
 */
test('TagFactory peut créer un tag inactif', function (): void {
    $tag = Tag::factory()->inactive()->create();

    expect($tag->is_active)->toBeFalse()
        ->and($tag->is_featured)->toBeFalse() // Reste non-featured par défaut
        ->and($tag->ordre)->toBeInt()->toBeBetween(1, 100);
});

/**
 * Test 5 : Vérifie la création d'un tag avec fiche collection
 */
test('TagFactory peut créer un tag avec fiche collection', function (): void {
    $tag = Tag::factory()->withFiche()->create();

    expect($tag->fiche_id)->toBeInt()
        ->and($tag->is_active)->toBeTrue()
        ->and($tag->is_featured)->toBeFalse();

    // Vérifier que la fiche existe et est publiée
    if (method_exists($tag, 'fiche')) {
        $fiche = $tag->fiche;
        expect($fiche)->toBeInstanceOf(Fiche::class);
        if ($fiche instanceof Fiche) {
            expect($fiche->published_at)->not->toBeNull();
        }
    }
});

/**
 * Test 6 : Vérifie les combinaisons de states
 */
test('TagFactory peut combiner plusieurs states', function (): void {
    // Tag featured + inactive
    $tagFeaturedInactive = Tag::factory()->featured()->inactive()->create();
    expect($tagFeaturedInactive->is_featured)->toBeTrue()
        ->and($tagFeaturedInactive->is_active)->toBeFalse()
        ->and($tagFeaturedInactive->ordre)->toBeInt()->toBeBetween(1, 10);

    // Tag featured + with fiche
    $tagFeaturedWithFiche = Tag::factory()->featured()->withFiche()->create();
    expect($tagFeaturedWithFiche->is_featured)->toBeTrue()
        ->and($tagFeaturedWithFiche->fiche_id)->toBeInt()
        ->and($tagFeaturedWithFiche->is_active)->toBeTrue();

    // Tag inactive + with fiche
    $tagInactiveWithFiche = Tag::factory()->inactive()->withFiche()->create();
    expect($tagInactiveWithFiche->is_active)->toBeFalse()
        ->and($tagInactiveWithFiche->fiche_id)->toBeInt()
        ->and($tagInactiveWithFiche->is_featured)->toBeFalse();
});

/**
 * Test 7 : Vérifie la relation avec Fiche
 */
test('TagFactory gère correctement la relation avec Fiche', function (): void {
    $fiche = Fiche::factory()->published()->create();
    $tag = Tag::factory()->for($fiche, 'fiche')->create();

    expect($tag->fiche_id)->toBe($fiche->id);

    // Vérifier la relation si elle existe
    if (method_exists($tag, 'fiche')) {
        $ficheRelation = $tag->fiche;
        expect($ficheRelation)->toBeInstanceOf(Fiche::class);
        if ($ficheRelation instanceof Fiche) {
            expect($ficheRelation->id)->toBe($fiche->id);
        }
    }
});

/**
 * Test 8 : Vérifie la génération de slugs variés
 */
test('TagFactory génère des slugs variés', function (): void {
    $tags = Tag::factory()->count(15)->create();

    /** @var Illuminate\Support\Collection<int, string> $slugs */
    $slugs = $tags->pluck('slug');

    // Tous les slugs devraient être uniques (faker génère des slugs aléatoires)
    $slugsUniques = $slugs->unique();
    expect($slugsUniques->count())->toBeGreaterThan(10);

    // Tous les slugs devraient être des strings non vides
    $slugs->each(function (string $slug): void {
        expect($slug)->toBeString()->not->toBeEmpty();
    });
});

/**
 * Test 9 : Vérifie la génération de descriptions variées
 */
test('TagFactory génère des descriptions variées', function (): void {
    $tags = Tag::factory()->count(10)->create();

    /** @var Illuminate\Support\Collection<int, string> $descriptions */
    $descriptions = $tags->pluck('description');

    // Toutes les descriptions devraient être uniques
    $descriptionsUniques = $descriptions->unique();
    expect($descriptionsUniques->count())->toBe(10);

    // Chaque description devrait être une phrase d'environ 8 mots
    $descriptions->each(function (string $description): void {
        expect($description)->toBeString()->not->toBeEmpty();
        $wordCount = str_word_count($description);
        expect($wordCount)->toBeGreaterThan(5)->toBeLessThan(15);
    });
});

/**
 * Test 10 : Vérifie la plage d'ordre et sa variabilité
 */
test('TagFactory génère des ordres dans la bonne plage avec variabilité', function (): void {
    // Tags normaux (ordre 1-100)
    $tagsNormaux = Tag::factory()->count(20)->create();
    /** @var Illuminate\Support\Collection<int, int> $ordresNormaux */
    $ordresNormaux = $tagsNormaux->pluck('ordre');

    $ordresNormauxArray = $ordresNormaux->toArray();
    expect(min($ordresNormauxArray))->toBeGreaterThanOrEqual(1)
        ->and(max($ordresNormauxArray))->toBeLessThanOrEqual(100);

    // Tags featured (ordre 1-10)
    $tagsFeatured = Tag::factory()->featured()->count(10)->create();
    /** @var Illuminate\Support\Collection<int, int> $ordresFeatured */
    $ordresFeatured = $tagsFeatured->pluck('ordre');

    $ordresFeaturedArray = $ordresFeatured->toArray();
    expect(min($ordresFeaturedArray))->toBeGreaterThanOrEqual(1)
        ->and(max($ordresFeaturedArray))->toBeLessThanOrEqual(10);

    // Vérifier la variabilité
    expect($ordresNormaux->unique()->count())->toBeGreaterThan(10);
    expect($ordresFeatured->unique()->count())->toBeGreaterThan(3);
});

/**
 * Test 11 : Vérifie les valeurs par défaut booléennes
 */
test('TagFactory définit correctement les valeurs par défaut booléennes', function (): void {
    $tags = Tag::factory()->count(10)->create();

    // Par défaut : is_featured = false, is_active = true
    $tags->each(function (Tag $tag): void {
        expect($tag->is_featured)->toBeBool()
            ->and($tag->is_active)->toBeBool()
            ->and($tag->is_featured)->toBeFalse()
            ->and($tag->is_active)->toBeTrue();
    });
});

/**
 * Test 12 : Vérifie la création en masse avec différents states
 */
test('TagFactory peut créer en masse avec différents states', function (): void {
    $tagsFeatured = Tag::factory()->featured()->count(5)->create();
    $tagsInactive = Tag::factory()->inactive()->count(3)->create();
    $tagsWithFiche = Tag::factory()->withFiche()->count(4)->create();

    // Vérifier les comptes
    expect($tagsFeatured)->toHaveCount(5)
        ->and($tagsInactive)->toHaveCount(3)
        ->and($tagsWithFiche)->toHaveCount(4);

    // Vérifier les propriétés
    $tagsFeatured->each(fn (Tag $tag) => expect($tag->is_featured)->toBeTrue());
    $tagsInactive->each(fn (Tag $tag) => expect($tag->is_active)->toBeFalse());
    $tagsWithFiche->each(fn (Tag $tag) => expect($tag->fiche_id)->toBeInt());
});

/**
 * Test 13 : Vérifie la cohérence des données entre les attributs
 */
test('TagFactory maintient la cohérence entre les attributs', function (): void {
    $tag = Tag::factory()->create();

    // Le nom doit être cohérent avec les options prédéfinies
    $validNoms = [
        'Empereurs romains', 'Rois de France', 'Poésie romantique',
        'Impressionnisme', 'Révolutions françaises', 'Compositeurs classiques',
        'Philosophes antiques', 'Peintres italiens', 'Jazz américain', 'Architecture gothique',
    ];

    expect($tag->nom)->toBeIn($validNoms);

    // Les valeurs booléennes doivent être strictement boolean
    expect($tag->is_featured)->toBeBool()
        ->and($tag->is_active)->toBeBool();

    // L'ordre doit être un entier positif
    expect($tag->ordre)->toBeInt()->toBeGreaterThan(0);

    // Si pas de fiche_id, il doit être null (pas 0 ou string vide)
    if ($tag->fiche_id === null) {
        expect($tag->fiche_id)->toBeNull();
    } else {
        expect($tag->fiche_id)->toBeInt()->toBeGreaterThan(0);
    }
});
