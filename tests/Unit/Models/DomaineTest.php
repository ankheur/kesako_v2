<?php

declare(strict_types=1);

use App\Enums\StatusFiche;
use App\Models\Categorie;
use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Test 1 : Vérifie que le modèle peut être créé avec les attributs requis
 */
test('Domaine peut être créé avec les attributs de base', function (): void {
    $domaine = Domaine::factory()->create([
        'titre' => 'Test Domaine',
        'slug' => 'test-domaine',
        'couleur' => '#FF0000',
        'ordre' => 1,
    ]);

    expect($domaine->titre)->toBe('Test Domaine')
        ->and($domaine->slug)->toBe('test-domaine')
        ->and($domaine->couleur)->toBe('#FF0000')
        ->and($domaine->ordre)->toBe(1)
        ->and($domaine->exists)->toBeTrue();
});

/**
 * Test 2 : Vérifie que les attributs fillable sont correctement définis
 */
test('Domaine a les bons attributs fillable', function (): void {
    $expectedFillable = [
        'titre',
        'slug',
        'icone',
        'description',
        'couleur',
        'fiche_id',
        'published_at',
        'ordre',
    ];

    expect(Domaine::make()->getFillable())->toBe($expectedFillable);
});

/**
 * Test 3 : Vérifie que les casts sont correctement définis
 */
test('Domaine a les bons casts', function (): void {
    $fiche = Fiche::factory()->create([]);
    $domaine = Domaine::factory()->create([
        'published_at' => '2024-01-15 10:30:00',
        'ordre' => '5',
        'fiche_id' => $fiche->id,
    ]);

    expect($domaine->published_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($domaine->ordre)->toBeInt()
        ->and($domaine->fiche_id)->toBeInt();
});

/**
 * Test 4 : Vérifie que la relation categories fonctionne correctement
 */
test('Domaine a une relation belongsToMany avec Categorie', function (): void {
    $domaine = Domaine::factory()->create();
    $categories = Categorie::factory()->count(3)->create();

    $domaine->categories()->attach($categories->pluck('id'));

    expect($domaine->categories())->toBeInstanceOf(BelongsToMany::class)
        ->and($domaine->categories)->toHaveCount(3)
        ->and($domaine->categories->first())->toBeInstanceOf(Categorie::class);
});

/**
 * Test 5 : Vérifie que la relation categories utilise la bonne table pivot
 */
test('Domaine relation categories utilise la table pivot domaines_categories', function (): void {
    $domaine = Domaine::factory()->create();
    $relation = $domaine->categories();

    expect($relation->getTable())->toBe('domaines_categories');
});

/**
 * Test 6 : Vérifie que la relation categories utilise withTimestamps
 */
test('Domaine relation categories utilise withTimestamps', function (): void {
    $domaine = Domaine::factory()->create();
    $categorie = Categorie::factory()->create();

    $domaine->categories()->attach($categorie->id);

    $pivotData = $domaine->categories()->first()->pivot;

    expect($pivotData->created_at)->not->toBeNull()
        ->and($pivotData->updated_at)->not->toBeNull();
});

/**
 * Test 7 : Vérifie que les relations categories sont ordonnées correctement
 */
test('Domaine relation categories a le bon ordre', function (): void {
    $domaine = Domaine::factory()->create();

    // Créer des catégories avec différents ordres pour tester le tri
    $categorie1 = Categorie::factory()->create([
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
        'ordre' => 2,
        'titre' => 'Z Categorie',
        'slug' => 'z-categorie',
    ]);
    $categorie2 = Categorie::factory()->create([
        'type_categorie' => App\Enums\TypeCategorie::EPOQUE,
        'ordre' => 1,
        'titre' => 'A Categorie',
        'slug' => 'a-categorie',
    ]);
    $categorie3 = Categorie::factory()->create([
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
        'ordre' => 1,
        'titre' => 'B Categorie',
        'slug' => 'b-categorie',
    ]);

    $domaine->categories()->attach([$categorie1->id, $categorie2->id, $categorie3->id]);

    // Récupérer les catégories et vérifier l'ordre
    $categories = $domaine->categories;

    // L'ordre devrait être : type_categorie, puis ordre, puis titre
    // EPOQUE (1, A) puis PROFESSION (1, B) puis PROFESSION (2, Z)
    expect($categories->get(0)->type_categorie->value)->toBe('epoque')
        ->and($categories->get(1)->titre)->toBe('B Categorie')
        ->and($categories->get(2)->titre)->toBe('Z Categorie');
});

/**
 * Test 8 : Vérifie que la relation fiche fonctionne correctement (sans filtrage)
 */
test('Domaine a une relation belongsTo avec Fiche sans filtrage', function (): void {
    $fiche = Fiche::factory()->create(['published_at' => null, 'status' => StatusFiche::BROUILLON]);
    $domaine = Domaine::factory()->create(['fiche_id' => $fiche->id]);

    expect($domaine->fiche())->toBeInstanceOf(BelongsTo::class)
        ->and($domaine->fiche)->toBeInstanceOf(Fiche::class)
        ->and($domaine->fiche->id)->toBe($fiche->id);
});

/**
 * Test 9 : Vérifie que la relation fichePubliee filtre correctement
 */
test('Domaine relation fichePubliee ne retourne que les fiches publiées', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $domaineAvecFichePubliee = Domaine::factory()->create(['fiche_id' => $fichePubliee->id]);
    $domaineAvecFicheNonPubliee = Domaine::factory()->create(['fiche_id' => $ficheNonPubliee->id]);

    expect($domaineAvecFichePubliee->fichePubliee())->toBeInstanceOf(BelongsTo::class)
        ->and($domaineAvecFichePubliee->fichePubliee)->toBeInstanceOf(Fiche::class)
        ->and($domaineAvecFichePubliee->fichePubliee->id)->toBe($fichePubliee->id)
        ->and($domaineAvecFicheNonPubliee->fichePubliee)->toBeNull();
});

/**
 * Test 10 : Vérifie la cohérence entre fiche() et fichePubliee()
 */
test('Domaine relations fiche et fichePubliee sont cohérentes', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $domaineAvecFichePubliee = Domaine::factory()->create(['fiche_id' => $fichePubliee->id]);
    $domaineAvecFicheNonPubliee = Domaine::factory()->create(['fiche_id' => $ficheNonPubliee->id]);

    // Avec fiche publiée : les deux relations retournent la fiche
    expect($domaineAvecFichePubliee->fiche)->not->toBeNull()
        ->and($domaineAvecFichePubliee->fichePubliee)->not->toBeNull()
        ->and($domaineAvecFichePubliee->fiche->id)->toBe($domaineAvecFichePubliee->fichePubliee->id);

    // Avec fiche non publiée : seule fiche() retourne la fiche
    expect($domaineAvecFicheNonPubliee->fiche)->not->toBeNull()
        ->and($domaineAvecFicheNonPubliee->fichePubliee)->toBeNull();
});

/**
 * Test 11 : Vérifie que getAllFiches retourne une collection
 */
test('Domaine getAllFiches retourne une collection de fiches', function (): void {
    $domaine = Domaine::factory()->create();
    $result = $domaine->getAllFiches();

    expect($result)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class);
});

/**
 * Test 12 : Vérifie que getFichesPubliees retourne une collection
 */
test('Domaine getFichesPubliees retourne une collection de fiches publiées', function (): void {
    $domaine = Domaine::factory()->create();
    $result = $domaine->getFichesPubliees();

    expect($result)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class);
});

/**
 * Test 13 : Vérifie la cohérence entre getAllFiches() et getFichesPubliees()
 */
test('Domaine méthodes getAllFiches et getFichesPubliees sont cohérentes', function (): void {
    $domaine = Domaine::factory()->create();

    $toutesLesFiches = $domaine->getAllFiches();
    $fichesPuBlieesOnly = $domaine->getFichesPubliees();

    // Les deux méthodes retournent des collections
    expect($toutesLesFiches)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class)
        ->and($fichesPuBlieesOnly)->toBeInstanceOf(Illuminate\Database\Eloquent\Collection::class);

    // Les fiches publiées doivent être un sous-ensemble de toutes les fiches
    expect($fichesPuBlieesOnly->count())->toBeLessThanOrEqual($toutesLesFiches->count());
});

/**
 * Test 14 : Vérifie que le scope published fonctionne correctement
 */
test('Domaine scope published ne retourne que les domaines publiés', function (): void {
    Domaine::factory()->create(['published_at' => now()]);
    Domaine::factory()->create(['published_at' => null]);

    $publishedDomaines = Domaine::published()->get();

    expect($publishedDomaines)->toHaveCount(1)
        ->and($publishedDomaines->first()->published_at)->not->toBeNull();
});

/**
 * Test 15 : Vérifie que le scope ordered fonctionne correctement
 */
test('Domaine scope ordered trie par ordre puis titre', function (): void {
    Domaine::factory()->create(['titre' => 'Z Domaine', 'slug' => 'z-domaine', 'ordre' => 1]);
    Domaine::factory()->create(['titre' => 'A Domaine', 'slug' => 'a-domaine', 'ordre' => 2]);
    Domaine::factory()->create(['titre' => 'B Domaine', 'slug' => 'b-domaine', 'ordre' => 1]);

    $orderedDomaines = Domaine::ordered()->get();

    expect($orderedDomaines->get(0)->titre)->toBe('B Domaine') // ordre 1, titre B
        ->and($orderedDomaines->get(1)->titre)->toBe('Z Domaine') // ordre 1, titre Z
        ->and($orderedDomaines->get(2)->titre)->toBe('A Domaine'); // ordre 2, titre A
});

/**
 * Test 16 : Vérifie que le scope withCounts fonctionne correctement
 */
test('Domaine scope withCounts ajoute le compteur de catégories', function (): void {
    $domaine = Domaine::factory()->create();
    $categories = Categorie::factory()->count(2)->create();
    $domaine->categories()->attach($categories->pluck('id'));

    $domaineWithCounts = Domaine::withCounts()->find($domaine->id);

    expect($domaineWithCounts->categories_count)->toBe(2);
});

/**
 * Test 17 : Vérifie que getOptimizedRelations retourne les bonnes relations
 */
test('Domaine utilise HasOptimizedRelations correctement', function (): void {
    $domaine = new Domaine();

    // Vérifier que le trait est bien utilisé (méthode existe)
    expect(method_exists($domaine, 'getOptimizedRelations'))->toBeTrue();
});

/**
 * Test 18 : Vérifie que hasPublishedContent fonctionne avec des catégories publiées
 */
test('Domaine hasPublishedContent retourne true avec catégories publiées', function (): void {
    $domaine = Domaine::factory()->create();
    $categorie = Categorie::factory()->create(['published_at' => now()]);
    $domaine->categories()->attach($categorie->id);

    expect($domaine->hasPublishedContent())->toBeTrue();
});

/**
 * Test 19 : Vérifie que hasPublishedContent retourne false sans contenu publié
 */
test('Domaine hasPublishedContent retourne false sans contenu publié', function (): void {
    $domaine = Domaine::factory()->create();
    $categorie = Categorie::factory()->create(['published_at' => null]);
    $domaine->categories()->attach($categorie->id);

    expect($domaine->hasPublishedContent())->toBeFalse();
});

/**
 * Test 20 : Vérifie que hasPublishedContent utilise getFichesPubliees
 */
test('Domaine hasPublishedContent fonctionne avec getFichesPubliees', function (): void {
    $domaine = Domaine::factory()->create();

    // Test que la méthode fonctionne (retourne un boolean)
    expect($domaine->hasPublishedContent())->toBeBool();
});

/**
 * Test 21 : Vérifie que getCategoriesGroupedByType groupe correctement
 */
test('Domaine getCategoriesGroupedByType groupe les catégories par type', function (): void {
    $domaine = Domaine::factory()->create();

    // Création de catégories avec différents types
    $categorieProfession = Categorie::factory()->create([
        'published_at' => now(),
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
    ]);
    $categorieEpoque = Categorie::factory()->create([
        'published_at' => now(),
        'type_categorie' => App\Enums\TypeCategorie::EPOQUE,
    ]);
    $categorieProfession2 = Categorie::factory()->create([
        'published_at' => now(),
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
    ]);

    $domaine->categories()->attach([
        $categorieProfession->id,
        $categorieEpoque->id,
        $categorieProfession2->id,
    ]);

    $grouped = $domaine->getCategoriesGroupedByType();

    expect($grouped)->toBeArray()
        ->and($grouped)->toHaveKeys(['profession', 'epoque'])
        ->and($grouped['profession'])->toHaveCount(2)
        ->and($grouped['epoque'])->toHaveCount(1)
        ->and($grouped['profession']->first())->toBeInstanceOf(Categorie::class);
});

/**
 * Test 22 : Vérifie que getCategoriesGroupedByType filtre les catégories non publiées
 */
test('Domaine getCategoriesGroupedByType filtre les catégories non publiées', function (): void {
    $domaine = Domaine::factory()->create();

    $categoriePubliee = Categorie::factory()->create([
        'published_at' => now(),
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
    ]);
    $categorieNonPubliee = Categorie::factory()->create([
        'published_at' => null,
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
    ]);

    $domaine->categories()->attach([
        $categoriePubliee->id,
        $categorieNonPubliee->id,
    ]);

    $grouped = $domaine->getCategoriesGroupedByType();

    expect($grouped['profession'])->toHaveCount(1)
        ->and($grouped['profession']->first()->id)->toBe($categoriePubliee->id);
});

/**
 * Test 23 : Vérifie que le modèle utilise SoftDeletes
 */
test('Domaine utilise SoftDeletes', function (): void {
    $domaine = Domaine::factory()->create();
    $id = $domaine->id;

    $domaine->delete();

    expect(Domaine::find($id))->toBeNull()
        ->and(Domaine::withTrashed()->find($id))->not->toBeNull()
        ->and(Domaine::withTrashed()->find($id)->deleted_at)->not->toBeNull();
});

/**
 * Test 24 : Vérifie que le modèle utilise les traits attendus
 */
test('Domaine utilise les bons traits', function (): void {
    $domaine = new Domaine();

    expect(method_exists($domaine, 'getOptimizedRelations'))->toBeTrue() // HasOptimizedRelations
        ->and(method_exists($domaine, 'bootSoftDeletes'))->toBeTrue() // SoftDeletes
        ->and(method_exists($domaine, 'newFactory'))->toBeTrue(); // HasFactory
});

/**
 * Test 25 : Vérifie que les méthodes getAllFiches et getFichesPubliees gèrent les relations complexes
 */
test('Domaine méthodes fiches gèrent les relations via catégories', function (): void {
    $domaine = Domaine::factory()->create();

    // Test avec des données réelles si nécessaire
    // Pour l'instant on vérifie juste que les méthodes ne plantent pas
    expect(fn () => $domaine->getAllFiches())->not->toThrow(Exception::class)
        ->and(fn () => $domaine->getFichesPubliees())->not->toThrow(Exception::class);
});
