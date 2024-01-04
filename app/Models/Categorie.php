<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class Categorie extends Model
{
    use HasFactory, HasSEO;

    protected $fillable = [
        'titre',
        'slug',
        'icone',
        'description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }


    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
