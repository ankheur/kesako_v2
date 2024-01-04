<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use RalphJSmit\Filament\SEO\SEO;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $slug = 'articles';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form->schema([

                Placeholder::make('created_at')
                    ->label('Date de création')
                    ->content(fn(?Article $record): string => $record?->created_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Dernière modification')
                    ->content(fn(?Article $record): string => $record?->updated_at?->locale('fr_FR')->isoFormat('DD/MM/YYYY à HH:mm:ss') ?? '-'),

                Section::make()
                    ->schema([
                        TextInput::make('titre')
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
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
                            ->directory('article-illustrations')
                            ->preserveFilenames(),
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
                    ]),

                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('categorie_id')
                            ->relationship('categorie', 'titre')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('published_at')
                            ->label('Date de publication')
                            ->maxDate(now()),
                    ]),

                Section::make()
                    ->schema([
                        SEO::make(),
                    ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('illustration'),

            TextColumn::make('titre')
                ->searchable()
                ->sortable(),

            TextColumn::make('slug'),

            TextColumn::make('published_at')
                ->label('Date de publication')
                ->date()
                ->sortable(),

            TextColumn::make('visits_count')
                ->label('Visites')
                ->counts('visits')
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug'];
    }
}
