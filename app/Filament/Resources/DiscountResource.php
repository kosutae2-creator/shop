<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Popusti i Kuponi';
    protected static ?string $pluralModelLabel = 'Popusti';
    protected static ?string $modelLabel = 'popust';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)
                ->schema([
                    // LEVA KOLONA: Glavna podešavanja
                    Forms\Components\Section::make('Osnovne Informacije')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Naziv akcije')
                                ->placeholder('npr. Sezonsko sniženje')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('type')
                                ->label('Tip popusta')
                                ->options([
                                    'global' => 'Globalna Akcija (Automatski na sajtu)',
                                    'coupon' => 'Kupon (Kupac unosi kod)',
                                ])
                                ->required()
                                ->live() // Ovo omogućava da se polja ispod menjaju odmah
                                ->default('global'),

                            Forms\Components\TextInput::make('percentage')
                                ->label('Procenat popusta')
                                ->numeric()
                                ->suffix('%')
                                ->minValue(1)
                                ->maxValue(100)
                                ->required(),
                        ])
                        ->columnSpan(2),

                    // DESNA KOLONA: Status i aktivacija
                    Forms\Components\Section::make('Status')
                        ->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktivno')
                                ->helperText('Isključite da privremeno deaktivirate popust.')
                                ->default(true)
                                ->onColor('success')
                                ->live()
                                ->afterStateUpdated(function ($state, $get, $record) {
                                    // AUTOMATIKA: Ako aktiviraš novi Globalni popust, 
                                    // isključi sve ostale stare globalne popuste.
                                    if ($state && $get('type') === 'global') {
                                        Discount::where('type', 'global')
                                            ->where('id', '!=', $record?->id)
                                            ->update(['is_active' => false]);
                                    }
                                }),
                        ])
                        ->columnSpan(1),

                    // DONJA SEKCIJA: Specifična polja za Kupon (skrivena ako je Global)
                    Forms\Components\Section::make('Podešavanja Kupona')
                        ->description('Ova polja popunite samo ako ste izabrali tip "Kupon".')
                        ->hidden(fn (Forms\Get $get): bool => $get('type') !== 'coupon')
                        ->schema([
                            Forms\Components\TextInput::make('code')
                                ->label('Promo Kod')
                                ->placeholder('npr. TEST10')
                                ->unique(ignoreRecord: true)
                                ->required(fn (Forms\Get $get): bool => $get('type') === 'coupon')
                                ->helperText('Kod koji kupci kucaju u korpi.'),

                            Forms\Components\TextInput::make('usage_limit')
                                ->label('Limit korišćenja')
                                ->numeric()
                                ->placeholder('npr. 100')
                                ->helperText('Koliko puta se kupon može iskoristiti ukupno.'),

                            Forms\Components\TextInput::make('min_order_amount')
                                ->label('Minimalni iznos korpe')
                                ->numeric()
                                ->prefix('RSD')
                                ->helperText('Kupon važi samo iznad ovog iznosa.'),

                            Forms\Components\DateTimePicker::make('expires_at')
                                ->label('Datum isteka')
                                ->helperText('Kada kupon prestaje da važi.'),
                        ])
                        ->columns(2)
                        ->columnSpan(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Discount $record): string => $record->code ? "Kod: {$record->code}" : 'Globalna akcija'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'global' => 'warning',
                        'coupon' => 'success',
                    }),

                Tables\Columns\TextColumn::make('percentage')
                    ->label('Popust')
                    ->suffix('%')
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('Iskorišćeno')
                    ->suffix(' puta')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'global' => 'Globalne akcije',
                        'coupon' => 'Kuponi',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivni'),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}