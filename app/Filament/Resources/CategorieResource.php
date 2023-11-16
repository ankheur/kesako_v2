<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategorieResource\Pages;
use App\Models\Categorie;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategorieResource extends Resource
{
    protected static ?string $model = Categorie::class;

    protected static ?string $slug = 'categories';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('created_at')
                ->label('Date de création')
                ->content(fn(?Categorie $record): string => $record?->created_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

            Placeholder::make('updated_at')
                ->label('Dernière modification')
                ->content(fn(?Categorie $record): string => $record?->updated_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

            Section::make()
                ->schema([
                    TextInput::make('titre')
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->live(onBlur: true)
                        ->required(),

                    TextInput::make('slug')
                        ->required(),

                    FileUpload::make('icone')
                        ->directory('categorie-icones')
                        ->preserveFilenames()
                        ->required(),

                    Textarea::make('description')
                        ->rows(3)
                        ->autosize(),

                    DatePicker::make('published_at')
                        ->label('Date de publication'),
                ])

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('titre'),

            TextColumn::make('slug')
                ->searchable()
                ->sortable(),

            ImageColumn::make('icone'),

            TextColumn::make('published_at')
                ->label('Published Date')
                ->date(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategorie::route('/create'),
            'edit' => Pages\EditCategorie::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug'];
    }
}
