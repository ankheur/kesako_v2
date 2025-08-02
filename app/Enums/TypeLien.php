<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeLien: string
{
    case WIKIPEDIA = 'wikipedia';
    case YOUTUBE = 'youtube';
    case SPOTIFY = 'spotify';
    case DEEZER = 'deezer';
    case APPLE_MUSIC = 'apple_music';
    case BANDCAMP = 'bandcamp';
    case AUTRE = 'autre';

    /**
     * Retourne tous les types sous forme de tableau pour les selects
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }

    /**
     * Types de liens musicaux
     *
     * @return array<int, TypeLien>
     */
    public static function musicaux(): array
    {
        return [
            self::SPOTIFY,
            self::DEEZER,
            self::APPLE_MUSIC,
            self::BANDCAMP,
            self::YOUTUBE,
        ];
    }

    /**
     * Détecte automatiquement le type de lien depuis une URL
     */
    public static function detectFromUrl(string $url): self
    {
        foreach (self::cases() as $type) {
            if ($type->validateUrl($url)) {
                return $type;
            }
        }

        return self::AUTRE;
    }

    public function label(): string
    {
        return match ($this) {
            self::WIKIPEDIA => 'Wikipedia',
            self::YOUTUBE => 'YouTube',
            self::SPOTIFY => 'Spotify',
            self::DEEZER => 'Deezer',
            self::APPLE_MUSIC => 'Apple Music',
            self::BANDCAMP => 'Bandcamp',
            self::AUTRE => 'Autre',
        };
    }

    public function couleur(): string
    {
        return match ($this) {
            self::WIKIPEDIA => '#000000',
            self::YOUTUBE => '#FF0000',
            self::SPOTIFY => '#1DB954',
            self::DEEZER => '#FF6600',
            self::APPLE_MUSIC => '#FA2D48',
            self::BANDCAMP => '#629AA0',
            self::AUTRE => '#9CA3AF',
        };
    }

    public function icone(): string
    {
        return match ($this) {
            self::WIKIPEDIA => 'wikipedia',
            self::YOUTUBE => 'youtube',
            self::SPOTIFY => 'music',
            self::DEEZER => 'music',
            self::APPLE_MUSIC => 'music',
            self::BANDCAMP => 'music',
            self::AUTRE => 'link',
        };
    }

    /**
     * Préfixe pour valider le format d'URL
     */
    public function getUrlPattern(): ?string
    {
        return match ($this) {
            self::WIKIPEDIA => 'https://*.wikipedia.org/',
            self::YOUTUBE => 'https://www.youtube.com/',
            self::SPOTIFY => 'https://open.spotify.com/',
            self::DEEZER => 'https://www.deezer.com/',
            self::APPLE_MUSIC => 'https://music.apple.com/',
            self::BANDCAMP => 'https://*.bandcamp.com/',
            self::AUTRE => null, // Pas de validation spécifique
        };
    }

    /**
     * Valide qu'une URL correspond au type de lien
     */
    public function validateUrl(string $url): bool
    {
        $pattern = $this->getUrlPattern();

        if ($pattern === null) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        }

        $escapedPattern = str_replace('*', '.*', $pattern);
        $escapedPattern = '/^'.str_replace('/', '\/', $escapedPattern).'/';

        $result = preg_match($escapedPattern, $url);

        return $result === 1;
    }

    /**
     * Vérifie si le type est musical
     */
    public function isMusical(): bool
    {
        return in_array($this, self::musicaux(), true);
    }

    /**
     * Retourne le domaine de l'URL pour ce type
     */
    public function getDomain(): ?string
    {
        $pattern = $this->getUrlPattern();

        if ($pattern === null) {
            return null;
        }

        // Extraire le domaine du pattern
        $pattern = str_replace(['https://', 'http://'], '', $pattern);
        $pattern = str_replace('*', 'www', $pattern);

        return mb_rtrim($pattern, '/');
    }
}
