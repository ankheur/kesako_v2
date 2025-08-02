<?php

declare(strict_types=1);

use App\Models\Domaine;
use App\Models\Fiche;

/**
 * Test 1 : Vérifie que la factory peut créer des domaines avec les attributs de base
 */
test('DomaineFactory peut créer un domaine de base', function (): void {
    $domaine = Domaine::factory()->create();

    expect($domaine)->toBeInstanceOf(Domaine::class)
        ->and($domaine->exists)->toBeTrue()
        ->and($domaine->titre)->toBeString()->not->toBeEmpty()
        ->and($domaine->slug)->toBeString()->not->toBeEmpty()
        ->and($domaine->icone)->toBeString()->toStartWith('domaines/')
        ->and($domaine->icone)->toEndWith('.svg')
        ->and($domaine->description)->toBeString()->not->toBeEmpty()
        ->and($domaine->couleur)->toBeString()->toMatch('/^#[0-9A-Fa-f]{6}$/')
        ->and($domaine->fiche_id)->toBeNull()
        ->and($domaine->published_at)->toBeNull()
        ->and($domaine->ordre)->toBeInt()->toBeBetween(1, 10);
});

/**
 * Test 2 : Vérifie que la factory génère des slugs uniques
 */
test('DomaineFactory génère des slugs uniques', function (): void {
    $domaines = Domaine::factory()->count(10)->create();

    $slugs = $domaines->pluck('slug')->toArray();
    $uniqueSlugs = array_unique($slugs);

    expect(count($slugs))->toBe(count($uniqueSlugs));
});

/**
 * Test 3 : Vérifie que la factory utilise des titres prédéfinis valides
 */
test('DomaineFactory génère des titres valides', function (): void {
    $titresAttendus = [
        'Histoire', 'Littérature', 'Musique', 'Sciences',
        'Arts', 'Philosophie', 'Politique', 'Géographie',
    ];

    $domaines = Domaine::factory()->count(20)->create();
    $titresGeneres = $domaines->pluck('titre')->unique()->toArray();

    foreach ($titresGeneres as $titre) {
        expect($titresAttendus)->toContain($titre);
    }
});

/**
 * Test 4 : Vérifie que l'icône est bien créé
 */
test('DomaineFactory génère une icônes', function (): void {
    $domaine = Domaine::factory()->create(['titre' => 'Histoire']);

    expect($domaine->icone)->toStartWith('domaines/')
        ->and($domaine->icone)->toEndWith('.svg')
        ->and($domaine->titre)->toBe('Histoire');
});

/**
 * Test 5 : Vérifie que le state published fonctionne complètement
 */
test('DomaineFactory state published fonctionne complètement', function (): void {
    // Test avec date par défaut (now)
    $avant = now()->subDays();
    $domaineDefaut = Domaine::factory()->published()->create();
    $apres = now()->addDay();

    expect($domaineDefaut->published_at)->not->toBeNull()
        ->and($domaineDefaut->published_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($domaineDefaut->published_at->greaterThanOrEqualTo($avant))->toBeTrue()
        ->and($domaineDefaut->published_at->lessThanOrEqualTo($apres))->toBeTrue();

    // Test avec date personnalisée
    $dateCustom = now()->subDays(5);
    $domaineCustom = Domaine::factory()->published($dateCustom)->create();

    expect($domaineCustom->published_at->format('Y-m-d H:i:s'))->toBe($dateCustom->format('Y-m-d H:i:s'));
});

/**
 * Test 6 : Vérifie que le state featured fonctionne complètement
 */
test('DomaineFactory state featured met en vedette et publie', function (): void {
    $domaineFeatured = Domaine::factory()->featured()->create();

    expect($domaineFeatured->ordre)->toBe(0) // Ordre prioritaire
        ->and($domaineFeatured->published_at)->not->toBeNull(); // Auto-publié
});

/**
 * Test 7 : Vérifie que le state withFiche fonctionne complètement
 */
test('DomaineFactory state withFiche fonctionne complètement', function (): void {
    $domaine = Domaine::factory()->withFiche()->create();

    expect($domaine->fiche_id)->not->toBeNull()
        ->and($domaine->fiche_id)->toBeInt()
        ->and($domaine->fiche)->toBeInstanceOf(Fiche::class)
        ->and($domaine->fiche->published_at)->not->toBeNull(); // Fiche publiée
});

/**
 * Test 8 : Vérifie que les states peuvent être combinés
 */
test('DomaineFactory states peuvent être combinés', function (): void {
    $domaine = Domaine::factory()
        ->published()
        ->withFiche()
        ->create();

    expect($domaine->published_at)->not->toBeNull()
        ->and($domaine->fiche_id)->not->toBeNull();

    // Test avec featured (qui inclut déjà published)
    $domaineFeatured = Domaine::factory()
        ->featured()
        ->withFiche()
        ->create();

    expect($domaineFeatured->ordre)->toBe(0)
        ->and($domaineFeatured->published_at)->not->toBeNull()
        ->and($domaineFeatured->fiche_id)->not->toBeNull();
});

/**
 * Test 9 : Vérifie que la factory peut être utilisée avec make() sans persister
 */
test('DomaineFactory peut utiliser make sans persister', function (): void {
    $domaine = Domaine::factory()->make();

    expect($domaine)->toBeInstanceOf(Domaine::class)
        ->and($domaine->exists)->toBeFalse()
        ->and($domaine->titre)->toBeString()->not->toBeEmpty()
        ->and($domaine->couleur)->toMatch('/^#[0-9A-Fa-f]{6}$/');
});

/**
 * Test 10 : Vérifie que les attributs manuels surchargent les states
 */
test('DomaineFactory attributs manuels surchargent les states', function (): void {
    // Test surcharge des states
    $domaine = Domaine::factory()
        ->featured()
        ->create([
            'ordre' => 5,
            'published_at' => null,
        ]);

    expect($domaine->ordre)->toBe(5) // Surcharge featured
        ->and($domaine->published_at)->toBeNull(); // Surcharge featured

    // Test respect des attributs manuels simples
    $attributsCustom = [
        'titre' => 'Domaine Custom',
        'slug' => 'domaine-custom',
        'couleur' => '#FF0000',
        'ordre' => 99,
    ];

    $domaineCustom = Domaine::factory()->create($attributsCustom);

    expect($domaineCustom->titre)->toBe('Domaine Custom')
        ->and($domaineCustom->slug)->toBe('domaine-custom')
        ->and($domaineCustom->couleur)->toBe('#FF0000')
        ->and($domaineCustom->ordre)->toBe(99);
});

/**
 * Test 11 : Vérifie que la factory gère correctement les valeurs null par défaut
 */
test('DomaineFactory gère les valeurs null par défaut', function (): void {
    $domaine = Domaine::factory()->create();

    expect($domaine->fiche_id)->toBeNull()
        ->and($domaine->published_at)->toBeNull();
});

/**
 * Test 12 : Vérifie la distribution des titres sur un large échantillon
 */
test('DomaineFactory distribue bien les titres de domaines', function (): void {
    $domaines = Domaine::factory()->count(50)->create();
    $titresGeneres = $domaines->groupBy('titre')->keys()->toArray();

    // On devrait avoir plusieurs titres différents sur 50 domaines
    expect(count($titresGeneres))->toBeGreaterThan(1)
        ->and(count($titresGeneres))->toBeLessThanOrEqual(8); // 8 titres max possibles
});

/**
 * Test 13 : Vérifie que l'ordre est bien dans la plage attendue pour les domaines normaux
 */
test('DomaineFactory génère des ordres dans la bonne plage', function (): void {
    $domaines = Domaine::factory()->count(20)->create();

    foreach ($domaines as $domaine) {
        expect($domaine->ordre)->toBeBetween(1, 10);
    }
});

/**
 * Test 14 : Vérifie que la factory peut créer plusieurs domaines en lot
 */
test('DomaineFactory peut créer plusieurs domaines en lot', function (): void {
    $domaines = Domaine::factory()->count(5)->create();

    expect($domaines)->toHaveCount(5)
        ->and($domaines->every(fn ($domaine) => $domaine->exists))->toBeTrue();
});

/**
 * Test 15 : Vérifie que les couleurs générées sont des codes hexadécimaux valides
 */
test('DomaineFactory génère des couleurs hexadécimales valides', function (): void {
    $domaines = Domaine::factory()->count(10)->create();

    foreach ($domaines as $domaine) {
        expect($domaine->couleur)->toMatch('/^#[0-9A-Fa-f]{6}$/')
            ->and(mb_strlen($domaine->couleur))->toBe(7); // # + 6 caractères
    }
});
