<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOptimizedRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $nom
 * @property string $slug
 * @property string|null $description
 * @property int|null $fiche_id
 * @property int $ordre
 * @property bool $is_featured
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Fiche|null $fiche
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Fiche> $fiches
 */
final class Tag extends Model
{
    use HasFactory, HasOptimizedRelations, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'fiche_id',
        'ordre',
        'is_featured',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'ordre' => 'integer',
        'fiche_id' => 'integer',
    ];

    // Relations

    /**
     * @return BelongsToMany<Fiche, $this, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function fiches(): BelongsToMany
    {
        return $this->belongsToMany(Fiche::class, 'fiches_tags')
            ->withTimestamps()
            ->published()
            ->orderBy('titre');
    }

    /**
     * @return BelongsTo<Fiche, $this>
     */
    public function fiche(): BelongsTo
    {
        return $this->belongsTo(Fiche::class)->published();
    }

    // Scopes

    /**
     * @param  Builder<Tag>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @param  Builder<Tag>  $query
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * @param  Builder<Tag>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('ordre')->orderBy('nom');
    }

    /**
     * @param  Builder<Tag>  $query
     */
    public function scopeWithFichesCount(Builder $query): void
    {
        $query->withCount(['fiches' => fn (Builder $q): Builder => $q->where('published_at', '!=', null)]);
    }

    /**
     * Vérifie si le tag a des fiches publiées
     */
    public function hasPublishedFiches(): bool
    {
        return $this->fiches()->exists();
    }

    /**
     * Retourne le nombre de fiches publiées
     */
    public function getPublishedFichesCount(): int
    {
        return $this->fiches()->count();
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
            'fiche:id,titre,slug',
        ];
    }
}
