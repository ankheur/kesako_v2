<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TypeCategorie;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

final class Categorie extends Model
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
        'type',
        'slug',
        'published_at',
    ];

    /**
     * @return array<int, string>
     */
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
            Textarea::make('description'),
            Select::make('type')
                ->options(TypeCategorie::class)
                ->required(),
            DateTimePicker::make('published_at'),
        ];
    }

    /**
     * @return BelongsToMany<Domaine>
     */
    public function domaines(): BelongsToMany
    {
        return $this->belongsToMany(Domaine::class);
    }

    /**
     * @return BelongsToMany<Fiche>
     */
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
            'type' => TypeCategorie::class,
            'published_at' => 'datetime',
        ];
    }
}
