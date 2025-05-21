<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AuteurResource\Pages;
use App\Models\Auteur;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class AuteurResource extends Resource
{
    protected static ?string $model = Auteur::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'auteurs';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn (?Auteur $record): string => $record?->created_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn (?Auteur $record): string => $record?->updated_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('nom')
                        ->required(),

                    TextInput::make('prenom')
                        ->required(),

                    TextInput::make('email')
                        ->required(),

                    TextInput::make('password')
                        ->label('Mot de passe')
                        ->password()
                        ->autocomplete(false)
                        ->required(),

                    FileUpload::make('image_profil')
                        ->directory('auteur-profil')
                        ->preserveFilenames(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image_profil'),
            TextColumn::make('nom')
                ->formatStateUsing(fn ($state, Auteur $auteur): string => $auteur->nom.' '.$auteur->prenom),
            TextColumn::make('email')
                ->searchable()
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Date de création')
                ->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuteurs::route('/'),
            'create' => Pages\CreateAuteur::route('/create'),
            'edit' => Pages\EditAuteur::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email'];
    }
}
