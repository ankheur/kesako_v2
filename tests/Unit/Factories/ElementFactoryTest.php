<?php

declare(strict_types=1);

use App\Enums\TypeLien;
use App\Models\Element;
use App\Models\Fiche;
use Illuminate\Support\Collection;

/**
 * Test 1 : Vérifie que la factory peut créer des éléments avec les attributs de base
 */
test('ElementFactory peut créer un élément de base', function (): void {
    $element = Element::factory()->create();

    expect($element)->toBeInstanceOf(Element::class)
        ->and($element->exists)->toBeTrue()
        ->and($element->nom)->toBeString()->not->toBeEmpty()
        ->and($element->fiche_id)->toBeInt()
        ->and($element->ordre)->toBeInt()->toBeBetween(1, 10)
        ->and($element->fiche_liee_id)->toBeNull()
        ->and($element->lien_externe)->toBeNull()
        ->and($element->type_lien)->toBeNull();
});

/**
 * Test 2 : Vérifie la génération optionnelle de compléments et images
 */
test('ElementFactory génère des compléments et images optionnels', function (): void {
    $elements = Element::factory()->count(20)->create();

    // Vérification des compléments (probabilité 0.6)
    /** @var Collection<int, string|int> $complements */
    $complements = $elements->pluck('complement');
    expect($complements->count())->toBeGreaterThan(5);

    // Vérification des images (probabilité 0.7)
    /** @var Collection<int, string> $images */
    $images = $elements->pluck('image')->filter();
    expect($images->count())->toBeGreaterThan(8);

    $validImages = ['elements/element-1.jpg', 'elements/element-2.jpg', 'elements/element-3.jpg'];
    $images->each(function (mixed $image) use ($validImages): void {
        expect($image)->toBeString()->toBeIn($validImages);
    });
});

/**
 * Test 3 : Vérifie la logique de génération automatique de image_alt
 */
test('ElementFactory génère image_alt selon la présence d\'image', function (): void {
    // Avec image
    $elementAvecImage = Element::factory()->state(['image' => 'elements/element-1.jpg'])->create();
    expect($elementAvecImage->image)->toBe('elements/element-1.jpg')
        ->and($elementAvecImage->image_alt)->toBe('Image de '.$elementAvecImage->nom);

    // Sans image
    $elementSansImage = Element::factory()->state(['image' => null])->create();
    expect($elementSansImage->image)->toBeNull()
        ->and($elementSansImage->image_alt)->toBeNull();
});

/**
 * Test 4 : Vérifie la création d'un élément avec fiche liée
 */
test('ElementFactory peut créer un élément avec fiche liée', function (): void {
    $element = Element::factory()->withFiche()->create();

    expect($element->fiche_liee_id)->toBeInt()
        ->and($element->lien_externe)->toBeNull()
        ->and($element->type_lien)->toBeNull();

    // Vérification de la relation si elle existe
    if (method_exists($element, 'ficheliee')) {
        $ficheliee = $element->ficheliee;
        expect($ficheliee)->toBeInstanceOf(Fiche::class);
        if ($ficheliee instanceof Fiche) {
            expect($ficheliee->published_at)->not->toBeNull();
        }
    }
});

/**
 * Test 5 : Vérifie la création d'éléments avec liens externes et détection automatique de type
 */
test('ElementFactory peut créer des éléments avec liens externes', function (): void {
    // Avec type spécifique
    $urlCustom = 'https://example.com/test';
    $typeCustom = TypeLien::WIKIPEDIA;
    $elementCustom = Element::factory()->withLink($urlCustom, $typeCustom)->create();

    expect($elementCustom->lien_externe)->toBe($urlCustom)
        ->and($elementCustom->type_lien)->toBe($typeCustom)
        ->and($elementCustom->fiche_liee_id)->toBeNull();

    // Avec détection automatique
    $urlAuto = 'https://open.spotify.com/track/test123';
    $elementAuto = Element::factory()->withLink($urlAuto)->create();

    expect($elementAuto->lien_externe)->toBe($urlAuto)
        ->and($elementAuto->type_lien)->toBeInstanceOf(TypeLien::class);
});

/**
 * Test 6 : Vérifie les méthodes spécialisées de liens (Spotify, YouTube, Wikipedia)
 */
test('ElementFactory peut créer des éléments avec liens spécialisés', function (): void {
    // Spotify
    $spotify = Element::factory()->spotify()->create();
    expect($spotify->lien_externe)->toBeString()->toStartWith('https://open.spotify.com/track/')
        ->and($spotify->type_lien)->toBe(TypeLien::SPOTIFY);

    $spotifyUrl = $spotify->lien_externe;
    if (is_string($spotifyUrl)) {
        $spotifyId = str_replace('https://open.spotify.com/track/', '', $spotifyUrl);
        expect($spotifyId)->toHaveLength(7);
    }

    // YouTube
    $youtube = Element::factory()->youtube()->create();
    expect($youtube->lien_externe)->toBeString()->toStartWith('https://www.youtube.com/watch?v=')
        ->and($youtube->type_lien)->toBe(TypeLien::YOUTUBE);

    $youtubeUrl = $youtube->lien_externe;
    if (is_string($youtubeUrl)) {
        $youtubeId = str_replace('https://www.youtube.com/watch?v=', '', $youtubeUrl);
        expect($youtubeId)->toHaveLength(11);
    }

    // Wikipedia
    $wikipedia = Element::factory()->wikipedia()->create();
    expect($wikipedia->lien_externe)->toBeString()->toStartWith('https://fr.wikipedia.org/wiki/')
        ->and($wikipedia->type_lien)->toBe(TypeLien::WIKIPEDIA);

    $wikipediaUrl = $wikipedia->lien_externe;
    if (is_string($wikipediaUrl)) {
        $slug = str_replace('https://fr.wikipedia.org/wiki/', '', $wikipediaUrl);
        expect($slug)->toMatch('/^[a-z0-9-]+$/');
    }
});

/**
 * Test 7 : Vérifie la création d'éléments thématiques (musical et théâtral)
 */
test('ElementFactory peut créer des éléments thématiques', function (): void {
    // Musical
    $musical = Element::factory()->musical()->create();
    $validMusicalWorks = ['Symphonie No. 9', 'Clair de Lune', 'La Marseillaise', 'Ave Maria', 'Boléro'];
    expect($musical->nom)->toBeString()->toBeIn($validMusicalWorks);

    $musicalComplement = $musical->complement;
    if (is_int($musicalComplement)) {
        expect($musicalComplement)->toBeBetween(1900, (int) date('Y'));
    } elseif (is_string($musicalComplement)) {
        expect($musicalComplement)->toMatch('/^\d{4}$/');
        expect((int) $musicalComplement)->toBeBetween(1900, (int) date('Y'));
    }

    // Théâtral
    $theatrical = Element::factory()->theatrical()->create();
    $validRoles = ['Réalisateur', 'Acteur principal', 'Scénariste', 'Producteur'];
    expect($theatrical->nom)->toBeString()->not->toBeEmpty();

    $theatricalComplement = $theatrical->complement;
    if (is_string($theatricalComplement)) {
        expect($theatricalComplement)->toBeIn($validRoles);
    }
});

/**
 * Test 8 : Vérifie les relations avec les modèles Fiche
 */
test('ElementFactory gère correctement les relations avec Fiche', function (): void {
    $fiche = Fiche::factory()->create();
    $element = Element::factory()->for($fiche)->create();

    expect($element->fiche_id)->toBe($fiche->id);

    // Vérification de la relation si elle existe
    if (method_exists($element, 'fiche')) {
        $ficheRelation = $element->fiche;
        expect($ficheRelation)->toBeInstanceOf(Fiche::class);
        if ($ficheRelation instanceof Fiche) {
            expect($ficheRelation->id)->toBe($fiche->id);
        }
    }
});

/**
 * Test 9 : Vérifie l'exclusivité mutuelle entre fiche_liee_id et lien_externe
 */
test('ElementFactory maintient l\'exclusivité entre fiche liée et lien externe', function (): void {
    $elementAvecFiche = Element::factory()->withFiche()->create();
    $elementAvecLien = Element::factory()->withLink('https://example.com')->create();

    // Élément avec fiche liée ne doit pas avoir de lien externe
    expect($elementAvecFiche->fiche_liee_id)->toBeInt()
        ->and($elementAvecFiche->lien_externe)->toBeNull()
        ->and($elementAvecFiche->type_lien)->toBeNull();

    // Élément avec lien externe ne doit pas avoir de fiche liée
    expect($elementAvecLien->lien_externe)->toBeString()->not->toBeEmpty()
        ->and($elementAvecLien->type_lien)->toBeInstanceOf(TypeLien::class)
        ->and($elementAvecLien->fiche_liee_id)->toBeNull();
});

/**
 * Test 10 : Vérifie la variété et l'unicité des données générées
 */
test('ElementFactory génère des données variées et uniques', function (): void {
    $elements = Element::factory()->count(20)->create();

    // Variété des noms
    /** @var Collection<int, string> $noms */
    $noms = $elements->pluck('nom')->unique();
    expect($noms->count())->toBeGreaterThan(10);

    // Plage d'ordre correcte
    /** @var Collection<int, int> $ordres */
    $ordres = $elements->pluck('ordre');
    $ordresArray = $ordres->toArray();
    expect(min($ordres->toArray()))->toBeGreaterThanOrEqual(1)
        ->and(max($ordresArray))->toBeLessThanOrEqual(10);
});

/**
 * Test 11 : Vérifie les combinaisons de states sans redondance
 */
test('ElementFactory peut combiner des states efficacement', function (): void {
    $musicalSpotify = Element::factory()->musical()->spotify()->create();

    $validMusicalWorks = ['Symphonie No. 9', 'Clair de Lune', 'La Marseillaise', 'Ave Maria', 'Boléro'];
    expect($musicalSpotify->nom)->toBeString()->toBeIn($validMusicalWorks);

    $complement = $musicalSpotify->complement;
    if (is_int($complement)) {
        expect($complement)->toBeBetween(1900, (int) date('Y'));
    } elseif (is_string($complement)) {
        expect($complement)->toMatch('/^\d{4}$/');
    }

    expect($musicalSpotify->type_lien)->toBe(TypeLien::SPOTIFY)
        ->and($musicalSpotify->lien_externe)->toBeString()->toStartWith('https://open.spotify.com/track/');
});
