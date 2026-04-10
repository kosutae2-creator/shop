<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Proizvodi';
    protected static ?string $pluralModelLabel = 'Proizvodi';
    protected static ?string $modelLabel = 'proizvod';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    // LEVA KOLONA (2/3 širine)
                    Section::make('Informacije o proizvodu')->schema([
                        TextInput::make('name')
                            ->label('Naziv proizvoda')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        
                        TextInput::make('slug')
                            ->label('URL Putanja')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Textarea::make('description')
                            ->label('Detaljan opis')
                            ->columnSpanFull()
                            ->rows(5),

                        // FIX: VARIJACIJE se sada čuvaju u 'variants' kolonu
                        Grid::make(2)->schema([
                            TagsInput::make('variants.sizes') // Mapa na variants JSON -> sizes
                                ->label('Dostupne Veličine')
                                ->placeholder('Dodaj i pritisni Enter (npr. S, M, L)'),

                            TagsInput::make('variants.colors') // Mapa na variants JSON -> colors
                                ->label('Dostupne Boje')
                                ->placeholder('Dodaj i pritisni Enter (npr. Crna, Bela)'),
                        ]),

                        // KARAKTERISTIKE (JSON polje 'options')
                        KeyValue::make('options')
                            ->label('Tehničke karakteristike / Specifikacije')
                            ->keyLabel('Svojstvo (npr. Snaga)')
                            ->valueLabel('Vrednost (npr. 500W)')
                            ->addActionLabel('Dodaj karakteristiku')
                            ->columnSpanFull(),
                            
                    ])->columnSpan(2),

                    // DESNA KOLONA (1/3 širine)
                    Section::make('Cena i Logistika')->schema([
                        Select::make('status')
                            ->label('Status vidljivosti')
                            ->options([
                                'active' => 'Aktivan (Vidi se na sajtu)',
                                'inactive' => 'Neaktivan (Sakriven)',
                            ])
                            ->default('active')
                            ->required()
                            ->native(false),

                        Toggle::make('is_featured')
                            ->label('Istaknuti proizvod')
                            ->helperText('Prikaži u sekciji "Izdvajamo"')
                            ->default(false)
                            ->onColor('success'),

                        // KATEGORIJE
                        Select::make('parent_category_id')
                            ->label('Glavna Kategorija')
                            ->options(Category::whereNull('parent_id')->pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('category_id', null))
                            ->dehydrated(false)
                            ->native(false),

                       Select::make('category_id')
    ->label('Kategorija')
    ->options(Category::all()->pluck('name', 'id')) // Vidi sve kategorije bez obzira na roditelje
    ->searchable()
    ->required()
    ->native(false),

                        TextInput::make('price')
                            ->label('Prodajna cena')
                            ->numeric()
                            ->required()
                            ->prefix('RSD'),

                        TextInput::make('old_price')
                            ->label('Stara cena')
                            ->numeric()
                            ->prefix('RSD'),

                        TextInput::make('stock')
                            ->label('Stanje')
                            ->numeric()
                            ->default(0),

                        FileUpload::make('image')
                            ->label('Glavna slika')
                            ->image()
                            ->directory('products')
                            ->required(),

                        Repeater::make('images')
                            ->relationship('images')
                            ->label('Galerija')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->image()
                                    ->directory('products/gallery')
                                    ->required(),
                            ])
                            ->grid(2)
                            ->collapsible(),
                            
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Slika')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_featured') // Promenjeno u Toggle za brzu izmenu u tabeli
                    ->label('Izdvojeno'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger'),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Cena')
                    ->money('RSD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategorija')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Samo istaknuti'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}