<?php

declare(strict_types=1);

use App\Enums\StatusFiche;
use App\Models\Fiche;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Test 1 : Vérifie que le modèle peut être créé avec les attributs requis
 */
test('Tag peut être créé avec les attributs de base', function (): void {
    $tag = Tag::factory()->create([
        'nom' => 'Test Tag',
        'slug' => 'test-tag',
        'description' => 'Une description de test',
        'ordre' => 1,
        'is_featured' => true,
        'is_active' => true,
    ]);

    expect($tag->nom)->toBe('Test Tag')
        ->and($tag->slug)->toBe('test-tag')
        ->and($tag->description)->toBe('Une description de test')
        ->and($tag->ordre)->toBe(1)
        ->and($tag->is_featured)->toBeTrue()
        ->and($tag->is_active)->toBeTrue()
        ->and($tag->exists)->toBeTrue();
});

/**
 * Test 2 : Vérifie que les attributs fillable sont correctement définis
 */
test('Tag a les bons attributs fillable', function (): void {
    $expectedFillable = [
        'nom',
        'slug',
        'description',
        'fiche_id',
        'ordre',
        'is_featured',
        'is_active',
    ];

    expect(Tag::make()->getFillable())->toBe($expectedFillable);
});

/**
 * Test 3 : Vérifie que les casts sont correctement définis
 */
test('Tag a les bons casts', function (): void {
    $fiche = Fiche::factory()->create();

    $tag = Tag::factory()->create([
        'fiche_id' => $fiche->id,
        'ordre' => '5',
        'is_featured' => '1',
        'is_active' => '0',
    ]);

    expect($tag->is_featured)->toBeBool()
        ->and($tag->is_featured)->toBeTrue()
        ->and($tag->is_active)->toBeBool()
        ->and($tag->is_active)->toBeFalse()
        ->and($tag->ordre)->toBeInt()
        ->and($tag->fiche_id)->toBeInt();
});

/**
 * Test 4 : Vérifie que la relation fiches fonctionne correctement
 */
test('Tag a une relation belongsToMany avec Fiche', function (): void {
    $tag = Tag::factory()->create();
    $fiches = Fiche::factory()->count(3)->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);

    $tag->fiches()->attach($fiches->pluck('id'));

    expect($tag->fiches())->toBeInstanceOf(BelongsToMany::class)
        ->and($tag->fiches)->toHaveCount(3)
        ->and($tag->fiches->first())->toBeInstanceOf(Fiche::class);
});

/**
 * Test 5 : Vérifie que la relation fiches utilise la bonne table pivot
 */
test('Tag relation fiches utilise la table pivot fiches_tags', function (): void {
    $tag = Tag::factory()->create();
    $relation = $tag->fiches();

    expect($relation->getTable())->toBe('fiches_tags');
});

/**
 * Test 6 : Vérifie que la relation fiches filtre les fiches publiées
 */
test('Tag relation fiches filtre les fiches non publiées', function (): void {
    $tag = Tag::factory()->create();
    $fichePubliee = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    $ficheBrouillon = Fiche::factory()->create([
        'status' => StatusFiche::BROUILLON,
        'published_at' => null,
    ]);

    $tag->fiches()->attach([$fichePubliee->id, $ficheBrouillon->id]);

    expect($tag->fiches)->toHaveCount(1)
        ->and($tag->fiches->first()->id)->toBe($fichePubliee->id);
});

/**
 * Test 7 : Vérifie que les relations fiches sont ordonnées par titre
 */
test('Tag relation fiches a le bon ordre', function (): void {
    $tag = Tag::factory()->create();

    $fiche1 = Fiche::factory()->create([
        'titre' => 'Z Fiche',
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    $fiche2 = Fiche::factory()->create([
        'titre' => 'A Fiche',
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    $fiche3 = Fiche::factory()->create([
        'titre' => 'B Fiche',
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);

    $tag->fiches()->attach([$fiche1->id, $fiche2->id, $fiche3->id]);

    $fiches = $tag->fiches;

    expect($fiches->get(0)->titre)->toBe('A Fiche')
        ->and($fiches->get(1)->titre)->toBe('B Fiche')
        ->and($fiches->get(2)->titre)->toBe('Z Fiche');
});

/**
 * Test 8 : Vérifie que la relation fiche fonctionne avec filtrage
 */
test('Tag relation fiche filtre les fiches non publiées', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $tagAvecFichePubliee = Tag::factory()->create(['fiche_id' => $fichePubliee->id]);
    $tagAvecFicheNonPubliee = Tag::factory()->create(['fiche_id' => $ficheNonPubliee->id]);

    expect($tagAvecFichePubliee->fiche())->toBeInstanceOf(BelongsTo::class)
        ->and($tagAvecFichePubliee->fiche)->toBeInstanceOf(Fiche::class)
        ->and($tagAvecFichePubliee->fiche->id)->toBe($fichePubliee->id)
        ->and($tagAvecFicheNonPubliee->fiche)->toBeNull();
});

/**
 * Test 9 : Vérifie que le scope active fonctionne correctement
 */
test('Tag scope active ne retourne que les tags actifs', function (): void {
    Tag::factory()->create(['is_active' => true]);
    Tag::factory()->create(['is_active' => false]);
    Tag::factory()->create(['is_active' => true]);

    $activeTags = Tag::active()->get();

    expect($activeTags)->toHaveCount(2)
        ->and($activeTags->first()->is_active)->toBeTrue()
        ->and($activeTags->last()->is_active)->toBeTrue();
});

/**
 * Test 10 : Vérifie que le scope featured fonctionne correctement
 */
test('Tag scope featured ne retourne que les tags mis en avant', function (): void {
    Tag::factory()->create(['is_featured' => true]);
    Tag::factory()->create(['is_featured' => false]);
    Tag::factory()->create(['is_featured' => true]);

    $featuredTags = Tag::featured()->get();

    expect($featuredTags)->toHaveCount(2)
        ->and($featuredTags->first()->is_featured)->toBeTrue()
        ->and($featuredTags->last()->is_featured)->toBeTrue();
});

/**
 * Test 11 : Vérifie que le scope ordered fonctionne correctement
 */
test('Tag scope ordered trie par ordre puis nom', function (): void {
    Tag::factory()->create(['nom' => 'Z Tag', 'ordre' => 1]);
    Tag::factory()->create(['nom' => 'A Tag', 'ordre' => 2]);
    Tag::factory()->create(['nom' => 'B Tag', 'ordre' => 1]);

    $orderedTags = Tag::ordered()->get();

    expect($orderedTags->get(0)->nom)->toBe('B Tag') // ordre 1, nom B
        ->and($orderedTags->get(1)->nom)->toBe('Z Tag') // ordre 1, nom Z
        ->and($orderedTags->get(2)->nom)->toBe('A Tag'); // ordre 2, nom A
});

/**
 * Test 12 : Vérifie que le scope withFichesCount fonctionne correctement
 */
test('Tag scope withFichesCount ajoute le compteur de fiches', function (): void {
    $tag = Tag::factory()->create();
    $fiches = Fiche::factory()->count(2)->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);

    $tag->fiches()->attach($fiches->pluck('id'));

    $tagWithCount = Tag::withFichesCount()->find($tag->id);

    expect($tagWithCount->fiches_count)->toBe(2);
});

/**
 * Test 13 : Vérifie que withFichesCount ne compte que les fiches publiées
 */
test('Tag scope withFichesCount ne compte que les fiches publiées', function (): void {
    $tag = Tag::factory()->create();
    $fichePubliee = Fiche::factory()->create([
        'published_at' => now(),
        'status' => StatusFiche::PUBLIE,
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'published_at' => null,
        'status' => StatusFiche::BROUILLON,
    ]);

    $tag->fiches()->attach([$fichePubliee->id, $ficheNonPubliee->id]);

    $tagWithCount = Tag::withFichesCount()->find($tag->id);

    // Note: Ce test peut échouer selon l'implémentation du scope withFichesCount
    // car il utilise une condition différente de la relation fiches()
    expect($tagWithCount->fiches_count)->toBe(1);
});

/**
 * Test 14 : Vérifie que hasPublishedFiches fonctionne correctement
 */
test('Tag hasPublishedFiches détecte les fiches publiées', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'status' => StatusFiche::BROUILLON,
        'published_at' => null,
    ]);

    $tagAvecFichePubliee = Tag::factory()->create();
    $tagAvecFicheNonPubliee = Tag::factory()->create();
    $tagSansFiche = Tag::factory()->create();

    $tagAvecFichePubliee->fiches()->attach($fichePubliee->id);
    $tagAvecFicheNonPubliee->fiches()->attach($ficheNonPubliee->id);

    expect($tagAvecFichePubliee->hasPublishedFiches())->toBeTrue()
        ->and($tagAvecFicheNonPubliee->hasPublishedFiches())->toBeFalse()
        ->and($tagSansFiche->hasPublishedFiches())->toBeFalse();
});

/**
 * Test 15 : Vérifie que getPublishedFichesCount fonctionne correctement
 */
test('Tag getPublishedFichesCount compte les fiches publiées', function (): void {
    $tag = Tag::factory()->create();
    $fichesPubliees = Fiche::factory()->count(3)->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    $ficheNonPubliee = Fiche::factory()->create([
        'status' => StatusFiche::BROUILLON,
        'published_at' => null,
    ]);

    $tag->fiches()->attach($fichesPubliees->pluck('id'));
    $tag->fiches()->attach($ficheNonPubliee->id);

    expect($tag->getPublishedFichesCount())->toBe(3);
});

/**
 * Test 16 : Vérifie que le modèle utilise SoftDeletes
 */
test('Tag utilise SoftDeletes', function (): void {
    $tag = Tag::factory()->create();
    $id = $tag->id;

    $tag->delete();

    expect(Tag::find($id))->toBeNull()
        ->and(Tag::withTrashed()->find($id))->not->toBeNull()
        ->and(Tag::withTrashed()->find($id)->deleted_at)->not->toBeNull();
});

/**
 * Test 17 : Vérifie que le modèle utilise les traits attendus
 */
test('Tag utilise les bons traits', function (): void {
    $tag = new Tag();

    expect(method_exists($tag, 'getOptimizedRelations'))->toBeTrue() // HasOptimizedRelations
        ->and(method_exists($tag, 'bootSoftDeletes'))->toBeTrue() // SoftDeletes
        ->and(method_exists($tag, 'newFactory'))->toBeTrue(); // HasFactory
});

/**
 * Test 18 : Vérifie que les scopes peuvent être combinés
 */
test('Tag scopes peuvent être combinés', function (): void {
    Tag::factory()->create([
        'is_active' => true,
        'is_featured' => true,
        'ordre' => 1,
        'nom' => 'Premier Tag',
    ]);

    Tag::factory()->create([
        'is_active' => false,
        'is_featured' => true,
        'ordre' => 2,
        'nom' => 'Deuxième Tag',
    ]);

    Tag::factory()->create([
        'is_active' => true,
        'is_featured' => false,
        'ordre' => 3,
        'nom' => 'Troisième Tag',
    ]);

    $result = Tag::active()
        ->featured()
        ->ordered()
        ->get();

    expect($result)->toHaveCount(1)
        ->and($result->first()->nom)->toBe('Premier Tag')
        ->and($result->first()->is_active)->toBeTrue()
        ->and($result->first()->is_featured)->toBeTrue();
});

/**
 * Test 19 : Vérifie la cohérence entre les méthodes et les relations
 */
test('Tag méthodes sont cohérentes avec les relations', function (): void {
    $tag = Tag::factory()->create();
    $fichePubliee = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);

    $tag->fiches()->attach($fichePubliee->id);

    // Les méthodes doivent être cohérentes avec la relation
    expect($tag->hasPublishedFiches())->toBeTrue()
        ->and($tag->getPublishedFichesCount())->toBe(1)
        ->and($tag->fiches)->toHaveCount(1);
});

/**
 * Test 20 : Vérifie que les états booléens fonctionnent correctement
 */
test('Tag états booléens fonctionnent correctement', function (): void {
    $tagActifFeatured = Tag::factory()->create([
        'is_active' => true,
        'is_featured' => true,
    ]);
    $tagInactifNonFeatured = Tag::factory()->create([
        'is_active' => false,
        'is_featured' => false,
    ]);
    $tagActifNonFeatured = Tag::factory()->create([
        'is_active' => true,
        'is_featured' => false,
    ]);

    expect(Tag::active()->get())->toHaveCount(2)
        ->and(Tag::featured()->get())->toHaveCount(1)
        ->and(Tag::active()->featured()->get())->toHaveCount(1)
        ->and(Tag::active()->featured()->first()->id)->toBe($tagActifFeatured->id);
});

/**
 * Test 21 : Vérifie que la relation fiches utilise withTimestamps
 */
test('Tag relation fiches utilise withTimestamps', function (): void {
    $tag = Tag::factory()->create();
    $fiche = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);

    $tag->fiches()->attach($fiche->id);

    $pivotData = $tag->fiches()->first()->pivot;

    expect($pivotData->created_at)->not->toBeNull()
        ->and($pivotData->updated_at)->not->toBeNull();
});
