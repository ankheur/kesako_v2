<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

final class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'denomination',
        'description',
        'slug',
        'published_at',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('denomination')
                ->label('Dénomination')
                ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state)))
                ->live(onBlur: true)
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->required()
                ->maxLength(255),
            DateTimePicker::make('published_at'),
        ];
    }

    public function domaines(): BelongsToMany
    {
        return $this->belongsToMany(Domaine::class);
    }

    public function fiches(): BelongsToMany
    {
        return $this->belongsToMany(Fiche::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'published_at' => 'datetime',
        ];
    }
}
