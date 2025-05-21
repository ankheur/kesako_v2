<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\StatutFiche;
use App\Enums\TypeFiche;
use App\Filament\Resources\FicheResource\Pages;
use App\Models\Categorie;
use App\Models\Fiche;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use RalphJSmit\Filament\SEO\SEO;

final class FicheResource extends Resource
{
    protected static ?string $model = Fiche::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $slug = 'fiches';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Placeholder::make('created_at')
                ->label('Date de création')
                ->content(fn (?Fiche $record): string => $record?->created_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

            Placeholder::make('updated_at')
                ->label('Dernière modification')
                ->content(fn (?Fiche $record): string => $record?->updated_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

            Section::make()
                ->schema([
                    TextInput::make('titre')
                        ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state)))
                        ->live(onBlur: true)
                        ->required()
                        ->maxLength(255),
                    TextInput::make('soustitre')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('illustration')
                        ->directory('fiche-illustrations')
                        ->preserveFilenames(),
                    Textarea::make('alt_illustration'),
                    RichEditor::make('description')
                        ->disableToolbarButtons([
                            'attachFiles',
                            'codeBlock',
                        ])
                        ->required(),
                    RichEditor::make('contenu')
                        ->disableToolbarButtons([
                            'attachFiles',
                            'codeBlock',
                        ])
                        ->required(),
                    Select::make('categories')
                        ->relationship('categories', 'denomination')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Categorie::getForm()),
                    Select::make('type')
                        ->options(TypeFiche::class)
                        ->required(),
                    Select::make('statut')
                        ->options(StatutFiche::class)
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?StatutFiche $state): void {
                            if ($state === StatutFiche::Publiee && $old !== StatutFiche::Publiee) {
                                $set('published_at', date('Y-m-d'));
                            } else {
                                $set('published_at', '');
                            }
                        })
                        ->required(),
                ]),

            Section::make()
                ->columns(2)
                ->schema([
                    /*Select::make('domaine_id')
                        ->relationship('domaine', 'titre')
                        ->searchable()
                        ->preload()
                        ->required(),*/

                    DatePicker::make('published_at')
                        ->label('Date de publication')
                        ->maxDate(now()),
                ]),

            Section::make()
                ->schema([
                    SEO::make(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('illustration'),

            TextColumn::make('titre')
                ->searchable()
                ->sortable(),

            TextColumn::make('type'),

            TextColumn::make('statut')
                ->badge(),

            TextColumn::make('published_at')
                ->label('Date de publication')
                ->date()
                ->sortable(),

            /*TextColumn::make('visits_count')
                ->label('Visites')
                ->counts('visits')*/
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFiches::route('/'),
            'create' => Pages\CreateFiche::route('/create'),
            'edit' => Pages\EditFiche::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug'];
    }
}
