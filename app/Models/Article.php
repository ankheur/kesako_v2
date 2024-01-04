<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class Article extends Model implements CanVisit
{
    use Searchable, HasFactory, SoftDeletes, HasVisits, HasSEO;

    protected $with = [
        'categorie'
    ];

    protected $fillable = [
        'categorie_id',
        'titre',
        'soustitre',
        'illustration',
        'slug',
        'description',
        'contenu',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function searchable()
    {
        return $this->published_at;
    }

    #[SearchUsingPrefix(['titre', 'slug'])]
    #[SearchUsingFullText(['contenu'])]
    public function toSearchableArray(): array
    {
        return [
            'titre' => $this->titre,
            'slug' => $this->slug,
            'contenu' => $this->contenu
        ];
    }

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }
}
