<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusFiche;
use App\Enums\TypeFiche;
use App\Models\Concerns\HasOptimizedRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property string $titre
 * @property string|null $soustitre
 * @property string $slug
 * @property string|null $illustration
 * @property string|null $illustration_alt
 * @property string $description
 * @property string $contenu
 * @property TypeFiche $type_fiche
 * @property StatusFiche $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $featured_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Categorie> $categories
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tag> $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Fiche> $fichesConnexes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Element> $elements
 */
final class Fiche extends Model
{
    use HasFactory, HasOptimizedRelations, Searchable, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
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

    /** @var array<string, string> */
    protected $casts = [
        'type_fiche' => TypeFiche::class,
        'status' => StatusFiche::class,
        'published_at' => 'datetime',
        'featured_at' => 'datetime',
    ];

    // Relations

    /**
     * @return BelongsToMany<Categorie, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'categories_fiches')
            ->withTimestamps()
            ->orderBy('type_categorie')
            ->orderBy('titre');
    }

    /**
     * @return BelongsToMany<Tag, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'fiches_tags')
            ->withTimestamps()
            ->where('is_active', true)
            ->orderBy('ordre')
            ->orderBy('nom');
    }

    /**
     * @return BelongsToMany<Fiche, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function fichesConnexes(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'fiches_connexes', 'fiche_id', 'fiche_connexe_id')
            ->published()
            ->withTimestamps();
    }

    /**
     * @return HasMany<Element, $this>
     */
    public function elements(): HasMany
    {
        return $this->hasMany(Element::class)->orderBy('ordre');
    }

    // Scopes

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', StatusFiche::PUBLIE)
            ->whereNotNull('published_at');
    }

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->whereNotNull('featured_at');
    }

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopeOfType(Builder $query, TypeFiche $type): void
    {
        $query->where('type_fiche', $type);
    }

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopeOfStatus(Builder $query, StatusFiche $status): void
    {
        $query->where('status', $status);
    }

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopeWithCategorie(Builder $query, Categorie $categorie): void
    {
        $query->whereHas('categories', fn (Builder $q): Builder => $q->where('categories.id', $categorie->id));
    }

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopeWithTag(Builder $query, Tag $tag): void
    {
        $query->whereHas('tags', fn (Builder $q): Builder => $q->where('tags.id', $tag->id));
    }

    // Search

    public function shouldBeSearchable(): bool
    {
        return $this->status === StatusFiche::PUBLIE && $this->published_at !== null;
    }

    #[SearchUsingPrefix(['titre', 'slug'])]
    #[SearchUsingFullText(['contenu', 'description'])]
    /**
     * @return array{
     *     titre: string,
     *     soustitre: string,
     *     slug: string,
     *     description: string,
     *     contenu: string,
     *     categories: string,
     *     tags: string
     * }
     */
    public function toSearchableArray(): array
    {
        return [
            'titre' => (string) $this->titre,
            'soustitre' => (string) ($this->soustitre ?? ''),
            'slug' => (string) $this->slug,
            'description' => (string) $this->description,
            'contenu' => strip_tags($this->contenu),
            'categories' => $this->categories->pluck('titre')->join(' '),
            'tags' => $this->tags->pluck('nom')->join(' '),
        ];
    }

    /**
     * Retourne la couleur du type de fiche
     */
    public function getTypeCouleur(): string
    {
        return $this->type_fiche->couleur();
    }

    /**
     * Retourne l'icône du type de fiche
     */
    public function getTypeIcone(): string
    {
        return $this->type_fiche->icone();
    }

    /**
     * Vérifie si la fiche peut changer vers un nouveau statut
     */
    public function canTransitionTo(StatusFiche $newStatus): bool
    {
        return $this->status->canTransitionTo($newStatus);
    }

    /**
     * Change le statut de la fiche avec validation
     */
    public function transitionTo(StatusFiche $newStatus): bool
    {
        if (! $this->canTransitionTo($newStatus)) {
            return false;
        }

        $this->status = $newStatus;

        if ($newStatus === StatusFiche::PUBLIE && $this->published_at === null) {
            $this->published_at = now();
        }

        return $this->save();
    }

    /**
     * Attache une fiche connexe avec validation anti auto-référence
     */
    public function attachFicheConnexe(self $ficheConnexe): void
    {
        if ($this->id === $ficheConnexe->id) {
            throw new InvalidArgumentException('Une fiche ne peut pas être connexe à elle-même');
        }

        if (! $this->fichesConnexes()->where('fiches.id', $ficheConnexe->id)->exists()) {
            $this->fichesConnexes()->attach($ficheConnexe);
        }
    }

    /**
     * Calcule le temps de lecture estimé
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->contenu));

        return (int) ceil($wordCount / 200); // 200 mots par minute
    }

    /**
     * Retourne le nombre de mots
     */
    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags($this->contenu));
    }

    // Méthodes métier

    /**
     * Retourne les relations à charger de manière optimisée
     *
     * @return array<int, string>
     */
    protected function getOptimizedRelations(): array
    {
        return [
            'categories:id,titre,slug,type_categorie',
            'tags:id,nom,slug',
            'elements',
        ];
    }
}
