<?php

declare(strict_types=1);

use App\Enums\StatusFiche;
use App\Enums\TypeFiche;
use App\Models\Fiche;
use Illuminate\Support\Collection;

/**
 * Test 1 : Vérifie que la factory peut créer des fiches avec les attributs de base
 */
test('FicheFactory peut créer une fiche de base', function (): void {
    $fiche = Fiche::factory()->create();

    expect($fiche)->toBeInstanceOf(Fiche::class)
        ->and($fiche->exists)->toBeTrue()
        ->and($fiche->titre)->toBeString()->not->toBeEmpty()
        ->and($fiche->slug)->toBeString()->not->toBeEmpty()
        ->and($fiche->description)->toBeString()->not->toBeEmpty()
        ->and($fiche->contenu)->toBeString()->not->toBeEmpty()
        ->and($fiche->type_fiche)->toBeInstanceOf(TypeFiche::class)
        ->and($fiche->status)->toBe(StatusFiche::BROUILLON)
        ->and($fiche->published_at)->toBeNull()
        ->and($fiche->featured_at)->toBeNull()
        ->and($fiche->created_at)->not->toBeNull()
        ->and($fiche->updated_at)->not->toBeNull();
});

/**
 * Test 2 : Vérifie la génération optionnelle de soustitre et illustration
 */
test('FicheFactory génère soustitre et illustration optionnels', function (): void {
    $fiches = Fiche::factory()->count(20)->create();

    // Vérification des soustitres (probabilité 0.7)
    /** @var Collection<int, string|null> $soustitres */
    $soustitres = $fiches->pluck('soustitre')->filter();
    expect($soustitres->count())->toBeGreaterThan(10);

    // Vérification des illustrations (probabilité 0.8)
    /** @var Collection<int, string|null> $illustrations */
    $illustrations = $fiches->pluck('illustration')->filter();
    expect($illustrations->count())->toBeGreaterThan(12);

    $validIllustrations = [
        'illustrations/fiche-1.jpg',
        'illustrations/fiche-2.jpg',
        'illustrations/fiche-3.jpg',
        'illustrations/fiche-4.jpg',
        'illustrations/fiche-5.jpg',
    ];

    $illustrations->each(function (mixed $illustration) use ($validIllustrations): void {
        expect($illustration)->toBeString()->toBeIn($validIllustrations);
    });
});

/**
 * Test 3 : Vérifie la logique de génération automatique de illustration_alt
 */
test('FicheFactory génère illustration_alt selon la présence d\'illustration', function (): void {
    // Avec illustration
    $ficheAvecIllustration = Fiche::factory()->state(['illustration' => 'illustrations/fiche-1.jpg'])->create();
    expect($ficheAvecIllustration->illustration)->toBe('illustrations/fiche-1.jpg')
        ->and($ficheAvecIllustration->illustration_alt)->toBe('Illustration de '.$ficheAvecIllustration->titre);
});

/**
 * Test 4 : Vérifie le slug généré à partir du titre avec unicité
 */
test('FicheFactory génère des slugs uniques basés sur le titre', function (): void {
    $titre = 'Test Titre Unique';
    $fiches = Fiche::factory()->state(['titre' => $titre])->count(3)->create();

    /** @var Collection<int, string> $slugs */
    $slugs = $fiches->pluck('slug');

    // Tous les slugs devraient être uniques
    expect($slugs->unique()->count())->toBe(3);
});

/**
 * Test 5 : Vérifie les différents états de publication
 */
test('FicheFactory peut créer des fiches dans différents états', function (): void {
    // Fiche publiée
    $fichePubliee = Fiche::factory()->published()->create();
    expect($fichePubliee->status)->toBe(StatusFiche::PUBLIE)
        ->and($fichePubliee->published_at)->not->toBeNull();

    // Fiche en relecture
    $ficheEnReview = Fiche::factory()->inReview()->create();
    expect($ficheEnReview->status)->toBe(StatusFiche::EN_REVIEW)
        ->and($ficheEnReview->published_at)->toBeNull();

    // Fiche en vedette
    $ficheFeatured = Fiche::factory()->featured()->create();
    expect($ficheFeatured->status)->toBe(StatusFiche::PUBLIE)
        ->and($ficheFeatured->published_at)->not->toBeNull()
        ->and($ficheFeatured->featured_at)->not->toBeNull();
});

/**
 * Test 6 : Vérifie la création de fiches avec dates personnalisées
 */
test('FicheFactory peut créer des fiches avec dates personnalisées', function (): void {
    $dateCustom = now()->subDays(10);

    $fichePubliee = Fiche::factory()->published($dateCustom)->create();
    expect($fichePubliee->published_at->toDateString())->toBe($dateCustom->toDateString());

    $ficheFeatured = Fiche::factory()->featured($dateCustom)->create();
    expect($ficheFeatured->featured_at->toDateString())->toBe($dateCustom->toDateString())
        ->and($ficheFeatured->published_at->toDateString())->toBe($dateCustom->toDateString());
});

/**
 * Test 7 : Vérifie la création de fiches par type spécifique
 */
test('FicheFactory peut créer des fiches de types spécifiques', function (): void {
    // Test de chaque type d'enum
    $typesToTest = [
        TypeFiche::BIOGRAPHIE,
        TypeFiche::OEUVRE,
        TypeFiche::EVENEMENT,
        TypeFiche::THEME,
        TypeFiche::CONCEPT,
        TypeFiche::CHRONOLOGIE,
    ];

    foreach ($typesToTest as $type) {
        $fiche = Fiche::factory()->ofType($type)->create();
        expect($fiche->type_fiche)->toBe($type)
            ->and($fiche->contenu)->toBeString()->not->toBeEmpty();
    }
});

/**
 * Test 8 : Vérifie la génération de contenu HTML riche
 */
test('FicheFactory génère du contenu HTML riche valide', function (): void {
    $fiche = Fiche::factory()->create();
    $contenu = $fiche->contenu;

    expect($contenu)->toBeString()->not->toBeEmpty();

    // Vérifier la présence de balises HTML
    expect($contenu)->toContain('<p>')
        ->and($contenu)->toContain('</p>');

    // Le contenu peut contenir des titres et des listes
    $hasHeadings = str_contains($contenu, '<h2>');
    $hasLists = str_contains($contenu, '<ul>') || str_contains($contenu, '<li>');

    // Au moins des paragraphes devraient être présents
    expect(mb_substr_count($contenu, '<p>'))->toBeGreaterThan(2);
});
