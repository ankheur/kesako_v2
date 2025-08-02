<?php

declare(strict_types=1);

use App\Enums\TypeCategorie;
use App\Models\Categorie;
use App\Models\Fiche;

/**
 * Test 1 : Vérifie que la factory peut créer des catégories avec les attributs de base
 */
test('CategorieFactory peut créer une catégorie de base', function (): void {
    $categorie = Categorie::factory()->create();

    expect($categorie)->toBeInstanceOf(Categorie::class)
        ->and($categorie->exists)->toBeTrue()
        ->and($categorie->titre)->toBeString()->not->toBeEmpty()
        ->and($categorie->slug)->toBeString()->not->toBeEmpty()
        ->and($categorie->type_categorie)->toBeInstanceOf(TypeCategorie::class)
        ->and($categorie->description)->toBeString()->not->toBeEmpty()
        ->and($categorie->fiche_id)->toBeNull()
        ->and($categorie->ordre)->toBeInt()->toBeBetween(1, 20)
        ->and($categorie->published_at)->toBeNull();
});

/**
 * Test 2 : Vérifie que la factory génère des slugs uniques
 */
test('CategorieFactory génère des slugs uniques', function (): void {
    $categories = Categorie::factory()->count(10)->create();

    $slugs = $categories->pluck('slug')->toArray();
    $uniqueSlugs = array_unique($slugs);

    expect(count($slugs))->toBe(count($uniqueSlugs));
});

/**
 * Test 3 : Vérifie que la factory utilise des types de catégories valides
 */
test('CategorieFactory génère des types de catégories valides', function (): void {
    $categories = Categorie::factory()->count(20)->create();
    $typesCategoriesGenerees = $categories->pluck('type_categorie')->unique()->toArray();

    foreach ($typesCategoriesGenerees as $type) {
        expect($type)->toBeInstanceOf(TypeCategorie::class)
            ->and(in_array($type, TypeCategorie::cases(), true))->toBeTrue();
    }
});

/**
 * Test 4 : Vérifie que la factory peut créer plusieurs catégories en lot
 */
test('CategorieFactory peut créer plusieurs catégories en lot', function (): void {
    $categories = Categorie::factory()->count(5)->create();

    expect($categories)->toHaveCount(5)
        ->and($categories->every(fn ($categorie) => $categorie->exists))->toBeTrue();
});

/**
 * Test 5 : Vérifie que le state published fonctionne complètement
 */
/**
 * Test 5 : Vérifie que le state published fonctionne complètement
 */
test('CategorieFactory state published fonctionne complètement', function (): void {
    // Test avec date par défaut (now)
    $avant = now()->subDays();
    $categorieDefaut = Categorie::factory()->published()->create();
    $apres = now()->addDay();

    expect($categorieDefaut->published_at)->not->toBeNull()
        ->and($categorieDefaut->published_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($categorieDefaut->published_at->greaterThanOrEqualTo($avant))->toBeTrue()
        ->and($categorieDefaut->published_at->lessThanOrEqualTo($apres))->toBeTrue();

    // Test avec date personnalisée
    $dateCustom = now()->subDays(10);
    $categorieCustom = Categorie::factory()->published($dateCustom)->create();

    expect($categorieCustom->published_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($categorieCustom->published_at->format('Y-m-d H:i:s'))->toBe($dateCustom->format('Y-m-d H:i:s'));
});

/**
 * Test 6 : Vérifie que ofType fonctionne avec tous les types de catégories et génère des titres cohérents
 */
test('CategorieFactory ofType fonctionne avec tous les types et génère des titres cohérents', function (): void {
    foreach (TypeCategorie::cases() as $type) {
        $categorie = Categorie::factory()->ofType($type)->create();

        expect($categorie->type_categorie)->toBe($type)
            ->and($type->getExemples())->toContain($categorie->titre);
    }
});

/**
 * Test 7 : Vérifie que le state withFiche fonctionne complètement
 */
test('CategorieFactory state withFiche fonctionne complètement', function (): void {
    $categorie = Categorie::factory()->withFiche()->create();

    expect($categorie->fiche_id)->not->toBeNull()
        ->and($categorie->fiche_id)->toBeInt()
        ->and($categorie->fiche)->toBeInstanceOf(Fiche::class)
        ->and($categorie->fiche->published_at)->not->toBeNull(); // Fiche publiée
});

/**
 * Test 8 : Vérifie que les states peuvent être combinés
 */
test('CategorieFactory states peuvent être combinés', function (): void {
    $categorie = Categorie::factory()
        ->published()
        ->ofType(TypeCategorie::NATIONALITE)
        ->withFiche()
        ->create();

    expect($categorie->published_at)->not->toBeNull()
        ->and($categorie->type_categorie)->toBe(TypeCategorie::NATIONALITE)
        ->and($categorie->fiche_id)->not->toBeNull()
        ->and(TypeCategorie::NATIONALITE->getExemples())->toContain($categorie->titre);
});

/**
 * Test 9 : Vérifie que la factory peut être utilisée avec make() sans persister
 */
test('CategorieFactory peut utiliser make sans persister', function (): void {
    $categorie = Categorie::factory()->make();

    expect($categorie)->toBeInstanceOf(Categorie::class)
        ->and($categorie->exists)->toBeFalse()
        ->and($categorie->titre)->toBeString()->not->toBeEmpty()
        ->and($categorie->type_categorie)->toBeInstanceOf(TypeCategorie::class);
});

/**
 * Test 10 : Vérifie que les attributs manuels surchargent les states
 */
test('CategorieFactory attributs manuels surchargent les states', function (): void {
    // Test surcharge des states
    $categorie = Categorie::factory()
        ->ofType(TypeCategorie::PROFESSION)
        ->create([
            'type_categorie' => TypeCategorie::LIEU,
            'titre' => 'Titre Forcé',
        ]);

    expect($categorie->type_categorie)->toBe(TypeCategorie::LIEU)
        ->and($categorie->titre)->toBe('Titre Forcé');

    // Test respect des attributs manuels simples
    $attributsCustom = [
        'titre' => 'Titre Custom',
        'slug' => 'titre-custom',
        'description' => 'Description personnalisée',
        'ordre' => 99,
    ];

    $categorieCustom = Categorie::factory()->create($attributsCustom);

    expect($categorieCustom->titre)->toBe('Titre Custom')
        ->and($categorieCustom->slug)->toBe('titre-custom')
        ->and($categorieCustom->description)->toBe('Description personnalisée')
        ->and($categorieCustom->ordre)->toBe(99);
});

/**
 * Test 11 : Vérifie que la factory gère correctement les valeurs null par défaut
 */
test('CategorieFactory gère les valeurs null par défaut', function (): void {
    $categorie = Categorie::factory()->create();

    expect($categorie->fiche_id)->toBeNull()
        ->and($categorie->published_at)->toBeNull();
});

/**
 * Test 12 : Vérifie la distribution des types de catégories sur un large échantillon
 */
test('CategorieFactory distribue bien les types de catégories', function (): void {
    $categories = Categorie::factory()->count(100)->create();
    $typesGeneres = $categories->groupBy('type_categorie')->keys()->toArray();

    // On devrait avoir plusieurs types différents sur 100 catégories
    expect(count($typesGeneres))->toBeGreaterThan(1)
        ->and(count($typesGeneres))->toBeLessThanOrEqual(count(TypeCategorie::cases()));
});

/**
 * Test 13 : Vérifie que l'ordre est bien dans la plage attendue
 */
test('CategorieFactory génère des ordres dans la bonne plage', function (): void {
    $categories = Categorie::factory()->count(20)->create();

    foreach ($categories as $categorie) {
        expect($categorie->ordre)->toBeBetween(1, 20);
    }
});
