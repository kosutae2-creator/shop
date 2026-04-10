<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategorije';
    protected static ?string $pluralModelLabel = 'Kategorije';
    protected static ?string $modelLabel = 'kategorija';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kategorizacija')
                    ->description('Napravite glavnu kategoriju ili je dodelite nekoj postojećoj kao podkategoriju.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Naziv kategorije')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        
                        TextInput::make('slug')
                            ->label('Putanja (Slug)')
                            ->required()
                            ->unique(Category::class, 'slug', ignoreRecord: true),

                        Select::make('parent_id')
                            ->label('Nadkategorija (Opciono)')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Ostavite prazno ako je ovo Glavna Kategorija')
                            ->columnSpanFull()
                            ->native(false),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Pripada kategoriji')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Glavna')
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Putanja')
                    ->badge(),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Broj proizvoda')
                    ->counts('products'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}