<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOptimizedRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $titre
 * @property string $slug
 * @property string|null $icone
 * @property string|null $description
 * @property string $couleur
 * @property int|null $fiche_id
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Fiche|null $fiche
 * @property-read Fiche|null $fichePubliee
 * @property-read Collection<int, Categorie> $categories
 */
final class Domaine extends Model
{
    use HasFactory, HasOptimizedRelations, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'titre',
        'slug',
        'icone',
        'description',
        'couleur',
        'fiche_id',
        'published_at',
        'ordre',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'published_at' => 'datetime',
        'ordre' => 'integer',
        'fiche_id' => 'integer',
    ];

    // Relations

    /**
     * @return BelongsToMany<Categorie, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'domaines_categories')
            ->withTimestamps()
            ->orderBy('type_categorie')
            ->orderBy('ordre')
            ->orderBy('titre');
    }

    /**
     * @return BelongsTo<Fiche, $this>
     */
    public function fiche(): BelongsTo
    {
        return $this->belongsTo(Fiche::class);
    }

    /**
     * @return BelongsTo<Fiche, $this>
     */
    public function fichePubliee(): BelongsTo
    {
        return $this->belongsTo(Fiche::class, 'fiche_id')->published();
    }

    /**
     * Toutes les fiches liées via les catégories
     *
     * @return Collection<int, Fiche>
     */
    public function getAllFiches(): Collection
    {
        /** @var Builder<Fiche> $query */
        $query = Fiche::whereHas('categories', function (Builder $query): void {
            $query->whereHas('domaines', function (Builder $subQuery): void {
                $subQuery->where('domaines.id', $this->id);
            });
        });

        return $query->get();
    }

    /**
     * Fiches publiées liées via les catégories
     *
     * @return Collection<int, Fiche>
     */
    public function getFichesPubliees(): Collection
    {
        /** @var Builder<Fiche> $query */
        $query = Fiche::published()
            ->whereHas('categories', function ($query): void {
                $query->whereHas('domaines', function ($subQuery): void {
                    $subQuery->where('domaines.id', $this->id);
                });
            });

        return $query->get();
    }

    // Scopes

    /**
     * @param  Builder<Domaine>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    /**
     * @param  Builder<Domaine>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('ordre')->orderBy('titre');
    }

    /**
     * @param  Builder<Domaine>  $query
     */
    public function scopeWithCounts(Builder $query): void
    {
        $query->withCount(['categories']);
    }

    /**
     * Vérifie si le domaine a du contenu publié
     */
    public function hasPublishedContent(): bool
    {
        if ($this->categories()->published()->exists()) {
            return true;
        }
        return $this->getFichesPubliees()->isNotEmpty();
    }

    /**
     * Retourne les catégories groupées par type
     *
     * @return array<string, Collection<int, Categorie>>
     */
    public function getCategoriesGroupedByType(): array
    {
        $categories = $this->categories()->published()->get();
        /** @var array<string, Collection<int, Categorie>> $grouped */
        $grouped = [];

        foreach ($categories as $categorie) {
            $type = $categorie->type_categorie->value;
            if (! isset($grouped[$type])) {
                /** @var Collection<int, Categorie> $collection */
                $collection = new Collection();
                $grouped[$type] = $collection;
            }
            $grouped[$type]->push($categorie);
        }

        return $grouped;
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
        ];
    }
}
