<?php

declare(strict_types=1);

use App\Enums\TypeLien;

/**
 * Test 1 : Vérifie que chaque case de l'enum a la bonne valeur string
 */
test('TypeLien has correct values', function (): void {
    expect(TypeLien::WIKIPEDIA->value)->toBe('wikipedia')
        ->and(TypeLien::YOUTUBE->value)->toBe('youtube')
        ->and(TypeLien::SPOTIFY->value)->toBe('spotify')
        ->and(TypeLien::DEEZER->value)->toBe('deezer')
        ->and(TypeLien::APPLE_MUSIC->value)->toBe('apple_music')
        ->and(TypeLien::BANDCAMP->value)->toBe('bandcamp')
        ->and(TypeLien::AUTRE->value)->toBe('autre');
});

/**
 * Test 2 : Vérifie que les labels d'affichage sont corrects
 */
test('TypeLien has correct labels', function (): void {
    expect(TypeLien::WIKIPEDIA->label())->toBe('Wikipedia')
        ->and(TypeLien::YOUTUBE->label())->toBe('YouTube')
        ->and(TypeLien::SPOTIFY->label())->toBe('Spotify')
        ->and(TypeLien::DEEZER->label())->toBe('Deezer')
        ->and(TypeLien::APPLE_MUSIC->label())->toBe('Apple Music')
        ->and(TypeLien::BANDCAMP->label())->toBe('Bandcamp')
        ->and(TypeLien::AUTRE->label())->toBe('Autre');
});

/**
 * Test 3 : Vérifie que les couleurs sont des codes hexadécimaux valides et corrects
 */
test('TypeLien has correct colors', function (): void {
    expect(TypeLien::WIKIPEDIA->couleur())->toBe('#000000')
        ->and(TypeLien::YOUTUBE->couleur())->toBe('#FF0000')
        ->and(TypeLien::SPOTIFY->couleur())->toBe('#1DB954')
        ->and(TypeLien::DEEZER->couleur())->toBe('#FF6600')
        ->and(TypeLien::APPLE_MUSIC->couleur())->toBe('#FA2D48')
        ->and(TypeLien::BANDCAMP->couleur())->toBe('#629AA0')
        ->and(TypeLien::AUTRE->couleur())->toBe('#9CA3AF');
});

/**
 * Test 4 : Vérifie que les icônes sont définies correctement
 * Important pour l'affichage dans l'interface (liens, badges)
 */
test('TypeLien has correct icons', function (): void {
    expect(TypeLien::WIKIPEDIA->icone())->toBe('wikipedia')
        ->and(TypeLien::YOUTUBE->icone())->toBe('youtube')
        ->and(TypeLien::SPOTIFY->icone())->toBe('music')
        ->and(TypeLien::DEEZER->icone())->toBe('music')
        ->and(TypeLien::APPLE_MUSIC->icone())->toBe('music')
        ->and(TypeLien::BANDCAMP->icone())->toBe('music')
        ->and(TypeLien::AUTRE->icone())->toBe('link');
});

/**
 * Test 5 : Vérifie que la méthode options() retourne le bon format
 * Crucial pour les selects Filament lors de la création de liens
 */
test('TypeLien options returns correct array structure', function (): void {
    $options = TypeLien::options();

    expect($options)->toBeArray()
        ->and($options)->toHaveCount(7)
        ->and(array_keys($options))->toEqual([
            'wikipedia', 'youtube', 'spotify', 'deezer', 'apple_music', 'bandcamp', 'autre',
        ])
        ->and($options['wikipedia'])->toBe('Wikipedia')
        ->and($options['youtube'])->toBe('YouTube')
        ->and($options['spotify'])->toBe('Spotify')
        ->and($options['deezer'])->toBe('Deezer')
        ->and($options['apple_music'])->toBe('Apple Music')
        ->and($options['bandcamp'])->toBe('Bandcamp')
        ->and($options['autre'])->toBe('Autre');
});

/**
 * Test 6 : Vérifie que musicaux() retourne uniquement les types musicaux
 */
test('TypeLien musicaux returns only musical types', function (): void {
    $musicaux = TypeLien::musicaux();

    expect($musicaux)->toBeArray()
        ->and($musicaux)->toHaveCount(5)
        ->and($musicaux)->toContain(TypeLien::SPOTIFY)
        ->and($musicaux)->toContain(TypeLien::DEEZER)
        ->and($musicaux)->toContain(TypeLien::APPLE_MUSIC)
        ->and($musicaux)->toContain(TypeLien::BANDCAMP)
        ->and($musicaux)->toContain(TypeLien::YOUTUBE)
        ->and($musicaux)->not->toContain(TypeLien::WIKIPEDIA)
        ->and($musicaux)->not->toContain(TypeLien::AUTRE);
});

/**
 * Test 7 : Vérifie que isMusical() fonctionne correctement
 */
test('TypeLien isMusical works correctly', function (): void {
    // Types musicaux
    expect(TypeLien::SPOTIFY->isMusical())->toBeTrue()
        ->and(TypeLien::DEEZER->isMusical())->toBeTrue()
        ->and(TypeLien::APPLE_MUSIC->isMusical())->toBeTrue()
        ->and(TypeLien::BANDCAMP->isMusical())->toBeTrue()
        ->and(TypeLien::YOUTUBE->isMusical())->toBeTrue();

    // Types non musicaux
    expect(TypeLien::WIKIPEDIA->isMusical())->toBeFalse()
        ->and(TypeLien::AUTRE->isMusical())->toBeFalse();
});

/**
 * Test 8 : Vérifie que getUrlPattern() retourne les bons patterns
 */
test('TypeLien getUrlPattern returns correct patterns', function (): void {
    expect(TypeLien::WIKIPEDIA->getUrlPattern())->toBe('https://*.wikipedia.org/')
        ->and(TypeLien::YOUTUBE->getUrlPattern())->toBe('https://www.youtube.com/')
        ->and(TypeLien::SPOTIFY->getUrlPattern())->toBe('https://open.spotify.com/')
        ->and(TypeLien::DEEZER->getUrlPattern())->toBe('https://www.deezer.com/')
        ->and(TypeLien::APPLE_MUSIC->getUrlPattern())->toBe('https://music.apple.com/')
        ->and(TypeLien::BANDCAMP->getUrlPattern())->toBe('https://*.bandcamp.com/')
        ->and(TypeLien::AUTRE->getUrlPattern())->toBeNull();
});

/**
 * Test 9 : Vérifie que getDomain() retourne les bons domaines
 */
test('TypeLien getDomain returns correct domains', function (): void {
    expect(TypeLien::WIKIPEDIA->getDomain())->toBe('www.wikipedia.org')
        ->and(TypeLien::YOUTUBE->getDomain())->toBe('www.youtube.com')
        ->and(TypeLien::SPOTIFY->getDomain())->toBe('open.spotify.com')
        ->and(TypeLien::DEEZER->getDomain())->toBe('www.deezer.com')
        ->and(TypeLien::APPLE_MUSIC->getDomain())->toBe('music.apple.com')
        ->and(TypeLien::BANDCAMP->getDomain())->toBe('www.bandcamp.com')
        ->and(TypeLien::AUTRE->getDomain())->toBeNull();
});

/**
 * Test 10 : Vérifie que validateUrl() fonctionne pour les URLs valides
 */
test('TypeLien validateUrl accepts valid URLs', function (): void {
    // Wikipedia avec différents sous-domaines
    expect(TypeLien::WIKIPEDIA->validateUrl('https://fr.wikipedia.org/wiki/Test'))->toBeTrue()
        ->and(TypeLien::WIKIPEDIA->validateUrl('https://en.wikipedia.org/wiki/Test'))->toBeTrue()
        ->and(TypeLien::WIKIPEDIA->validateUrl('https://simple.wikipedia.org/wiki/Test'))->toBeTrue();

    // YouTube
    expect(TypeLien::YOUTUBE->validateUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ'))->toBeTrue()
        ->and(TypeLien::YOUTUBE->validateUrl('https://www.youtube.com/playlist?list=test'))->toBeTrue();

    // Spotify
    expect(TypeLien::SPOTIFY->validateUrl('https://open.spotify.com/track/4iV5W9uYEdYUVa79Axb7Rh'))->toBeTrue()
        ->and(TypeLien::SPOTIFY->validateUrl('https://open.spotify.com/album/1DFixLWuPkv3KT3TnV35m3'))->toBeTrue();

    // Deezer
    expect(TypeLien::DEEZER->validateUrl('https://www.deezer.com/track/123456'))->toBeTrue()
        ->and(TypeLien::DEEZER->validateUrl('https://www.deezer.com/album/456789'))->toBeTrue();

    // Apple Music
    expect(TypeLien::APPLE_MUSIC->validateUrl('https://music.apple.com/us/album/test'))->toBeTrue()
        ->and(TypeLien::APPLE_MUSIC->validateUrl('https://music.apple.com/fr/artist/test'))->toBeTrue();

    // Bandcamp avec sous-domaines
    expect(TypeLien::BANDCAMP->validateUrl('https://artist.bandcamp.com/album/test'))->toBeTrue()
        ->and(TypeLien::BANDCAMP->validateUrl('https://test-artist.bandcamp.com/track/song'))->toBeTrue();

    // Site officiel et Autre acceptent toute URL valide
    expect(TypeLien::AUTRE->validateUrl('https://any-website.com/path'))->toBeTrue();
});

/**
 * Test 11 : Vérifie que validateUrl() rejette les URLs invalides
 */
test('TypeLien validateUrl rejects invalid URLs', function (): void {
    // URLs incorrectes pour chaque type spécifique
    expect(TypeLien::WIKIPEDIA->validateUrl('https://youtube.com'))->toBeFalse()
        ->and(TypeLien::YOUTUBE->validateUrl('https://youtu.be/test'))->toBeFalse()
        ->and(TypeLien::SPOTIFY->validateUrl('https://spotify.com'))->toBeFalse()
        ->and(TypeLien::DEEZER->validateUrl('https://deezer.com'))->toBeFalse()
        ->and(TypeLien::APPLE_MUSIC->validateUrl('https://apple.com'))->toBeFalse()
        ->and(TypeLien::BANDCAMP->validateUrl('https://bandcamp.com'))->toBeFalse();

    // URLs complètement invalides pour tous les types
    foreach (TypeLien::cases() as $type) {
        expect($type->validateUrl('not-a-url'))->toBeFalse()
            ->and($type->validateUrl(''))->toBeFalse()
            ->and($type->validateUrl('invalid-format'))->toBeFalse();
    }
});

/**
 * Test 12 : Vérifie que detectFromUrl() détecte correctement les types
 */
test('TypeLien detectFromUrl detects types correctly', function (): void {
    // Détection correcte pour chaque type
    expect(TypeLien::detectFromUrl('https://fr.wikipedia.org/wiki/Test'))->toBe(TypeLien::WIKIPEDIA)
        ->and(TypeLien::detectFromUrl('https://www.youtube.com/watch?v=test'))->toBe(TypeLien::YOUTUBE)
        ->and(TypeLien::detectFromUrl('https://open.spotify.com/track/test'))->toBe(TypeLien::SPOTIFY)
        ->and(TypeLien::detectFromUrl('https://www.deezer.com/track/123'))->toBe(TypeLien::DEEZER)
        ->and(TypeLien::detectFromUrl('https://music.apple.com/us/album/test'))->toBe(TypeLien::APPLE_MUSIC)
        ->and(TypeLien::detectFromUrl('https://artist.bandcamp.com/album/test'))->toBe(TypeLien::BANDCAMP);

    // URLs non reconnues retournent AUTRE
    expect(TypeLien::detectFromUrl('https://example.com'))->toBe(TypeLien::AUTRE)
        ->and(TypeLien::detectFromUrl('https://google.com'))->toBe(TypeLien::AUTRE)
        ->and(TypeLien::detectFromUrl('not-a-url'))->toBe(TypeLien::AUTRE)
        ->and(TypeLien::detectFromUrl(''))->toBe(TypeLien::AUTRE);
});

/**
 * Test 13 : Vérifie que les couleurs sont des codes hexadécimaux valides
 */
test('TypeLien colors are valid hex codes', function (): void {
    foreach (TypeLien::cases() as $type) {
        $couleur = $type->couleur();
        expect($couleur)->toMatch('/^#[0-9A-F]{6}$/');
    }
});

/**
 * Test 14 : Vérifie la cohérence entre types musicaux et isMusical()
 */
test('TypeLien musical types consistency', function (): void {
    $typesMusicaux = TypeLien::musicaux();

    foreach (TypeLien::cases() as $type) {
        if (in_array($type, $typesMusicaux, true)) {
            expect($type->isMusical())->toBeTrue("Le type {$type->value} devrait être musical");
        } else {
            expect($type->isMusical())->toBeFalse("Le type {$type->value} ne devrait pas être musical");
        }
    }
});

/**
 * Test 15 : Vérifie le comportement avec des URLs edge cases
 */
test('TypeLien handles edge case URLs correctly', function (): void {
    // URLs avec paramètres complexes
    expect(TypeLien::detectFromUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ&list=PLTest&index=1'))->toBe(TypeLien::YOUTUBE);

    // URLs avec fragments
    expect(TypeLien::detectFromUrl('https://open.spotify.com/track/4iV5W9uYEdYUVa79Axb7Rh#test'))->toBe(TypeLien::SPOTIFY);

    // Bandcamp avec sous-domaines complexes
    expect(TypeLien::detectFromUrl('https://my-super-long-artist-name.bandcamp.com/track/song-name'))->toBe(TypeLien::BANDCAMP);

    // Wikipedia avec chemins complexes
    expect(TypeLien::detectFromUrl('https://fr.wikipedia.org/wiki/Cat%C3%A9gorie:Test'))->toBe(TypeLien::WIKIPEDIA);
});

/**
 * Test 16 : Vérifie que les patterns d'URL sont cohérents avec la validation
 */
test('TypeLien URL patterns are consistent with validation', function (): void {
    foreach (TypeLien::cases() as $type) {
        $pattern = $type->getUrlPattern();

        if ($pattern !== null) {
            // Vérifie qu'une URL basée sur le pattern est validée correctement
            $testUrl = str_replace('*', 'test', $pattern).'test-path';
            expect($type->validateUrl($testUrl))->toBeTrue("Le pattern {$pattern} devrait valider {$testUrl}");
        }
    }
});
