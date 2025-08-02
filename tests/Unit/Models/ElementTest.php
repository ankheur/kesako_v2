<?php

declare(strict_types=1);

use App\Enums\TypeLien;
use App\Models\Element;
use App\Models\Fiche;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Test 1 : Vérifie que le modèle peut être créé avec les attributs requis
 */
test('Element peut être créé avec les attributs de base', function (): void {
    $fiche = Fiche::factory()->create();

    $element = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'nom' => 'Test Element',
        'complement' => 'Un complément',
        'lien_externe' => 'https://example.com',
        'type_lien' => TypeLien::AUTRE,
        'ordre' => 1,
    ]);

    expect($element->fiche_id)->toBe($fiche->id)
        ->and($element->nom)->toBe('Test Element')
        ->and($element->complement)->toBe('Un complément')
        ->and($element->lien_externe)->toBe('https://example.com')
        ->and($element->type_lien)->toBe(TypeLien::AUTRE)
        ->and($element->ordre)->toBe(1)
        ->and($element->exists)->toBeTrue();
});

/**
 * Test 2 : Vérifie que les attributs fillable sont correctement définis
 */
test('Element a les bons attributs fillable', function (): void {
    $expectedFillable = [
        'fiche_id',
        'nom',
        'complement',
        'image',
        'image_alt',
        'fiche_liee_id',
        'lien_externe',
        'type_lien',
        'ordre',
    ];

    expect(Element::make()->getFillable())->toBe($expectedFillable);
});

/**
 * Test 3 : Vérifie que les casts sont correctement définis
 */
test('Element a les bons casts', function (): void {
    $fiche = Fiche::factory()->create();
    $ficheLiee = Fiche::factory()->create();

    $element = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => $ficheLiee->id,
        'type_lien' => TypeLien::SPOTIFY,
        'ordre' => '5',
    ]);

    expect($element->type_lien)->toBeInstanceOf(TypeLien::class)
        ->and($element->type_lien)->toBe(TypeLien::SPOTIFY)
        ->and($element->ordre)->toBeInt()
        ->and($element->fiche_id)->toBeInt()
        ->and($element->fiche_liee_id)->toBeInt();
});

/**
 * Test 4 : Vérifie que la relation fiche fonctionne correctement
 */
test('Element a une relation belongsTo avec Fiche', function (): void {
    $fiche = Fiche::factory()->create();
    $element = Element::factory()->create(['fiche_id' => $fiche->id]);

    expect($element->fiche())->toBeInstanceOf(BelongsTo::class)
        ->and($element->fiche)->toBeInstanceOf(Fiche::class)
        ->and($element->fiche->id)->toBe($fiche->id);
});

/**
 * Test 5 : Vérifie que la relation ficheLiee fonctionne correctement
 */
test('Element a une relation belongsTo avec Fiche pour ficheLiee', function (): void {
    $fiche = Fiche::factory()->create();
    $ficheLiee = Fiche::factory()->create();
    $element = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => $ficheLiee->id,
    ]);

    expect($element->ficheLiee())->toBeInstanceOf(BelongsTo::class)
        ->and($element->ficheLiee)->toBeInstanceOf(Fiche::class)
        ->and($element->ficheLiee->id)->toBe($ficheLiee->id);
});

/**
 * Test 6 : Vérifie que la relation ficheLiee peut être nulle
 */
test('Element relation ficheLiee peut être nulle', function (): void {
    $fiche = Fiche::factory()->create();
    $element = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => null,
    ]);

    expect($element->ficheLiee)->toBeNull();
});

/**
 * Test 7 : Vérifie que le scope ordered fonctionne correctement
 */
test('Element scope ordered trie par ordre', function (): void {
    $fiche = Fiche::factory()->create();

    Element::factory()->create(['fiche_id' => $fiche->id, 'ordre' => 3, 'nom' => 'Troisième']);
    Element::factory()->create(['fiche_id' => $fiche->id, 'ordre' => 1, 'nom' => 'Premier']);
    Element::factory()->create(['fiche_id' => $fiche->id, 'ordre' => 2, 'nom' => 'Deuxième']);

    $orderedElements = Element::ordered()->get();

    expect($orderedElements->get(0)->nom)->toBe('Premier')
        ->and($orderedElements->get(1)->nom)->toBe('Deuxième')
        ->and($orderedElements->get(2)->nom)->toBe('Troisième');
});

/**
 * Test 8 : Vérifie que le scope withLinks fonctionne correctement
 */
test('Element scope withLinks ne retourne que les éléments avec liens externes', function (): void {
    $fiche = Fiche::factory()->create();

    Element::factory()->create(['fiche_id' => $fiche->id, 'lien_externe' => 'https://example.com']);
    Element::factory()->create(['fiche_id' => $fiche->id, 'lien_externe' => null]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'lien_externe' => 'https://test.com']);

    $elementsWithLinks = Element::withLinks()->get();

    expect($elementsWithLinks)->toHaveCount(2)
        ->and($elementsWithLinks->first()->lien_externe)->not->toBeNull()
        ->and($elementsWithLinks->last()->lien_externe)->not->toBeNull();
});

/**
 * Test 9 : Vérifie que le scope withFiches fonctionne correctement
 */
test('Element scope withFiches ne retourne que les éléments avec fiches liées', function (): void {
    $fiche = Fiche::factory()->create();
    $ficheLiee = Fiche::factory()->create();

    Element::factory()->create(['fiche_id' => $fiche->id, 'fiche_liee_id' => $ficheLiee->id]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'fiche_liee_id' => null]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'fiche_liee_id' => $ficheLiee->id]);

    $elementsWithFiches = Element::withFiches()->get();

    expect($elementsWithFiches)->toHaveCount(2)
        ->and($elementsWithFiches->first()->fiche_liee_id)->not->toBeNull()
        ->and($elementsWithFiches->last()->fiche_liee_id)->not->toBeNull();
});

/**
 * Test 10 : Vérifie que le scope musical fonctionne correctement
 */
test('Element scope musical ne retourne que les éléments musicaux', function (): void {
    $fiche = Fiche::factory()->create();

    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::SPOTIFY]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::WIKIPEDIA]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::YOUTUBE]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::AUTRE]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::DEEZER]);

    $elementsMusical = Element::musical()->get();

    expect($elementsMusical)->toHaveCount(3) // SPOTIFY, YOUTUBE, DEEZER
        ->and($elementsMusical->pluck('type_lien')->toArray())->toContain(TypeLien::SPOTIFY)
        ->and($elementsMusical->pluck('type_lien')->toArray())->toContain(TypeLien::YOUTUBE)
        ->and($elementsMusical->pluck('type_lien')->toArray())->toContain(TypeLien::DEEZER)
        ->and($elementsMusical->pluck('type_lien')->toArray())->not->toContain(TypeLien::WIKIPEDIA)
        ->and($elementsMusical->pluck('type_lien')->toArray())->not->toContain(TypeLien::AUTRE);
});

/**
 * Test 11 : Vérifie que hasLienExterne fonctionne correctement
 */
test('Element hasLienExterne détecte la présence de liens externes', function (): void {
    $fiche = Fiche::factory()->create();

    $elementAvecLien = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'lien_externe' => 'https://example.com',
    ]);
    $elementSansLien = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'lien_externe' => null,
    ]);

    expect($elementAvecLien->hasLienExterne())->toBeTrue()
        ->and($elementSansLien->hasLienExterne())->toBeFalse();
});

/**
 * Test 12 : Vérifie que hasFicheLiee fonctionne correctement
 */
test('Element hasFicheLiee détecte la présence de fiches liées', function (): void {
    $fiche = Fiche::factory()->create();
    $ficheLiee = Fiche::factory()->create();

    $elementAvecFicheLiee = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => $ficheLiee->id,
    ]);
    $elementSansFicheLiee = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => null,
    ]);

    expect($elementAvecFicheLiee->hasFicheLiee())->toBeTrue()
        ->and($elementSansFicheLiee->hasFicheLiee())->toBeFalse();
});

/**
 * Test 13 : Vérifie que getLienCouleur fonctionne correctement
 */
test('Element getLienCouleur retourne la couleur du type de lien', function (): void {
    $fiche = Fiche::factory()->create();

    $elementSpotify = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::SPOTIFY,
    ]);
    $elementSansType = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => null,
    ]);

    expect($elementSpotify->getLienCouleur())->toBe(TypeLien::SPOTIFY->couleur())
        ->and($elementSpotify->getLienCouleur())->toMatch('/^#[0-9A-F]{6}$/')
        ->and($elementSansType->getLienCouleur())->toBe('#6B7280'); // Couleur par défaut
});

/**
 * Test 14 : Vérifie que getLienIcone fonctionne correctement
 */
test('Element getLienIcone retourne l\'icône du type de lien', function (): void {
    $fiche = Fiche::factory()->create();

    $elementYoutube = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::YOUTUBE,
    ]);
    $elementSansType = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => null,
    ]);

    expect($elementYoutube->getLienIcone())->toBe(TypeLien::YOUTUBE->icone())
        ->and($elementYoutube->getLienIcone())->toBeString()
        ->and($elementSansType->getLienIcone())->toBe('link'); // Icône par défaut
});

/**
 * Test 15 : Vérifie que validateLienExterne fonctionne correctement
 */
test('Element validateLienExterne valide les URLs selon leur type', function (): void {
    $fiche = Fiche::factory()->create();

    // URL valide pour le type
    $elementValide = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::SPOTIFY,
        'lien_externe' => 'https://open.spotify.com/track/test',
    ]);

    // URL invalide pour le type
    $elementInvalide = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::SPOTIFY,
        'lien_externe' => 'https://youtube.com/watch',
    ]);

    // Sans lien externe (valide par défaut)
    $elementSansLien = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::SPOTIFY,
        'lien_externe' => null,
    ]);

    // Sans type de lien (valide par défaut)
    $elementSansType = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => null,
        'lien_externe' => 'https://example.com',
    ]);

    expect($elementValide->validateLienExterne())->toBeTrue()
        ->and($elementInvalide->validateLienExterne())->toBeFalse()
        ->and($elementSansLien->validateLienExterne())->toBeTrue()
        ->and($elementSansType->validateLienExterne())->toBeTrue();
});

/**
 * Test 16 : Vérifie que detectTypeLien fonctionne correctement
 */
test('Element detectTypeLien détecte automatiquement le type', function (): void {
    $fiche = Fiche::factory()->create();

    $elementSpotify = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'lien_externe' => 'https://open.spotify.com/track/test',
        'type_lien' => null,
    ]);

    $elementYoutube = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'lien_externe' => 'https://www.youtube.com/watch?v=test',
        'type_lien' => null,
    ]);

    $elementAutre = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'lien_externe' => 'https://example.com',
        'type_lien' => null,
    ]);

    $elementSansLien = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'lien_externe' => null,
        'type_lien' => null,
    ]);

    // Détection automatique
    $elementSpotify->detectTypeLien();
    $elementYoutube->detectTypeLien();
    $elementAutre->detectTypeLien();
    $elementSansLien->detectTypeLien();

    expect($elementSpotify->type_lien)->toBe(TypeLien::SPOTIFY)
        ->and($elementYoutube->type_lien)->toBe(TypeLien::YOUTUBE)
        ->and($elementAutre->type_lien)->toBe(TypeLien::AUTRE)
        ->and($elementSansLien->type_lien)->toBeNull(); // Pas de changement si pas de lien
});

/**
 * Test 17 : Vérifie que les scopes peuvent être combinés
 */
test('Element scopes peuvent être combinés', function (): void {
    $fiche = Fiche::factory()->create();

    Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::SPOTIFY,
        'lien_externe' => 'https://open.spotify.com/track/test',
        'ordre' => 1,
    ]);

    Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::WIKIPEDIA,
        'lien_externe' => 'https://wikipedia.org/test',
        'ordre' => 2,
    ]);

    Element::factory()->create([
        'fiche_id' => $fiche->id,
        'type_lien' => TypeLien::YOUTUBE,
        'lien_externe' => 'https://youtube.com/watch',
        'ordre' => 3,
    ]);

    $result = Element::withLinks()
        ->musical()
        ->ordered()
        ->get();

    expect($result)->toHaveCount(2) // SPOTIFY et YOUTUBE
        ->and($result->first()->type_lien)->toBe(TypeLien::SPOTIFY) // ordre 1
        ->and($result->last()->type_lien)->toBe(TypeLien::YOUTUBE); // ordre 3
});

/**
 * Test 18 : Vérifie que le modèle utilise les traits attendus
 */
test('Element utilise les bons traits', function (): void {
    $element = new Element();

    expect(method_exists($element, 'newFactory'))->toBeTrue(); // HasFactory
});

/**
 * Test 19 : Vérifie la cohérence entre les méthodes et les scopes
 */
test('Element méthodes et scopes sont cohérents', function (): void {
    $fiche = Fiche::factory()->create();
    $ficheLiee = Fiche::factory()->create();

    $elementComplet = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => $ficheLiee->id,
        'lien_externe' => 'https://open.spotify.com/track/test',
        'type_lien' => TypeLien::SPOTIFY,
    ]);

    $elementVide = Element::factory()->create([
        'fiche_id' => $fiche->id,
        'fiche_liee_id' => null,
        'lien_externe' => null,
        'type_lien' => null,
    ]);

    // L'élément complet doit être trouvé par tous les scopes correspondants
    expect(Element::withLinks()->get()->contains('fiche_liee_id', $ficheLiee->id))->toBeTrue()
        ->and(Element::withFiches()->get()->contains('fiche_liee_id', $ficheLiee->id))->toBeTrue()
        ->and(Element::musical()->get()->contains('fiche_liee_id', $ficheLiee->id))->toBeTrue();

    // L'élément vide ne doit être trouvé par aucun scope restrictif
    expect(Element::withLinks()->get()->contains('id', $elementVide->id))->not->toBeTrue()
        ->and(Element::withFiches()->get()->contains('id', $elementVide->id))->not->toBeTrue()
        ->and(Element::musical()->get()->contains('id', $elementVide->id))->not->toBeTrue();

    // Cohérence avec les méthodes
    expect($elementComplet->hasLienExterne())->toBeTrue()
        ->and($elementComplet->hasFicheLiee())->toBeTrue()
        ->and($elementVide->hasLienExterne())->toBeFalse()
        ->and($elementVide->hasFicheLiee())->toBeFalse();
});

/**
 * Test 20 : Vérifie que le scope musical utilise bien tous les types musicaux
 */
test('Element scope musical inclut tous les types musicaux de TypeLien', function (): void {
    $fiche = Fiche::factory()->create();
    $typesMusicaux = [
        TypeLien::SPOTIFY,
        TypeLien::DEEZER,
        TypeLien::APPLE_MUSIC,
        TypeLien::BANDCAMP,
        TypeLien::YOUTUBE,
    ];

    // Créer un élément pour chaque type musical
    foreach ($typesMusicaux as $type) {
        Element::factory()->create([
            'fiche_id' => $fiche->id,
            'type_lien' => $type,
        ]);
    }

    // Créer des éléments non musicaux
    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::WIKIPEDIA]);
    Element::factory()->create(['fiche_id' => $fiche->id, 'type_lien' => TypeLien::AUTRE]);

    $elementsMusical = Element::musical()->get();
    $typesRetournes = $elementsMusical->pluck('type_lien')->toArray();

    expect($elementsMusical)->toHaveCount(5);

    // Vérifier que chaque type musical est présent (sans se soucier de l'ordre)
    foreach ($typesMusicaux as $type) {
        expect($typesRetournes)->toContain($type);
    }

    // Vérifier qu'aucun type non musical n'est présent
    expect($typesRetournes)->not->toContain(TypeLien::WIKIPEDIA)
        ->and($typesRetournes)->not->toContain(TypeLien::AUTRE);
});
