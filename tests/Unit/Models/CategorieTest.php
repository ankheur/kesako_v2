<?php

declare(strict_types=1);

use App\Enums\StatusFiche;
use App\Enums\TypeCategorie;
use App\Models\Categorie;
use App\Models\Domaine;
use App\Models\Fiche;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Test 1 : Vérifie que le modèle peut être créé avec les attributs requis
 */
test('Categorie peut être créée avec les attributs de base', function (): void {
    $categorie = Categorie::factory()->create([
        'titre' => 'Test Catégorie',
        'slug' => 'test-categorie',
        'type_categorie' => TypeCategorie::PROFESSION,
        'ordre' => 1,
    ]);

    expect($categorie->titre)->toBe('Test Catégorie')
        ->and($categorie->slug)->toBe('test-categorie')
        ->and($categorie->type_categorie)->toBe(TypeCategorie::PROFESSION)
        ->and($categorie->ordre)->toBe(1)
        ->and($categorie->exists)->toBeTrue();
});

/**
 * Test 2 : Vérifie que les attributs fillable sont correctement définis
 */
test('Categorie a les bons attributs fillable', function (): void {
    $expectedFillable = [
        'titre',
        'slug',
        'type_categorie',
        'description',
        'fiche_id',
        'ordre',
        'published_at',
    ];

    expect(Categorie::make()->getFillable())->toBe($expectedFillable);
});

/**
 * Test 3 : Vérifie que les casts sont correctement définis
 */
test('Categorie a les bons casts', function (): void {
    $fiche = Fiche::factory()->create([]);
    $categorie = Categorie::factory()->create([
        'type_categorie' => TypeCategorie::PROFESSION,
        'published_at' => '2024-01-15 10:30:00',
        'ordre' => '5',
        'fiche_id' => $fiche->id,
    ]);

    expect($categorie->type_categorie)->toBeInstanceOf(TypeCategorie::class)
        ->and($categorie->type_categorie)->toBe(TypeCategorie::PROFESSION)
        ->and($categorie->published_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($categorie->ordre)->toBeInt()
        ->and($categorie->fiche_id)->toBeInt();
});

/**
 * Test 4 : Vérifie que la relation domaines fonctionne correctement
 */
test('Categorie a une relation belongsToMany avec Domaine', function (): void {
    $categorie = Categorie::factory()->create();
    $domaines = Domaine::factory()->count(3)->create();

    $categorie->domaines()->attach($domaines->pluck('id'));

    expect($categorie->domaines())->toBeInstanceOf(BelongsToMany::class)
        ->and($categorie->domaines)->toHaveCount(3)
        ->and($categorie->domaines->first())->toBeInstanceOf(Domaine::class);
});

/**
 * Test 5 : Vérifie que la relation domaines utilise la bonne table pivot
 */
test('Categorie relation domaines utilise la table pivot domaines_categories', function (): void {
    $categorie = Categorie::factory()->create();
    $relation = $categorie->domaines();

    expect($relation->getTable())->toBe('domaines_categories');
});

/**
 * Test 6 : Vérifie que la relation domaines utilise withTimestamps
 */
test('Categorie relation domaines utilise withTimestamps', function (): void {
    $categorie = Categorie::factory()->create();
    $domaine = Domaine::factory()->create();

    $categorie->domaines()->attach($domaine->id);

    $pivotData = $categorie->domaines()->first()->pivot;

    expect($pivotData->created_at)->not->toBeNull()
        ->and($pivotData->updated_at)->not->toBeNull();
});

/**
 * Test 7 : Vérifie que les relations domaines sont ordonnées correctement
 */
test('Categorie relation domaines a le bon ordre', function (): void {
    $categorie = Categorie::factory()->create();

    $domaine1 = Domaine::factory()->create(['ordre' => 2, 'titre' => 'Z Domaine', 'slug' => 'z-categorie']);
    $domaine2 = Domaine::factory()->create(['ordre' => 1, 'titre' => 'A Domaine', 'slug' => 'a-categorie']);
    $domaine3 = Domaine::factory()->create(['ordre' => 1, 'titre' => 'B Domaine', 'slug' => 'b-categorie']);

    $categorie->domaines()->attach([$domaine1->id, $domaine2->id, $domaine3->id]);

    $domaines = $categorie->domaines;

    // L'ordre devrait être : ordre puis titre
    // (1, A) puis (1, B) puis (2, Z)
    expect($domaines->get(0)->titre)->toBe('A Domaine')
        ->and($domaines->get(1)->titre)->toBe('B Domaine')
        ->and($domaines->get(2)->titre)->toBe('Z Domaine');
});

/**
 * Test 8 : Vérifie que la relation fiches fonctionne correctement
 */
test('Categorie a une relation belongsToMany avec Fiche', function (): void {
    $categorie = Categorie::factory()->create();
    $fiches = Fiche::factory()->count(3)->create();

    $categorie->fiches()->attach($fiches->pluck('id'));

    expect($categorie->fiches())->toBeInstanceOf(BelongsToMany::class)
        ->and($categorie->fiches)->toHaveCount(3)
        ->and($categorie->fiches->first())->toBeInstanceOf(Fiche::class);
});

/**
 * Test 9 : Vérifie que la relation fiches utilise la bonne table pivot
 */
test('Categorie relation fiches utilise la table pivot categories_fiches', function (): void {
    $categorie = Categorie::factory()->create();
    $relation = $categorie->fiches();

    expect($relation->getTable())->toBe('categories_fiches');
});

/**
 * Test 10 : Vérifie que les relations fiches sont ordonnées par titre
 */
test('Categorie relation fiches a le bon ordre', function (): void {
    $categorie = Categorie::factory()->create();

    $fiche1 = Fiche::factory()->create(['titre' => 'Z Fiche']);
    $fiche2 = Fiche::factory()->create(['titre' => 'A Fiche']);
    $fiche3 = Fiche::factory()->create(['titre' => 'B Fiche']);

    $categorie->fiches()->attach([$fiche1->id, $fiche2->id, $fiche3->id]);

    $fiches = $categorie->fiches;

    expect($fiches->get(0)->titre)->toBe('A Fiche')
        ->and($fiches->get(1)->titre)->toBe('B Fiche')
        ->and($fiches->get(2)->titre)->toBe('Z Fiche');
});

/**
 * Test 11 : Vérifie que la relation fiche fonctionne sans filtrage
 */
test('Categorie relation fiche fonctionne sans filtrage', function (): void {
    $fiche = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $categorie = Categorie::factory()->create(['fiche_id' => $fiche->id]);

    expect($categorie->fiche())->toBeInstanceOf(BelongsTo::class)
        ->and($categorie->fiche)->toBeInstanceOf(Fiche::class)
        ->and($categorie->fiche->id)->toBe($fiche->id);
});

/**
 * Test 11b : Vérifie que la relation fichePubliee filtre correctement
 */
test('Categorie relation fichePubliee filtre les fiches non publiées', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $categorieAvecFichePubliee = Categorie::factory()->create(['fiche_id' => $fichePubliee->id]);
    $categorieAvecFicheNonPubliee = Categorie::factory()->create(['fiche_id' => $ficheNonPubliee->id]);

    expect($categorieAvecFichePubliee->fichePubliee())->toBeInstanceOf(BelongsTo::class)
        ->and($categorieAvecFichePubliee->fichePubliee)->toBeInstanceOf(Fiche::class)
        ->and($categorieAvecFichePubliee->fichePubliee->id)->toBe($fichePubliee->id)
        ->and($categorieAvecFicheNonPubliee->fichePubliee)->toBeNull();
});

/**
 * Test 12 : Vérifie que le scope published fonctionne correctement
 */
test('Categorie scope published ne retourne que les catégories publiées', function (): void {
    Categorie::factory()->create(['published_at' => now()]);
    Categorie::factory()->create(['published_at' => null]);

    $publishedCategories = Categorie::published()->get();

    expect($publishedCategories)->toHaveCount(1)
        ->and($publishedCategories->first()->published_at)->not->toBeNull();
});

/**
 * Test 13 : Vérifie que le scope ofType fonctionne correctement
 */
test('Categorie scope ofType filtre par type', function (): void {
    Categorie::factory()->create(['type_categorie' => TypeCategorie::PROFESSION]);
    Categorie::factory()->create(['type_categorie' => TypeCategorie::EPOQUE]);
    Categorie::factory()->create(['type_categorie' => TypeCategorie::PROFESSION]);

    $professionCategories = Categorie::ofType(TypeCategorie::PROFESSION)->get();
    $epoqueCategories = Categorie::ofType(TypeCategorie::EPOQUE)->get();

    expect($professionCategories)->toHaveCount(2)
        ->and($epoqueCategories)->toHaveCount(1)
        ->and($professionCategories->first()->type_categorie)->toBe(TypeCategorie::PROFESSION)
        ->and($epoqueCategories->first()->type_categorie)->toBe(TypeCategorie::EPOQUE);
});

/**
 * Test 14 : Vérifie que le scope forDomaine fonctionne correctement
 */
test('Categorie scope forDomaine filtre par domaine', function (): void {
    $domaine1 = Domaine::factory()->create();
    $domaine2 = Domaine::factory()->create();

    $categorie1 = Categorie::factory()->create();
    $categorie2 = Categorie::factory()->create();
    $categorie3 = Categorie::factory()->create();

    $categorie1->domaines()->attach($domaine1->id);
    $categorie2->domaines()->attach($domaine2->id);
    $categorie3->domaines()->attach([$domaine1->id, $domaine2->id]);

    $categoriesDomaine1 = Categorie::forDomaine($domaine1)->get();
    $categoriesDomaine2 = Categorie::forDomaine($domaine2)->get();

    expect($categoriesDomaine1)->toHaveCount(2) // categorie1 et categorie3
        ->and($categoriesDomaine2)->toHaveCount(2) // categorie2 et categorie3
        ->and($categoriesDomaine1->pluck('id')->toArray())->toContain($categorie1->id)
        ->and($categoriesDomaine1->pluck('id')->toArray())->toContain($categorie3->id)
        ->and($categoriesDomaine2->pluck('id')->toArray())->toContain($categorie2->id)
        ->and($categoriesDomaine2->pluck('id')->toArray())->toContain($categorie3->id);
});

/**
 * Test 15 : Vérifie que le scope ordered fonctionne correctement
 */
test('Categorie scope ordered trie par ordre puis titre', function (): void {
    Categorie::factory()->create(['titre' => 'Z Catégorie', 'slug' => 'z-categorie', 'ordre' => 1]);
    Categorie::factory()->create(['titre' => 'A Catégorie', 'slug' => 'a-categorie', 'ordre' => 2]);
    Categorie::factory()->create(['titre' => 'B Catégorie', 'slug' => 'b-categorie', 'ordre' => 1]);

    $orderedCategories = Categorie::ordered()->get();

    expect($orderedCategories->get(0)->titre)->toBe('B Catégorie') // ordre 1, titre B
        ->and($orderedCategories->get(1)->titre)->toBe('Z Catégorie') // ordre 1, titre Z
        ->and($orderedCategories->get(2)->titre)->toBe('A Catégorie'); // ordre 2, titre A
});

/**
 * Test 16 : Vérifie que getTypeCouleur retourne la couleur du type
 */
test('Categorie getTypeCouleur retourne la couleur du type de catégorie', function (): void {
    $categorieProfession = Categorie::factory()->create(['type_categorie' => TypeCategorie::PROFESSION]);
    $categorieEpoque = Categorie::factory()->create(['type_categorie' => TypeCategorie::EPOQUE]);

    expect($categorieProfession->getTypeCouleur())->toBe(TypeCategorie::PROFESSION->couleur())
        ->and($categorieEpoque->getTypeCouleur())->toBe(TypeCategorie::EPOQUE->couleur())
        ->and($categorieProfession->getTypeCouleur())->toMatch('/^#[0-9A-F]{6}$/');
});

/**
 * Test 17 : Vérifie que getTypeIcone retourne l'icône du type
 */
test('Categorie getTypeIcone retourne l\'icône du type de catégorie', function (): void {
    $categorieProfession = Categorie::factory()->create(['type_categorie' => TypeCategorie::PROFESSION]);
    $categorieEpoque = Categorie::factory()->create(['type_categorie' => TypeCategorie::EPOQUE]);

    expect($categorieProfession->getTypeIcone())->toBe(TypeCategorie::PROFESSION->icone())
        ->and($categorieEpoque->getTypeIcone())->toBe(TypeCategorie::EPOQUE->icone())
        ->and($categorieProfession->getTypeIcone())->toBeString()
        ->and($categorieEpoque->getTypeIcone())->toBeString();
});

/**
 * Test 18 : Vérifie que hasPublishedFiches utilise le scope published
 */
test('Categorie hasPublishedFiches utilise le scope published de Fiche', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $categorieAvecFichePubliee = Categorie::factory()->create();
    $categorieAvecFicheNonPubliee = Categorie::factory()->create();
    $categorieSansFiche = Categorie::factory()->create();

    $categorieAvecFichePubliee->fiches()->attach($fichePubliee->id);
    $categorieAvecFicheNonPubliee->fiches()->attach($ficheNonPubliee->id);

    expect($categorieAvecFichePubliee->hasPublishedFiches())->toBeTrue()
        ->and($categorieAvecFicheNonPubliee->hasPublishedFiches())->toBeFalse()
        ->and($categorieSansFiche->hasPublishedFiches())->toBeFalse();
});

/**
 * Test 20 : Vérifie que le modèle utilise SoftDeletes
 */
test('Categorie utilise SoftDeletes', function (): void {
    $categorie = Categorie::factory()->create();
    $id = $categorie->id;

    $categorie->delete();

    expect(Categorie::find($id))->toBeNull()
        ->and(Categorie::withTrashed()->find($id))->not->toBeNull()
        ->and(Categorie::withTrashed()->find($id)->deleted_at)->not->toBeNull();
});

/**
 * Test 20 : Vérifie que le modèle utilise les traits attendus
 */
test('Categorie utilise les bons traits', function (): void {
    $categorie = new Categorie();

    expect(method_exists($categorie, 'getOptimizedRelations'))->toBeTrue() // HasOptimizedRelations
        ->and(method_exists($categorie, 'bootSoftDeletes'))->toBeTrue() // SoftDeletes
        ->and(method_exists($categorie, 'newFactory'))->toBeTrue(); // HasFactory
});

/**
 * Test 21 : Vérifie la cohérence des types enum
 */
test('Categorie type_categorie enum fonctionne correctement', function (): void {
    foreach (TypeCategorie::cases() as $type) {
        $categorie = Categorie::factory()->create(['slug' => random_bytes(8), 'type_categorie' => $type]);

        expect($categorie->type_categorie)->toBe($type)
            ->and($categorie->getTypeCouleur())->toBe($type->couleur())
            ->and($categorie->getTypeIcone())->toBe($type->icone());
    }
});

/**
 * Test 22 : Vérifie que les scopes peuvent être combinés
 */
test('Categorie scopes peuvent être combinés', function (): void {
    $domaine = Domaine::factory()->create();

    Categorie::factory()->create([
        'type_categorie' => TypeCategorie::PROFESSION,
        'published_at' => now(),
    ])->domaines()->attach($domaine->id);

    Categorie::factory()->create([
        'type_categorie' => TypeCategorie::EPOQUE,
        'published_at' => now(),
    ])->domaines()->attach($domaine->id);

    Categorie::factory()->create([
        'type_categorie' => TypeCategorie::PROFESSION,
        'published_at' => null,
    ])->domaines()->attach($domaine->id);

    $result = Categorie::published()
        ->ofType(TypeCategorie::PROFESSION)
        ->forDomaine($domaine)
        ->ordered()
        ->get();

    expect($result)->toHaveCount(1)
        ->and($result->first()->type_categorie)->toBe(TypeCategorie::PROFESSION)
        ->and($result->first()->published_at)->not->toBeNull();
});
