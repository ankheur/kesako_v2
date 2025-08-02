<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TypeCategorie;
use App\Models\Concerns\HasOptimizedRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $titre
 * @property string $slug
 * @property TypeCategorie $type_categorie
 * @property string|null $description
 * @property int|null $fiche_id
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Fiche|null $fiche
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Domaine> $domaines
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Fiche> $fiches
 */
final class Categorie extends Model
{
    use HasFactory, HasOptimizedRelations, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'titre',
        'slug',
        'type_categorie',
        'description',
        'fiche_id',
        'ordre',
        'published_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'type_categorie' => TypeCategorie::class,
        'published_at' => 'datetime',
        'ordre' => 'integer',
        'fiche_id' => 'integer',
    ];

    // Relations

    /**
     * @return BelongsToMany<Domaine, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function domaines(): BelongsToMany
    {
        return $this->belongsToMany(Domaine::class, 'domaines_categories')
            ->withTimestamps()
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
     * @return BelongsToMany<Fiche, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function fiches(): BelongsToMany
    {
        return $this->belongsToMany(Fiche::class, 'categories_fiches')
            ->withTimestamps()
            ->orderBy('titre');
    }

    // Scopes

    /**
     * @param  Builder<Categorie>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    /**
     * @param  Builder<Categorie>  $query
     */
    public function scopeOfType(Builder $query, TypeCategorie $type): void
    {
        $query->where('type_categorie', $type);
    }

    /**
     * @param  Builder<Categorie>  $query
     */
    public function scopeForDomaine(Builder $query, Domaine $domaine): void
    {
        $query->whereHas('domaines', fn (Builder $q): Builder => $q->where('domaines.id', $domaine->id));
    }

    /**
     * @param  Builder<Categorie>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('ordre')->orderBy('titre');
    }

    /**
     * Retourne la couleur du type de catégorie
     */
    public function getTypeCouleur(): string
    {
        return $this->type_categorie->couleur();
    }

    /**
     * Retourne l'icône du type de catégorie
     */
    public function getTypeIcone(): string
    {
        return $this->type_categorie->icone();
    }

    /**
     * Vérifie si la catégorie a des fiches publiées
     */
    public function hasPublishedFiches(): bool
    {
        return $this->fiches()->published()->exists();
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
            'domaines:id,titre,slug,couleur',
            'fiche:id,titre,slug',
        ];
    }
}
