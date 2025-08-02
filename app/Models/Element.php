<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TypeLien;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $fiche_id
 * @property string $nom
 * @property string|null $complement
 * @property string|null $image
 * @property string|null $image_alt
 * @property int|null $fiche_liee_id
 * @property string|null $lien_externe
 * @property TypeLien|null $type_lien
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Fiche $fiche
 * @property-read Fiche|null $ficheLiee
 */
final class Element extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
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

    /** @var array<string, string> */
    protected $casts = [
        'type_lien' => TypeLien::class,
        'ordre' => 'integer',
        'fiche_id' => 'integer',
        'fiche_liee_id' => 'integer',
    ];

    // Relations

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
    public function ficheLiee(): BelongsTo
    {
        return $this->belongsTo(Fiche::class, 'fiche_liee_id');
    }

    // Scopes

    /**
     * @param  Builder<Element>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('ordre');
    }

    /**
     * @param  Builder<Element>  $query
     */
    public function scopeWithLinks(Builder $query): void
    {
        $query->whereNotNull('lien_externe');
    }

    /**
     * @param  Builder<Element>  $query
     */
    public function scopeWithFiches(Builder $query): void
    {
        $query->whereNotNull('fiche_liee_id');
    }

    /**
     * @param  Builder<Element>  $query
     */
    public function scopeMusical(Builder $query): void
    {
        $query->whereIn('type_lien', [
            TypeLien::SPOTIFY->value,
            TypeLien::DEEZER->value,
            TypeLien::APPLE_MUSIC->value,
            TypeLien::BANDCAMP->value,
            TypeLien::YOUTUBE->value,
        ]);
    }

    // Méthodes métier

    /**
     * Vérifie si l'élément a un lien externe
     */
    public function hasLienExterne(): bool
    {
        return $this->lien_externe !== null;
    }

    /**
     * Vérifie si l'élément est lié à une fiche
     */
    public function hasFicheLiee(): bool
    {
        return $this->fiche_liee_id !== null;
    }

    /**
     * Retourne la couleur du type de lien
     */
    public function getLienCouleur(): string
    {
        return $this->type_lien?->couleur() ?? '#6B7280';
    }

    /**
     * Retourne l'icône du type de lien
     */
    public function getLienIcone(): string
    {
        return $this->type_lien?->icone() ?? 'link';
    }

    /**
     * Valide l'URL selon son type
     */
    public function validateLienExterne(): bool
    {
        if ($this->lien_externe === null || $this->type_lien === null) {
            return true;
        }

        return $this->type_lien->validateUrl($this->lien_externe);
    }

    /**
     * Détecte automatiquement le type de lien depuis l'URL
     */
    public function detectTypeLien(): void
    {
        if ($this->lien_externe !== null) {
            $this->type_lien = TypeLien::detectFromUrl($this->lien_externe);
        }
    }
}
