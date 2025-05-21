<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatutFiche;
use App\Enums\TypeFiche;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

/**
 * @property int $id
 * @property int $domaine_id
 * @property string $titre
 * @property string|null $soustitre
 * @property string|null $illustration
 * @property string $slug
 * @property string|null $description
 * @property string|null $contenu
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<Fiche> published()
 * @method static Builder<Fiche> query()
 */
final class Fiche extends Model
{
    use HasFactory, HasSEO, Searchable, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'titre',
        'soustitre',
        'illustration',
        'alt_illustration',
        'slug',
        'description',
        'contenu',
        'type',
        'statut',
        'published_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => TypeFiche::class,
        'statut' => StatutFiche::class,
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Used by Laravel Scout to determine index eligibility.
     */
    public function searchable(): ?Carbon
    {
        return $this->published_at;
    }

    /**
     * @return array<string, string>
     */
    #[SearchUsingPrefix(['titre', 'slug'])]
    #[SearchUsingFullText(['contenu'])]
    public function toSearchableArray(): array
    {
        return [
            'titre' => $this->titre,
            'slug' => $this->slug,
            'contenu' => $this->contenu,
        ];
    }

    /**
     * @param  Builder<Fiche>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    /**
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return BelongsToMany<Categorie>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class);
    }

    /**
     * Scope: Filter fiches by Domaine through categories
     *
     * @param  Builder<Fiche>  $query
     * @param  positive-int  $domaineId
     * @return Builder<Fiche>
     */
    public function scopeFromDomaine(Builder $query, int $domaineId): Builder
    {
        return $query->whereHas('categories', static function (Builder $query) use ($domaineId): void {
            $query->whereHas('domaines', static function (Builder $query) use ($domaineId): void {
                $query->where('domaines.id', $domaineId);
            });
        });
    }
}
