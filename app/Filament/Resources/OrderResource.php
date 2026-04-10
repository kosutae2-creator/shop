<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Porudzbine';
    protected static ?string $pluralModelLabel = 'Porudzbine';
    protected static ?string $modelLabel = 'Porudzbinu';

    /**
     * FORMA ZA EDITOVANJE (Sa prikazom popusta na artiklima)
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informacije o kupcu')
                    ->schema([
                        TextInput::make('customer_name')->label('Ime i prezime')->disabled(),
                        TextInput::make('customer_email')->label('Email kupca')->disabled(), 
                        TextInput::make('address')->label('Adresa dostave')->disabled(),
                        TextInput::make('phone')->label('Telefon')->disabled(),
                        TextInput::make('total_amount')->label('Ukupan iznos')->suffix(' RSD')->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status porudžbine')
                            ->options([
                                'novo' => 'Novo 🔵',
                                'pending' => 'Na čekanju ⏳',
                                'poslato' => 'Poslato 🚚',
                                'otkazano' => 'Otkazano 🔴',
                            ])
                            ->native(false)
                            ->required(),
                    ])->columns(2),

                Section::make('Sadržaj porudžbine')
                    ->schema([
                        Forms\Components\Placeholder::make('items_list')
                            ->label('Naručeni artikli')
                            ->content(function ($record) {
                                if (!$record || !$record->items) return 'Nema podataka';
                                
                                $items = is_array($record->items) ? $record->items : json_decode($record->items, true);
                                $html = '<div style="display: flex; flex-direction: column; gap: 1rem;">';

                                foreach ($items as $item) {
                                    $imagePath = isset($item['image']) ? asset('storage/' . $item['image']) : 'https://placehold.co/50x50?text=No+Img';
                                    
                                    // Prikaz opcija (boja, veličina itd.)
                                    $optionsHtml = "";
                                    if (!empty($item['options']) && is_array($item['options'])) {
                                        foreach ($item['options'] as $key => $value) {
                                            $optionsHtml .= "<span style='background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-right: 5px; font-weight: bold; color: #333;'>{$key}: {$value}</span>";
                                        }
                                    }

                                    // NOVO: Logika za popust
                                    $discountHtml = "";
                                    if (!empty($item['discount']) && $item['discount'] > 0) {
                                        $discountHtml = "<span style='background: #be123c; color: white; padding: 1px 5px; border-radius: 4px; font-size: 10px; margin-left: 8px;'>-{$item['discount']}%</span>";
                                    }

                                    $html .= "
                                        <div style='display: flex; align-items: center; gap: 15px; padding: 10px; border: 1px solid #f1f1f1; border-radius: 12px; background: white;'>
                                            <img src='{$imagePath}' style='width: 50px; height: 50px; object-fit: contain; border-radius: 8px; border: 1px solid #eee;'>
                                            <div style='flex: 1;'>
                                                <div style='font-weight: 800; font-style: italic; text-transform: uppercase; font-size: 13px; color: #111;'>
                                                    " . ($item['name'] ?? 'Nepoznato') . " 
                                                    {$discountHtml}
                                                    <span style='color: #fb7185; margin-left: 10px;'>x " . ($item['quantity'] ?? 1) . "</span>
                                                </div>
                                                <div style='margin-top: 4px;'>
                                                    {$optionsHtml}
                                                </div>
                                            </div>
                                            <div style='font-weight: bold; color: #111; font-size: 14px;'>
                                                " . number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') . " RSD
                                            </div>
                                        </div>
                                    ";
                                }

                                $html .= '</div>';
                                return new HtmlString($html);
                            })
                    ]),
            ]);
    }

    /**
     * GLAVNA TABELA (Svetli tekst za tamnu pozadinu)
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('items')
                    ->label('Artikli')
                    ->html()
                    ->formatStateUsing(function ($state, Order $record) {
                        $items = $record->items;
                        if (is_string($items)) $items = json_decode($items, true);
                        if (empty($items)) return '<span style="color: #bbb;">Prazna korpa</span>';
                        
                        $totalQty = collect($items)->sum('quantity');
                        $count = count($items);
                        
                        $output = "<div style='font-weight: 800; margin-bottom: 5px; color: #fff; font-size: 13px;'>Ukupno: {$totalQty} kom</div>";
                        
                        foreach (array_slice($items, 0, 3) as $item) {
                            $name = $item['name'] ?? 'Nepoznato';
                            $qty = $item['quantity'] ?? 1;
                            $discount = (!empty($item['discount']) && $item['discount'] > 0) ? " <small style='color: #fb7185;'>(-{$item['discount']}%)</small>" : "";
                            
                            $output .= "<div style='font-size: 12px; color: #f3f4f6; line-height: 1.3;'>";
                            $output .= "<span style='color: #fb7185; font-weight: bold;'>{$qty}x</span> {$name}{$discount}";
                            $output .= "</div>";
                        }

                        if ($count > 3) {
                            $output .= "<div style='font-size: 11px; color: #818cf8; font-weight: bold; margin-top: 4px;'>+ još " . ($count - 3) . " stavke...</div>";
                        }

                        return $output;
                    }),

                TextColumn::make('customer_name')
                    ->label('Kupac / Telefon')
                    ->description(fn (Order $record): string => $record->phone ?? 'Nema telefona')
                    ->searchable(['customer_name', 'phone'])
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'novo' => 'info',
                        'pending' => 'warning',
                        'poslato' => 'success',
                        'otkazano' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'novo' => 'NOVO',
                        'pending' => 'ČEKANJE',
                        'poslato' => 'ISPORUČENO',
                        'otkazano' => 'OTKAZANO',
                        default => strtoupper($state),
                    }),

                TextColumn::make('total_amount')
                    ->label('Iznos')
                    ->money('RSD')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Datum')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'novo' => 'Novo',
                        'pending' => 'Na čekanju',
                        'poslato' => 'Poslato',
                        'otkazano' => 'Otkazano',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markAsShipped')
                    ->label(fn (Order $record) => $record->status === 'poslato' ? 'Poslato' : 'Označi kao poslato')
                    ->icon(fn (Order $record) => $record->status === 'poslato' ? 'heroicon-m-check-circle' : 'heroicon-m-truck')
                    ->color(fn (Order $record) => $record->status === 'poslato' ? 'gray' : 'success')
                    ->requiresConfirmation(fn (Order $record) => $record->status !== 'poslato')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'poslato']);
                    })
                    ->disabled(fn (Order $record) => $record->status === 'poslato'),
                
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsShippedBulk')
                        ->label('Označi odabrano kao poslato')
                        ->icon('heroicon-m-truck')
                        ->action(fn ($records) => $records->each->update(['status' => 'poslato'])),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'novo')->count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}