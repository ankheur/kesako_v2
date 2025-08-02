<?php

declare(strict_types=1);

use App\Enums\StatusFiche;
use App\Enums\TypeFiche;
use App\Models\Categorie;
use App\Models\Element;
use App\Models\Fiche;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Test 1 : Vérifie que le modèle peut être créé avec les attributs requis
 */
test('Fiche peut être créée avec les attributs de base', function (): void {
    $fiche = Fiche::factory()->create([
        'titre' => 'Test Fiche',
        'slug' => 'test-fiche',
        'description' => 'Une description de test',
        'contenu' => 'Du contenu de test',
        'type_fiche' => TypeFiche::BIOGRAPHIE,
        'status' => StatusFiche::BROUILLON,
    ]);

    expect($fiche->titre)->toBe('Test Fiche')
        ->and($fiche->slug)->toBe('test-fiche')
        ->and($fiche->type_fiche)->toBe(TypeFiche::BIOGRAPHIE)
        ->and($fiche->status)->toBe(StatusFiche::BROUILLON)
        ->and($fiche->exists)->toBeTrue();
});

/**
 * Test 2 : Vérifie que les attributs fillable sont correctement définis
 */
test('Fiche a les bons attributs fillable', function (): void {
    $expectedFillable = [
        'titre',
        'soustitre',
        'slug',
        'illustration',
        'illustration_alt',
        'description',
        'contenu',
        'type_fiche',
        'status',
        'published_at',
        'featured_at',
    ];

    expect(Fiche::make()->getFillable())->toBe($expectedFillable);
});

/**
 * Test 3 : Vérifie que les casts sont correctement définis
 */
test('Fiche a les bons casts', function (): void {
    $fiche = Fiche::factory()->create([
        'type_fiche' => TypeFiche::BIOGRAPHIE,
        'status' => StatusFiche::PUBLIE,
        'published_at' => '2024-01-15 10:30:00',
        'featured_at' => '2024-01-16 15:45:00',
    ]);

    expect($fiche->type_fiche)->toBeInstanceOf(TypeFiche::class)
        ->and($fiche->type_fiche)->toBe(TypeFiche::BIOGRAPHIE)
        ->and($fiche->status)->toBeInstanceOf(StatusFiche::class)
        ->and($fiche->status)->toBe(StatusFiche::PUBLIE)
        ->and($fiche->published_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($fiche->featured_at)->toBeInstanceOf(Carbon\Carbon::class);
});

/**
 * Test 4 : Vérifie que la relation categories fonctionne correctement
 */
test('Fiche a une relation belongsToMany avec Categorie', function (): void {
    $fiche = Fiche::factory()->create();
    $categories = Categorie::factory()->count(3)->create();

    $fiche->categories()->attach($categories->pluck('id'));

    expect($fiche->categories())->toBeInstanceOf(BelongsToMany::class)
        ->and($fiche->categories)->toHaveCount(3)
        ->and($fiche->categories->first())->toBeInstanceOf(Categorie::class);
});

/**
 * Test 5 : Vérifie que la relation categories utilise la bonne table pivot
 */
test('Fiche relation categories utilise la table pivot categories_fiches', function (): void {
    $fiche = Fiche::factory()->create();
    $relation = $fiche->categories();

    expect($relation->getTable())->toBe('categories_fiches');
});

/**
 * Test 6 : Vérifie que les relations categories sont ordonnées correctement
 */
test('Fiche relation categories a le bon ordre', function (): void {
    $fiche = Fiche::factory()->create();

    $categorie1 = Categorie::factory()->create([
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
        'titre' => 'Z Catégorie',
    ]);
    $categorie2 = Categorie::factory()->create([
        'type_categorie' => App\Enums\TypeCategorie::EPOQUE,
        'titre' => 'A Catégorie',
    ]);
    $categorie3 = Categorie::factory()->create([
        'type_categorie' => App\Enums\TypeCategorie::PROFESSION,
        'titre' => 'B Catégorie',
    ]);

    $fiche->categories()->attach([$categorie1->id, $categorie2->id, $categorie3->id]);

    $categories = $fiche->categories;

    // L'ordre devrait être : type_categorie puis titre
    // EPOQUE (A) puis PROFESSION (B) puis PROFESSION (Z)
    expect($categories->get(0)->type_categorie->value)->toBe('epoque')
        ->and($categories->get(1)->titre)->toBe('B Catégorie')
        ->and($categories->get(2)->titre)->toBe('Z Catégorie');
});

/**
 * Test 7 : Vérifie que la relation tags fonctionne correctement
 */
test('Fiche a une relation belongsToMany avec Tag', function (): void {
    $fiche = Fiche::factory()->create();
    $tags = Tag::factory()->count(3)->create(['is_active' => true]);

    $fiche->tags()->attach($tags->pluck('id'));

    expect($fiche->tags())->toBeInstanceOf(BelongsToMany::class)
        ->and($fiche->tags)->toHaveCount(3)
        ->and($fiche->tags->first())->toBeInstanceOf(Tag::class);
});

/**
 * Test 8 : Vérifie que la relation tags filtre les tags actifs
 */
test('Fiche relation tags filtre les tags inactifs', function (): void {
    $fiche = Fiche::factory()->create();
    $tagActif = Tag::factory()->create(['is_active' => true]);
    $tagInactif = Tag::factory()->create(['is_active' => false]);

    $fiche->tags()->attach([$tagActif->id, $tagInactif->id]);

    expect($fiche->tags)->toHaveCount(1)
        ->and($fiche->tags->first()->id)->toBe($tagActif->id);
});

/**
 * Test 9 : Vérifie que la relation fichesConnexes fonctionne correctement
 */
test('Fiche a une relation fichesConnexes avec elle-même', function (): void {
    $fiche1 = Fiche::factory()->create();
    $fiche2 = Fiche::factory()->create(['published_at' => now(), 'status' => StatusFiche::PUBLIE]);

    $fiche1->fichesConnexes()->attach($fiche2->id);

    expect($fiche1->fichesConnexes())->toBeInstanceOf(BelongsToMany::class)
        ->and($fiche1->fichesConnexes)->toHaveCount(1)
        ->and($fiche1->fichesConnexes->first())->toBeInstanceOf(Fiche::class)
        ->and($fiche1->fichesConnexes->first()->id)->toBe($fiche2->id);
});

/**
 * Test 10 : Vérifie que la relation fichesConnexes filtre les fiches non publiées
 */
test('Fiche relation fichesConnexes filtre les fiches non publiées', function (): void {
    $fiche1 = Fiche::factory()->create();
    $fichePubliee = Fiche::factory()->create(['published_at' => now(), 'status' => StatusFiche::PUBLIE]);
    $ficheNonPubliee = Fiche::factory()->create(['published_at' => null]);

    $fiche1->fichesConnexes()->attach([$fichePubliee->id, $ficheNonPubliee->id]);

    expect($fiche1->fichesConnexes)->toHaveCount(1)
        ->and($fiche1->fichesConnexes->first()->id)->toBe($fichePubliee->id);
});

/**
 * Test 11 : Vérifie que la relation elements fonctionne correctement
 */
test('Fiche a une relation hasMany avec Element', function (): void {
    $fiche = Fiche::factory()->create();
    $elements = Element::factory()->count(3)->create(['fiche_id' => $fiche->id]);

    expect($fiche->elements())->toBeInstanceOf(HasMany::class)
        ->and($fiche->elements)->toHaveCount(3)
        ->and($fiche->elements->first())->toBeInstanceOf(Element::class);
});

/**
 * Test 12 : Vérifie que le scope published fonctionne correctement
 */
test('Fiche scope published ne retourne que les fiches publiées', function (): void {
    Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    Fiche::factory()->create([
        'status' => StatusFiche::BROUILLON,
        'published_at' => null,
    ]);
    Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => null,
    ]);

    $publishedFiches = Fiche::published()->get();

    expect($publishedFiches)->toHaveCount(1)
        ->and($publishedFiches->first()->status)->toBe(StatusFiche::PUBLIE)
        ->and($publishedFiches->first()->published_at)->not->toBeNull();
});

/**
 * Test 13 : Vérifie que le scope featured fonctionne correctement
 */
test('Fiche scope featured ne retourne que les fiches mises en avant', function (): void {
    Fiche::factory()->create(['featured_at' => now()]);
    Fiche::factory()->create(['featured_at' => null]);

    $featuredFiches = Fiche::featured()->get();

    expect($featuredFiches)->toHaveCount(1)
        ->and($featuredFiches->first()->featured_at)->not->toBeNull();
});

/**
 * Test 14 : Vérifie que le scope ofType fonctionne correctement
 */
test('Fiche scope ofType filtre par type', function (): void {
    Fiche::factory()->create(['type_fiche' => TypeFiche::BIOGRAPHIE]);
    Fiche::factory()->create(['type_fiche' => TypeFiche::EVENEMENT]);
    Fiche::factory()->create(['type_fiche' => TypeFiche::BIOGRAPHIE]);

    $biographieFiches = Fiche::ofType(TypeFiche::BIOGRAPHIE)->get();
    $evenementFiches = Fiche::ofType(TypeFiche::EVENEMENT)->get();

    expect($biographieFiches)->toHaveCount(2)
        ->and($evenementFiches)->toHaveCount(1)
        ->and($biographieFiches->first()->type_fiche)->toBe(TypeFiche::BIOGRAPHIE)
        ->and($evenementFiches->first()->type_fiche)->toBe(TypeFiche::EVENEMENT);
});

/**
 * Test 15 : Vérifie que le scope ofStatus fonctionne correctement
 */
test('Fiche scope ofStatus filtre par statut', function (): void {
    Fiche::factory()->create(['status' => StatusFiche::PUBLIE]);
    Fiche::factory()->create(['status' => StatusFiche::BROUILLON]);
    Fiche::factory()->create(['status' => StatusFiche::EN_REVIEW]);

    $publieeFiches = Fiche::ofStatus(StatusFiche::PUBLIE)->get();
    $brouillonFiches = Fiche::ofStatus(StatusFiche::BROUILLON)->get();

    expect($publieeFiches)->toHaveCount(1)
        ->and($brouillonFiches)->toHaveCount(1)
        ->and($publieeFiches->first()->status)->toBe(StatusFiche::PUBLIE)
        ->and($brouillonFiches->first()->status)->toBe(StatusFiche::BROUILLON);
});

/**
 * Test 16 : Vérifie que le scope withCategorie fonctionne correctement
 */
test('Fiche scope withCategorie filtre par catégorie', function (): void {
    $categorie1 = Categorie::factory()->create();
    $categorie2 = Categorie::factory()->create();

    $fiche1 = Fiche::factory()->create();
    $fiche2 = Fiche::factory()->create();
    $fiche3 = Fiche::factory()->create();

    $fiche1->categories()->attach($categorie1->id);
    $fiche2->categories()->attach($categorie2->id);
    $fiche3->categories()->attach([$categorie1->id, $categorie2->id]);

    $fichesCategorie1 = Fiche::withCategorie($categorie1)->get();
    $fichesCategorie2 = Fiche::withCategorie($categorie2)->get();

    expect($fichesCategorie1)->toHaveCount(2) // fiche1 et fiche3
        ->and($fichesCategorie2)->toHaveCount(2) // fiche2 et fiche3
        ->and($fichesCategorie1->pluck('id')->toArray())->toContain($fiche1->id)
        ->and($fichesCategorie1->pluck('id')->toArray())->toContain($fiche3->id);
});

/**
 * Test 17 : Vérifie que le scope withTag fonctionne correctement
 */
test('Fiche scope withTag filtre par tag', function (): void {
    $tag1 = Tag::factory()->create(['is_active' => true]);
    $tag2 = Tag::factory()->create(['is_active' => true]);

    $fiche1 = Fiche::factory()->create();
    $fiche2 = Fiche::factory()->create();
    $fiche3 = Fiche::factory()->create();

    $fiche1->tags()->attach($tag1->id);
    $fiche2->tags()->attach($tag2->id);
    $fiche3->tags()->attach([$tag1->id, $tag2->id]);

    $fichesTag1 = Fiche::withTag($tag1)->get();
    $fichesTag2 = Fiche::withTag($tag2)->get();

    expect($fichesTag1)->toHaveCount(2) // fiche1 et fiche3
        ->and($fichesTag2)->toHaveCount(2) // fiche2 et fiche3
        ->and($fichesTag1->pluck('id')->toArray())->toContain($fiche1->id)
        ->and($fichesTag1->pluck('id')->toArray())->toContain($fiche3->id);
});

/**
 * Test 18 : Vérifie que shouldBeSearchable fonctionne correctement
 */
test('Fiche shouldBeSearchable retourne true seulement pour les fiches publiées', function (): void {
    $fichePubliee = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);
    $ficheBrouillon = Fiche::factory()->create([
        'status' => StatusFiche::BROUILLON,
        'published_at' => null,
    ]);
    $fichePublieeSansDate = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => null,
    ]);

    expect($fichePubliee->shouldBeSearchable())->toBeTrue()
        ->and($ficheBrouillon->shouldBeSearchable())->toBeFalse()
        ->and($fichePublieeSansDate->shouldBeSearchable())->toBeFalse();
});

/**
 * Test 19 : Vérifie que toSearchableArray retourne les bonnes données
 */
test('Fiche toSearchableArray retourne les données de recherche', function (): void {
    $categorie = Categorie::factory()->create(['titre' => 'Ma Catégorie']);
    $tag = Tag::factory()->create(['nom' => 'Mon Tag', 'is_active' => true]);

    $fiche = Fiche::factory()->create([
        'titre' => 'Test Titre',
        'soustitre' => 'Test Sous-titre',
        'slug' => 'test-slug',
        'description' => 'Test Description',
        'contenu' => '<p>Test <strong>Contenu</strong></p>',
    ]);

    $fiche->categories()->attach($categorie->id);
    $fiche->tags()->attach($tag->id);

    $searchableArray = $fiche->toSearchableArray();

    expect($searchableArray)->toBeArray()
        ->and($searchableArray['titre'])->toBe('Test Titre')
        ->and($searchableArray['soustitre'])->toBe('Test Sous-titre')
        ->and($searchableArray['slug'])->toBe('test-slug')
        ->and($searchableArray['description'])->toBe('Test Description')
        ->and($searchableArray['contenu'])->toBe('Test Contenu') // HTML strips
        ->and($searchableArray['categories'])->toBe('Ma Catégorie')
        ->and($searchableArray['tags'])->toBe('Mon Tag');
});

/**
 * Test 20 : Vérifie que getTypeCouleur retourne la couleur du type
 */
test('Fiche getTypeCouleur retourne la couleur du type de fiche', function (): void {
    $ficheBiographie = Fiche::factory()->create(['type_fiche' => TypeFiche::BIOGRAPHIE]);
    $ficheEvenement = Fiche::factory()->create(['type_fiche' => TypeFiche::EVENEMENT]);

    expect($ficheBiographie->getTypeCouleur())->toBe(TypeFiche::BIOGRAPHIE->couleur())
        ->and($ficheEvenement->getTypeCouleur())->toBe(TypeFiche::EVENEMENT->couleur())
        ->and($ficheBiographie->getTypeCouleur())->toMatch('/^#[0-9A-F]{6}$/');
});

/**
 * Test 21 : Vérifie que getTypeIcone retourne l'icône du type
 */
test('Fiche getTypeIcone retourne l\'icône du type de fiche', function (): void {
    $ficheBiographie = Fiche::factory()->create(['type_fiche' => TypeFiche::BIOGRAPHIE]);
    $ficheEvenement = Fiche::factory()->create(['type_fiche' => TypeFiche::EVENEMENT]);

    expect($ficheBiographie->getTypeIcone())->toBe(TypeFiche::BIOGRAPHIE->icone())
        ->and($ficheEvenement->getTypeIcone())->toBe(TypeFiche::EVENEMENT->icone())
        ->and($ficheBiographie->getTypeIcone())->toBeString()
        ->and($ficheEvenement->getTypeIcone())->toBeString();
});

/**
 * Test 22 : Vérifie que canTransitionTo fonctionne correctement
 */
test('Fiche canTransitionTo valide les transitions de statut', function (): void {
    $ficheBrouillon = Fiche::factory()->create(['status' => StatusFiche::BROUILLON]);
    $ficheReview = Fiche::factory()->create(['status' => StatusFiche::EN_REVIEW]);
    $fichePubliee = Fiche::factory()->create(['status' => StatusFiche::PUBLIE]);

    // Transitions valides
    expect($ficheBrouillon->canTransitionTo(StatusFiche::EN_REVIEW))->toBeTrue()
        ->and($ficheBrouillon->canTransitionTo(StatusFiche::PUBLIE))->toBeTrue()
        ->and($ficheReview->canTransitionTo(StatusFiche::BROUILLON))->toBeTrue()
        ->and($ficheReview->canTransitionTo(StatusFiche::PUBLIE))->toBeTrue()
        ->and($fichePubliee->canTransitionTo(StatusFiche::BROUILLON))->toBeTrue()
        ->and($fichePubliee->canTransitionTo(StatusFiche::EN_REVIEW))->toBeTrue();

    // Transitions invalides (vers soi-même)
    expect($ficheBrouillon->canTransitionTo(StatusFiche::BROUILLON))->toBeFalse()
        ->and($ficheReview->canTransitionTo(StatusFiche::EN_REVIEW))->toBeFalse()
        ->and($fichePubliee->canTransitionTo(StatusFiche::PUBLIE))->toBeFalse();
});

/**
 * Test 23 : Vérifie que transitionTo change le statut et met à jour published_at
 */
test('Fiche transitionTo change le statut correctement', function (): void {
    $fiche = Fiche::factory()->create([
        'status' => StatusFiche::BROUILLON,
        'published_at' => null,
    ]);

    // Transition vers PUBLIE doit définir published_at
    $result = $fiche->transitionTo(StatusFiche::PUBLIE);

    expect($result)->toBeTrue()
        ->and($fiche->status)->toBe(StatusFiche::PUBLIE)
        ->and($fiche->published_at)->not->toBeNull();

    // Transition invalide doit retourner false
    $resultInvalide = $fiche->transitionTo(StatusFiche::PUBLIE);
    expect($resultInvalide)->toBeFalse();
});

/**
 * Test 24 : Vérifie que attachFicheConnexe fonctionne avec validation
 */
test('Fiche attachFicheConnexe attache des fiches avec validation', function (): void {
    $fiche1 = Fiche::factory()->create();
    $fiche2 = Fiche::factory()->create([
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);

    $fiche1->attachFicheConnexe($fiche2);
    expect($fiche1->fichesConnexes)->toHaveCount(1);

    // Double attachement ne doit pas créer de doublon
    $fiche1->attachFicheConnexe($fiche2);
    expect($fiche1->fichesConnexes)->toHaveCount(1);
});

/**
 * Test 25 : Vérifie que attachFicheConnexe empêche l'auto-référence
 */
test('Fiche attachFicheConnexe empêche l\'auto-référence', function (): void {
    $fiche = Fiche::factory()->create();

    expect(fn () => $fiche->attachFicheConnexe($fiche))
        ->toThrow(InvalidArgumentException::class, 'Une fiche ne peut pas être connexe à elle-même');
});

/**
 * Test 26 : Vérifie que getReadingTimeAttribute calcule correctement
 */
test('Fiche getReadingTimeAttribute calcule le temps de lecture', function (): void {
    $fiche = Fiche::factory()->create([
        'contenu' => str_repeat('mot ', 400), // 400 mots
    ]);

    expect($fiche->reading_time)->toBe(2); // 400 mots / 200 mots par minute = 2 minutes
});

/**
 * Test 27 : Vérifie que getWordCountAttribute compte les mots
 */
test('Fiche getWordCountAttribute compte les mots correctement', function (): void {
    $fiche = Fiche::factory()->create([
        'contenu' => '<p>Voici un <strong>test</strong> avec des mots.</p>', // 6 mots
    ]);

    expect($fiche->word_count)->toBe(6);
});

/**
 * Test 28 : Vérifie que le modèle utilise les traits attendus
 */
test('Fiche utilise les bons traits', function (): void {
    $fiche = new Fiche();

    expect(method_exists($fiche, 'getOptimizedRelations'))->toBeTrue() // HasOptimizedRelations
        ->and(method_exists($fiche, 'bootSoftDeletes'))->toBeTrue() // SoftDeletes
        ->and(method_exists($fiche, 'newFactory'))->toBeTrue() // HasFactory
        ->and(method_exists($fiche, 'searchableAs'))->toBeTrue(); // Searchable
});

/**
 * Test 29 : Vérifie que les scopes peuvent être combinés
 */
test('Fiche scopes peuvent être combinés', function (): void {
    $categorie = Categorie::factory()->create();
    $tag = Tag::factory()->create(['is_active' => true]);

    $fiche1 = Fiche::factory()->create([
        'type_fiche' => TypeFiche::BIOGRAPHIE,
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
        'featured_at' => now(),
    ]);

    $fiche2 = Fiche::factory()->create([
        'type_fiche' => TypeFiche::EVENEMENT,
        'status' => StatusFiche::PUBLIE,
        'published_at' => now(),
    ]);

    $fiche1->categories()->attach($categorie->id);
    $fiche1->tags()->attach($tag->id);

    $result = Fiche::published()
        ->featured()
        ->ofType(TypeFiche::BIOGRAPHIE)
        ->withCategorie($categorie)
        ->withTag($tag)
        ->get();

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($fiche1->id);
});

/**
 * Test 30 : Vérifie que le modèle utilise SoftDeletes
 */
test('Fiche utilise SoftDeletes', function (): void {
    $fiche = Fiche::factory()->create();
    $id = $fiche->id;

    $fiche->delete();

    expect(Fiche::find($id))->toBeNull()
        ->and(Fiche::withTrashed()->find($id))->not->toBeNull()
        ->and(Fiche::withTrashed()->find($id)->deleted_at)->not->toBeNull();
});
